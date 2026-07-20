<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Articles::index');

// Authentification
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Articles
$routes->get('articles', 'Articles::index');
$routes->get('articles/create', 'Articles::create');
$routes->post('articles/store', 'Articles::store');
$routes->get('articles/delete/(:num)', 'Articles::delete/$1');

// Administration
$routes->get('admin', 'Admin::dashboard');
$routes->get('admin/delete/(:num)', 'Admin::deleteUser/$1');
