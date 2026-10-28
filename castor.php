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
        ->withVersion('8.2')
        ->withMode(PhpMode::Fpm)
        // Warming up the cache needs more than the default 128M
        ->withPhpIni(['memory_limit' => '512M'], PhpIniScope::Cli)
        ->addExtension('mysql')
        ->addExtension('gd')
        ->link($database)
        ->withDomain('jedisjeux.test')
        ->withHttpAccess()
        // NodeSource has no node_16.x repository for current Debian, so 16 would silently install Debian's Node 20
        ->withNodeVersion('20')
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

#[AsTask(description: 'Run Behat scenarios in the app container, next to the headless Chrome', namespace: 'test')]
function behat(#[AsRawTokens] array $args = []): int
{
    // Not docker_compose_run(): Behat must share the app network to reach Chrome and the test vhost on localhost
    $tty = Process::isTtySupported();

    return docker_compose(
        ['exec', ...($tty ? [] : ['-T']), '-e', 'APP_ENV=test', 'app', 'vendor/bin/behat', ...$args],
        context()->withTty($tty)->withTimeout(null)->withAllowFailure(),
    )->getExitCode();
}

#[AsTask(description: 'Build the front-end assets in the builder container', namespace: 'app')]
function assets(): int
{
    // package.json asks for Node 16, the version production builds with, but the assets build fine on Node 20
    return docker_exit_code(
        ['sh', '-c', 'yarn install --frozen-lockfile && yarn build'],
        'app-builder',
        environment: ['YARN_IGNORE_ENGINES' => 'true'],
    );
}

#[AsTask(description: 'Run Psalm in the builder container', namespace: 'app:qa')]
function psalm(#[AsRawTokens] array $args = []): int
{
    return docker_exit_code(['php', '-d', 'memory_limit=2G', 'vendor/bin/psalm', '--show-info=false', ...$args], 'app-builder');
}

#[AsTask(description: 'Run phpspec in the builder container', namespace: 'test')]
function phpspec(#[AsRawTokens] array $args = []): int
{
    return docker_exit_code(['vendor/bin/phpspec', 'run', '--no-interaction', ...$args], 'app-builder');
}

#[AsTask(description: 'Run PHPUnit in the builder container', namespace: 'test')]
function phpunit(#[AsRawTokens] array $args = []): int
{
    return docker_exit_code(['vendor/bin/phpunit', ...$args], 'app-builder', noDeps: false);
}
