<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ── Opérateur / Admin ──────────────────────────────────────────────────────

// Dashboard admin (tableau de bord avec graphiques)
$routes->get('operateur/dashboard', 'DashboardAdminController::index');

// Redirection racine opérateur vers le dashboard
$routes->get('/operateur', 'DashboardAdminController::index');


$routes->get('operateur/gains',          'OperateurController::index');
$routes->get('operateur/clients',        'OperateurController::clients');

// Par défaut : redirection ou affichage de la page de connexion
$routes->get('/', 'AuthController::login');

// --- Authentification ---
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// --- Côté Client (Espace Utilisateur) ---
$routes->group('client', function (RouteCollection $routes) {
    // Voir le solde (Page d'accueil du client)
    $routes->get('dashboard', 'ClientController::index');
    
    // Dépôt
    $routes->get('depot', 'ClientController::depot');
    $routes->post('store-depot', 'ClientController::storeDepot');
    
    // Retrait
    $routes->get('retrait', 'ClientController::retrait');
    $routes->post('store-retrait', 'ClientController::storeRetrait');
    
    // Transfert
    $routes->get('transfert', 'ClientController::transfert');
    $routes->post('store-transfert', 'ClientController::storeTransfert');
    
    // Historique des opérations
    $routes->get('historique', 'ClientController::historique');
});

// --- Côté Opérateur (Administration) ---
$routes->group('operator', function (RouteCollection $routes) {
    // Situation des gains (via les différents frais)
    $routes->get('dashboard', 'OperatorController::dashboard');
    
    // Situation des comptes clients
    $routes->get('clients', 'OperatorController::clients');
});
