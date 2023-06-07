<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Start session
 */
session_start();
/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('index', ['controller' => 'Home', 'action' => 'index']);
$router->add('tickets', ['controller' => 'Ticket', 'action' => 'index']);
$router->add('ticket/create', ['controller' => 'Ticket', 'action' => 'create']);
$router->add('login', ['controller' => 'LogController', 'action' => 'login  ']);
$router->add('logout', ['controller' => 'LogController', 'action' => 'logout  ']);
$router->add('register', ['controller' => 'LogController', 'action' => 'register  ']);
$router->add('{controller}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
