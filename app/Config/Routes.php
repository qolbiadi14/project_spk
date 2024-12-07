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
