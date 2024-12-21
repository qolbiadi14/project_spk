<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'IndexController::index');

$routes->get('/kriteria', 'KriteriaController::index');
$routes->get('/tambah-kriteria', 'KriteriaController::viewTambah');
$routes->post('/kriteria/add', 'KriteriaController::add');
$routes->get('/kriteria/delete/(:num)', 'KriteriaController::delete/$1');
$routes->get('/kriteria/edit/(:num)', 'KriteriaController::edit/$1');
$routes->post('/kriteria/update/(:num)', 'KriteriaController::update/$1');

$routes->get('/perbandingan-kriteria', 'PerbandinganKriteriaController::index');
$routes->post('/perbandingan-kriteria/add', 'PerbandinganKriteriaController::add');

$routes->get('/alternatif', 'AlternatifController::index');
$routes->get('/tambah-alternatif', 'AlternatifController::viewTambah');
$routes->post('/alternatif/add', 'AlternatifController::add');
$routes->get('/alternatif/delete/(:num)', 'AlternatifController::delete/$1');
$routes->get('/alternatif/edit/(:num)', 'AlternatifController::edit/$1');
$routes->post('/alternatif/update/(:num)', 'AlternatifController::update/$1');

$routes->get('/perangkingan-alternatif', 'PerangkinganAlternatifController::index');
$routes->post('/perangkingan-alternatif/store', 'PerangkinganAlternatifController::store');
$routes->get('/perangkingan-alternatif/normalisasi', 'PerangkinganAlternatifController::normalisasi');
