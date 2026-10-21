<?php

namespace project;

defined('CASTOR_USE_CHDIR') || define('CASTOR_USE_CHDIR', true);

use Castor\Attribute\AsArgument;
use Castor\Attribute\AsContext;
use Castor\Attribute\AsListener;
use Castor\Attribute\AsTask;
use Castor\Context;

use function Castor\capture;
use function Castor\context;
use function Castor\run;
use Castor\Docker\Event\RegisterServiceEvent;
use Castor\Docker\Service\MariaDBService;
use Castor\Docker\Service\PhpMode;
use Castor\Docker\Service\SymfonyService;

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
        ->withVersion('7.3')
        ->withMode(PhpMode::Fpm)
        ->addExtension('mysql')
        ->addExtension('gd')
        ->link($database)
        ->withDomain('jedisjeux.test')
        ->withHttpAccess()
    ;

    $event->addService($app);
}

#[AsTask(description: 'Deploy the application with Capistrano (staging or production)', namespace: 'app')]
function deploy(
    #[AsArgument(description: 'Stage to deploy', autocomplete: ['staging', 'production'])]
    string $stage = 'staging',
): void {
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
        'sh', '-c', 'bundle install --quiet && bundle exec cap "$0" deploy', $stage,
    ], context: context()->withTty()->withTimeout(null));
}
