<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/drivers', 'DriverController::index');
$routes->get('/drivers/create', 'DriverController::create');
$routes->post('/drivers/store', 'DriverController::store');
$routes->get('/drivers/edit/(:num)', 'DriverController::edit/$1');
$routes->post('/drivers/update/(:num)', 'DriverController::update/$1');
$routes->get('/drivers/delete/(:num)', 'DriverController::delete/$1');
$routes->get('/seasons', 'SeasonController::index');
$routes->get('/seasons/(:num)', 'SeasonController::show/$1');
$routes->get('/drivers/trashed', 'DriverController::trashed');
$routes->get('/drivers/restore/(:num)', 'DriverController::restore/$1');
$routes->get('/drivers/forceDelete/(:num)', 'DriverController::forceDelete/$1');