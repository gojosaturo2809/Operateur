<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Accueil / Redirection principale
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

// Déconnexion générale (client & opérateur)
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
$routes->group('operateur', function (RouteCollection $routes) {
    
    // Situation des gains (Dashboard principal opérateur)
    $routes->get('/',           'DashboardAdminController::index');
    $routes->get('dashboard',   'DashboardAdminController::index');
    $routes->get('gains',       'AdminOperateurController::index');

    // Liste des comptes clients
    $routes->get('clients',     'AdminOperateurController::clients');

    // CRUD : Préfixes autorisés
    $routes->get( 'prefixes',               'AdminOperateurController::prefixes');
    $routes->post('prefixes/store',         'AdminOperateurController::storePrefixe');
    $routes->get( 'prefixes/delete/(:num)', 'AdminOperateurController::deletePrefixe/$1');

    // CRUD : Types d'opération
    $routes->get( 'types-operation',               'AdminOperateurController::typesOperation');
    $routes->post('types-operation/store',         'AdminOperateurController::storeTypeOperation');
    $routes->get( 'types-operation/delete/(:num)', 'AdminOperateurController::deleteTypeOperation/$1');

    // CRUD : Barèmes de frais
    $routes->get( 'baremes',               'AdminOperateurController::baremes');
    $routes->post('baremes/store',         'AdminOperateurController::storeBareme');
    $routes->get( 'baremes/delete/(:num)', 'AdminOperateurController::deleteBareme/$1');
});
