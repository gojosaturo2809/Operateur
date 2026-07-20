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

