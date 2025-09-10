<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('mocktest/(:num)', 'Home::mocktest/$1');
$routes->get('course/(:segment)', 'Home::course/$1');
