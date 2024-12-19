<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('api', ['filter' => ['basicauth', 'apikey']], function($routes) {
  $routes->resource('mahasiswa', ['controller' => 'MahasiswaController']);
});
$routes->get('login', 'App\Controllers\ApiClientController::loginEndpoint');

$routes->get('mahasiswa', 'ApiClientController::getMahasiswaEndpoint');


// $routes->resource('mahasiswa', ['controller' => 'MahasiswaController']);

// $routes->get('mahasiswa', 'MahasiswaController::index');
// $routes->get('mahasiswa/(:num)', 'MahasiswaController::show/$1');
// $routes->post('mahasiswa', 'MahasiswaController::create');
// $routes->put('mahasiswa/(:num)', 'MahasiswaController::update/$1');
// $routes->delete('mahasiswa/(:num)', 'MahasiswaController::delete/$1');