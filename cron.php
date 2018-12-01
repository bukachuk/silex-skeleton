<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors',1);
date_default_timezone_set('UTC');
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/etc/config.php';

$app = new Silex\Application();
$app['debug'] = true;

use Knp\Provider\ConsoleServiceProvider;
use Silex\Application;
use Silex\Provider;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $connectionParams
));
$app->register(new \Project\Services\DoctrineORMServiceProvider(), array(
    'orm.proxies_dir'           => __DIR__ . "/tmp",
    'orm.proxies_namespace'     => 'Doctrine\ORM\Proxy\Proxy',
    'orm.auto_generate_proxies' => true,

    'orm.entities'              => array(array(
        'type'      => 'annotation',
        'path'      => __DIR__.'/../src/Entities',
        'namespace' => 'Project\Entities',
    )),
));

$app->register(new ConsoleServiceProvider(), array(
    'console.name'              => 'console tools',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__.'/..'
));

$app->register(new Provider\SwiftmailerServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$routingServiceProvider = new \Project\Services\RoutingServiceProvider();
require_once __DIR__ . '/src/routing.php';
$routingServiceProvider->addRoutes($app, $routes);
$app['swiftmailer.use_spool'] = false;

$app['command.controller'] = $app->protect(function($pid_file) use ($app) {
    $pid_file = "app/lock/" . $pid_file . ".pid";
    if( is_file($pid_file) ) {
    $pid = @file_get_contents($pid_file);
    if(posix_kill($pid,0)) {
        die('Daemon is active'.PHP_EOL);
    } else {
        if(!unlink($pid_file)) {
            exit(-1);
        }
    }
    }
    if(false === file_put_contents($pid_file,  getmypid())){
        die('Невозможно создать pid файл');
    }
    return false;
});
include_once __DIR__ . '/src/telegram.php';
$application = $app['console'];
$application->add(new \Project\Command\Check());
$application->add(new \Project\Command\Telegram());
$application->add(new \Project\Command\Import());
$application->run();

