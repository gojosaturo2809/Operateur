<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::login');

// ══════════════════════════════════════════════════════════════════════════════
//  Authentification
// ══════════════════════════════════════════════════════════════════════════════

// Client
$routes->get( 'login',  'AuthController::login');
$routes->post('login',  'AuthController::login');

// Opérateur
$routes->get( 'operateur/login',  'AuthController::loginOperateur');
$routes->post('operateur/login',  'AuthController::loginOperateur');

// Déconnexion (client & opérateur)
$routes->get('logout', 'AuthController::logout');

// ══════════════════════════════════════════════════════════════════════════════
//  Espace Client
// ══════════════════════════════════════════════════════════════════════════════
$routes->group('client', function (RouteCollection $routes) {
    $routes->get( 'dashboard',       'ClientController::index');
    $routes->get( 'depot',           'ClientController::depot');
    $routes->post('store-depot',     'ClientController::storeDepot');
    $routes->get( 'retrait',         'ClientController::retrait');
    $routes->post('store-retrait',   'ClientController::storeRetrait');
    $routes->get( 'transfert',       'ClientController::transfert');
    $routes->post('store-transfert', 'ClientController::storeTransfert');
    $routes->get( 'historique',      'ClientController::historique');
});

// ══════════════════════════════════════════════════════════════════════════════
//  Espace Opérateur / Administration
// ══════════════════════════════════════════════════════════════════════════════

// Dashboard avec graphiques
$routes->get('operateur',           'DashboardAdminController::index');
$routes->get('operateur/dashboard', 'DashboardAdminController::index');

// Clients
$routes->get('operateur/clients',   'OperateurController::clients');

// Préfixes
$routes->get( 'operateur/prefixes',               'OperateurController::prefixes');
$routes->post('operateur/prefixes/add',           'OperateurController::ajouterPrefixe');
$routes->get( 'operateur/prefixes/delete/(:num)', 'OperateurController::supprimerPrefixe/$1');

// Types d'opération
$routes->get( 'operateur/types-operation',       'OperateurController::typesOperation');
$routes->post('operateur/types-operation/add',   'OperateurController::ajouterType');

// Barèmes de frais
$routes->get( 'operateur/baremes',               'OperateurController::baremes');
$routes->post('operateur/baremes/add',           'OperateurController::ajouterBareme');
$routes->get( 'operateur/baremes/delete/(:num)', 'OperateurController::supprimerBareme/$1');

// Situation des gains
$routes->get('operateur/gains',     'OperateurController::index');
