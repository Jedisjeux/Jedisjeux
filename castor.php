<?php

namespace project;

defined('CASTOR_USE_CHDIR') || define('CASTOR_USE_CHDIR', true);

use Castor\Attribute\AsContext;
use Castor\Attribute\AsListener;
use Castor\Attribute\AsRawTokens;
use Castor\Attribute\AsTask;
use Castor\Context;

use function Castor\capture;
use function Castor\context;
use function Castor\run;
use function Castor\Docker\docker_compose;
use function Castor\Docker\docker_exit_code;
use Castor\Docker\Event\RegisterServiceEvent;
use Castor\Docker\Service\MariaDBService;
use Castor\Docker\Service\PhpIniScope;
use Castor\Docker\Service\PhpMode;
use Castor\Docker\Service\SymfonyService;
use Symfony\Component\Process\Process;

#[AsContext(default: true)]
function default_context(): Context
{
    return new Context([
        'root_domain' => 'jedisjeux.test',
    ]);
}

#[AsListener(RegisterServiceEvent::class)]
function register_services(RegisterServiceEvent $event): void
{
    $database = (new MariaDBService())
        ->withVersion('10.6')
        ->withRootPassword('root')
        ->withDatabase('jdj_dev')
    ;

    $event->addService($database);
    $app = (new SymfonyService('app'))
        ->withDirectory(__DIR__)
        // 8.3 runs in production, the CI also tests 8.5 to prepare the migration
        ->withVersion(getenv('APP_PHP_VERSION') ?: '8.3')
        ->withMode(PhpMode::Fpm)
        // Warming up the cache needs more than the default 128M
        ->withPhpIni(['memory_limit' => '512M'], PhpIniScope::Cli)
        ->addExtension('mysql')
        ->addExtension('gd')
        ->link($database)
        ->withDomain('jedisjeux.test')
        ->withHttpAccess()
        // The extensions phpstan.neon includes from vendor/ are built for PHPStan 1
        ->withPhpStanVersion('^1.12')
        // Same version as composer.lock, so the task and the vendor binary agree on the rules
        ->withPhpCsFixerVersion('3.95.27')
    ;

    $event->addService($app);
}

#[AsTask(description: 'Deploy the application to production with Capistrano', namespace: 'app')]
function deploy(): void
{
    $home = (string) getenv('HOME');
    $branch = trim(capture(['git', 'rev-parse', '--abbrev-ref', 'HEAD']));

    run([
        'docker', 'run', '--rm', '-it',
        '-v', __DIR__ . ':/app',
        '-w', '/app',
        '-v', 'jedisjeux-bundle:/usr/local/bundle',
        '-v', $home . '/.ssh/known_hosts:/root/.ssh/known_hosts',
        '-v', '/run/host-services/ssh-auth.sock:/run/host-services/ssh-auth.sock',
        '-e', 'SSH_AUTH_SOCK=/run/host-services/ssh-auth.sock',
        '-e', 'GIT_CONFIG_COUNT=1',
        '-e', 'GIT_CONFIG_KEY_0=safe.directory',
        '-e', 'GIT_CONFIG_VALUE_0=/app',
        '-e', 'BRANCH=' . $branch,
        'ruby:3.3',
        'sh', '-c', 'bundle install --quiet && bundle exec cap production deploy',
    ], context: context()->withTty()->withTimeout(null));
}

#[AsTask(description: 'Serve the documentation with live reload and open it in the browser', namespace: 'docs')]
function open(int $port = 4321): void
{
    $url = 'http://localhost:' . $port;

    // Opens the browser once the dev server answers, the server keeps the terminal
    $browser = new Process(['sh', '-c', \sprintf(
        'until curl -s -o /dev/null %1$s; do sleep 1; done; %2$s %1$s',
        $url,
        'Darwin' === \PHP_OS_FAMILY ? 'open' : 'xdg-open',
    )]);
    $browser->setTimeout(null);
    $browser->start();

    docs_npm('run dev -- --host 0.0.0.0 --port 4321', ['-p', $port . ':4321']);

    $browser->stop();
}

#[AsTask(description: 'Build the documentation as a static site in docs/dist', namespace: 'docs')]
function build(): int
{
    return docs_npm('run build');
}

/**
 * Node runs inside Docker (no Node needed on your machine), node_modules stays in a volume for the Linux binaries.
 *
 * @param list<string> $dockerOptions
 */
function docs_npm(string $command, array $dockerOptions = []): int
{
    $tty = Process::isTtySupported();

    return run([
        'docker', 'run', '--rm', ...($tty ? ['-it'] : []),
        '-v', __DIR__ . '/docs:/docs',
        '-v', 'jedisjeux-docs-node-modules:/docs/node_modules',
        '-w', '/docs',
        '-e', 'NPM_CONFIG_UPDATE_NOTIFIER=false',
        '-e', 'ASTRO_TELEMETRY_DISABLED=1',
        ...$dockerOptions,
        'node:24-alpine',
        'sh', '-c', 'npm ci --no-audit --no-fund --loglevel=error && npm ' . $command,
    ], context: context()->withTty($tty)->withTimeout(null)->withAllowFailure())->getExitCode();
}

#[AsTask(description: 'Run Behat scenarios in the app container, next to the headless Chrome', namespace: 'test')]
function behat(#[AsRawTokens] array $args = []): int
{
    // Not docker_compose_run(): Behat must share the app network to reach Chrome and the test vhost on localhost
    $tty = Process::isTtySupported();

    return docker_compose(
        // The CLI php.ini of withPhpIni() only reaches the builder, and the Symfony driver boots the kernel in this process
        ['exec', ...($tty ? [] : ['-T']), '-e', 'APP_ENV=test', 'app', 'php', '-d', 'memory_limit=1G', 'vendor/bin/behat', ...$args],
        context()->withTty($tty)->withTimeout(null)->withAllowFailure(),
    )->getExitCode();
}

#[AsTask(description: 'Download the importmap packages of the front-end in the builder container', namespace: 'app')]
function assets(): int
{
    // AssetMapper serves the other assets on the fly in dev and test, and compiles them on deploy
    return docker_exit_code(['php', 'bin/console', 'importmap:install'], 'app-builder');
}

#[AsTask(description: 'Run Psalm in the builder container', namespace: 'app:qa')]
function psalm(#[AsRawTokens] array $args = []): int
{
    return docker_exit_code(['php', '-d', 'memory_limit=2G', 'vendor/bin/psalm', '--show-info=false', ...$args], 'app-builder');
}

#[AsTask(description: 'Run PHPUnit in the builder container', namespace: 'test')]
function phpunit(#[AsRawTokens] array $args = []): int
{
    return docker_exit_code(['vendor/bin/phpunit', ...$args], 'app-builder', noDeps: false);
}
