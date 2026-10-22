<?php

namespace project;

defined('CASTOR_USE_CHDIR') || define('CASTOR_USE_CHDIR', true);

use Castor\Attribute\AsContext;
use Castor\Attribute\AsListener;
use Castor\Context;
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
        ->withVersion('8.2')
        ->withMode(PhpMode::Fpm)
        ->addExtension('mysql')
        ->addExtension('gd')
        ->link($database)
        ->withDomain('jedisjeux.test')
        ->withHttpAccess()
    ;

    $event->addService($app);
}
