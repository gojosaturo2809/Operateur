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
    $routes->get( 'envoi-multiple', 'ClientController::envoiMultiple');
    $routes->post('storeEnvoiMultiple', 'ClientController::storeEnvoiMultiple');
    $routes->get( 'historique',      'ClientController::historique');
});

// ══════════════════════════════════════════════════════════════════════════════
//  Espace Opérateur / Administration
// ══════════════════════════════════════════════════════════════════════════════
$routes->group('operateur', function (RouteCollection $routes) {
    
    // Situation des gains (Dashboard principal opérateur)
    $routes->get('/',           'DashboardAdminController::index');
    $routes->get('dashboard',   'DashboardAdminController::index');
    $routes->get('gains',       'OperateurController::index');

    // Liste des comptes clients
    $routes->get('clients',     'OperateurController::clients');

    // CRUD : Préfixes autorisés
    $routes->get( 'prefixes',               'OperateurController::prefixes');
    $routes->post('prefixes/store',         'OperateurController::storePrefixe');
    $routes->get( 'prefixes/delete/(:num)', 'OperateurController::deletePrefixe/$1');

    // CRUD : Types d'opération
    $routes->get( 'types-operation',               'OperateurController::typesOperation');
    $routes->post('types-operation/store',         'OperateurController::storeTypeOperation');
    $routes->get( 'types-operation/delete/(:num)', 'OperateurController::deleteTypeOperation/$1');

    // CRUD : Barèmes de frais
    $routes->get( 'baremes',               'OperateurController::baremes');
    $routes->post('baremes/store',         'OperateurController::storeBareme');
    $routes->get( 'baremes/delete/(:num)', 'OperateurController::deleteBareme/$1');
});
