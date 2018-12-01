<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../etc/config.php';

use Silex\Application;
use Silex\Provider;
use Silex\Provider\FormServiceProvider;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;

$app = new Silex\Application();
$app->register(new Provider\SessionServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $connectionParams
));

$app->register(new \Project\Services\DoctrineORMServiceProvider(), array(
    'orm.proxies_dir' => __DIR__ . "/../tmp",
    'orm.proxies_namespace' => 'Doctrine\ORM\Proxy\Proxy',
    'orm.auto_generate_proxies' => true,
    'orm.entities' => array(array(
            'type' => 'annotation',
            'path' => __DIR__ . '/../src/Entities',
            'namespace' => 'Project\Entities',
        )),
));
$routingServiceProvider = new \Project\Services\RoutingServiceProvider();

$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../src/Views',
));
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
$app->register(new Provider\SwiftmailerServiceProvider());

require_once __DIR__ . '/../src/routing.php';

$routingServiceProvider->addRoutes($app, $routes);
$app->register(new FranMoreno\Silex\Provider\PagerfantaServiceProvider());
$app->error(
    function (Exception $e) use ($app) {
        if ($e instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return $app['twig']->render('404.html.twig', array('meesage' => $e->getMessage()));
        }
        return $app['twig']->render('500.html.twig', array('meesage' => $e->getMessage()));
    }
);

$app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions, $app) {
    $managerRegistry = new \Project\Extensions\ManagerRegistry(null, array(), array('orm.em'), null, null, $app['orm.proxies_namespace']);
    $managerRegistry->setContainer($app);
    $extensions[] = new DoctrineOrmExtension($managerRegistry);
    return $extensions;
}));

$app['swiftmailer.use_spool'] = false;

$app['pagerfanta.view.options'] = array(
    'routeName' => null,
    'routeParams' => array(),
    'pageParameter' => '[page]',
    'proximity' => 3,
    'next_message' => '&raquo;',
    'previous_message' => '&laquo;',
    'default_view' => 'default'
);

return $app;
