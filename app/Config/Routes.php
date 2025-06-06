<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/drivers', 'DriverController::index');
$routes->get('/drivers/create', 'DriverController::create');
$routes->post('/drivers/store', 'DriverController::store');

$routes->get('/drivers/driver_details/(:num)', 'DriverController::show/$1');


$routes->get('/seasons', 'SeasonController::index');
$routes->get('/seasons/(:num)', 'SeasonController::show/$1');


$routes->get('/teams', 'TeamController::index');
$routes->get('/teams/(:num)/seasons/(:num)', 'TeamController::seasonResults/$1/$2');

$routes->get('/seasons/season25', 'ResultController::manageSeason');


$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::processRegister');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {

$routes->get('/drivers/edit/(:num)', 'DriverController::edit/$1');
$routes->post('/drivers/update/(:num)', 'DriverController::update/$1');
$routes->get('/drivers/delete/(:num)', 'DriverController::delete/$1');
$routes->get('/drivers/trashed', 'DriverController::trashed');
$routes->get('/drivers/restore/(:num)', 'DriverController::restore/$1');
$routes->get('/drivers/forceDelete/(:num)', 'DriverController::forceDelete/$1');

$routes->match(['get', 'post'], '/seasons/season25/add', 'ResultController::addRace');
$routes->match(['get', 'post'], '/seasons/season25/edit/(:num)', 'ResultController::editRace2025/$1');
$routes->get('/seasons/season25/delete/(:num)', 'ResultController::deleteRace2025/$1');

});
