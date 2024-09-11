<?php

namespace Config;

use App\Controllers\Auth\AuthController;
use CodeIgniter\Router\RouteCollection;


use App\Controllers\CropController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->post('get-trials', [CropController::class, 'ajaxLoad']);
$routes->get('(:segment)/documents', [CropController::class, 'downloads']);
$routes->post('get-documents', [CropController::class, 'getDocuments']);
$routes->post('get-downloads', [CropController::class, 'ajaxDownloadLoad']);
$routes->get('documents', [CropController::class, 'documents']);

$routes->match(['get', 'post'], 'forgot-password', [AuthController::class, 'forgot']);
$routes->match(['get', 'post'], 'forgot-password/(:any)', [AuthController::class, 'changePassword']);


//Admin Routes

require('RoutesAdmin.php');


$routes->get('(:segment)/trials', [CropController::class, 'index']);


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
