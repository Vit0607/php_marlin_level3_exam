<!DOCTYPE html>
<html>
<?php
if( !session_id() ) @session_start();
require '../vendor/autoload.php';

use Aura\SqlQuery\QueryFactory;
use JasonGrimes\Paginator;
use Faker\Factory;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    Engine::class => function () {
        return new Engine('../app/views');
    },

    PDO::class => function () {
        $driver = 'mysql';
        $host = 'localhost';
        $database_name = 'php_marlin_app3';
        $username = 'root';
        $password = '';

        return new PDO("$driver:host=$host;dbname=$database_name", $username, $password);
    },

    Auth::class => function ($container) {
        return new Delight\Auth\Auth($container->get('PDO'), null, null, false);
    }
]);
$containerBuilder->ignorePhpDocErrors(false);
$container = $containerBuilder->build();
?>



<?php

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/home', ['App\controllers\HomeController', 'index']);
    $r->addRoute('GET', '/about', ['App\controllers\HomeController', 'about']);
    $r->addRoute('GET', '/verification', ['App\controllers\HomeController', 'email_verification']);
    $r->addRoute(['GET', 'POST'], '/login', ['App\controllers\HomeController', 'login']);
    $r->addRoute('GET', '/logout', ['App\controllers\HomeController', 'logout']);
    $r->addRoute(['GET', 'POST'], '/register', ['App\controllers\HomeController', 'register']);
    $r->addRoute(['GET', 'POST'], '/users', ['App\controllers\HomeController', 'users']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($routeInfo[1], $routeInfo[2]);
        // ... call $handler with $vars
        break;
}

?>

</html>




