<?php

namespace Config;

use App\Controllers\Admin\UploadController;
use App\Controllers\Admin\UserController;
use App\Controllers\Auth\AuthController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->match(['get', 'post'], 'admin/login', 'Auth\AuthController::adminLogin');
$routes->get('admin/logout', 'Auth\AuthController::adminSignOut', ['filter' => 'islogged']);

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'islogged'], function ($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->match(['get', 'post'], 'change-password', [UserController::class, 'changePassword']);

    $routes->group('crop', function ($routes) {
        $routes->get('/', 'CropController::index');
        // $routes->post('bulk', 'CropController::bulkInsert');
        $routes->match(['get', 'post'], 'create', 'CropController::create', ['filter' => 'isAllowed']);
        $routes->match(['get', 'post'], '(:num)/edit', 'CropController::edit/$1', ['filter' => 'isAllowed']);
        $routes->get('(:num)/delete', 'CropController::delete/$1', ['filter' => 'isAllowed']);
        $routes->get('(:num/trials)', 'CropController::cropDetails/$1', ['filter' => 'isAllowed']);
        $routes->post('get_variables', 'CropController::variables');
    });

    $routes->group('location', function ($routes) {
        $routes->get('/', 'LocationController::index');
        $routes->post('bulk', 'LocationController::bulkInsert', ['filter' => 'isAllowed']);
        $routes->post('get-city', 'LocationController::getCity');
        $routes->match(['get', 'post'], 'create', 'LocationController::create', ['filter' => 'isAllowed']);
        $routes->match(['get', 'post'], '(:num)/edit', 'LocationController::edit/$1', ['filter' => 'isAllowed']);
        $routes->get('(:num)/delete', 'LocationController::delete/$1', ['filter' => 'isAllowed']);
        $routes->post('get_single', 'LocationController::getSingle');
    });

    $routes->group('variety', function ($routes) {
        $routes->get('/', 'VarietyController::index');
        $routes->post('bulk', 'VarietyController::bulkInsert', ['filter' => 'isAllowed']);
        $routes->match(['get', 'post'], 'create', 'VarietyController::create', ['filter' => 'isAllowed']);
        $routes->match(['get', 'post'], '(:num)/edit', 'VarietyController::edit/$1', ['filter' => 'isAllowed']);
        $routes->get('(:num)/delete', 'VarietyController::delete/$1', ['filter' => 'isAllowed']);
        $routes->post('bulk-delete', 'VarietyController::bulkDelete', ['filter' => 'isAllowed']);
        $routes->post('get_single', 'VarietyController::getSingle');
    });


    $routes->group('trials', function ($routes) {
        $routes->get('/', 'TrialController::index');
        $routes->post('/', 'TrialController::ajaxLoad');
        $routes->post('bulk', 'TrialController::bulkInsert');
        $routes->match(['get', 'post'], 'create', 'TrialController::create');
        $routes->match(['get', 'post'], '(:num)/edit', 'TrialController::edit/$1');
        $routes->get('(:num)/delete', 'TrialController::delete/$1');
        $routes->post('copy', 'TrialController::copy', ['filter' => 'isAllowed']);
        $routes->post('export/(:any)', 'TrialController::export/$1');
        $routes->post('approve', 'TrialController::approve', ['filter' => 'isAllowed']);
        $routes->post('bulk-approve', 'TrialController::bulkApprove', ['filter' => 'isAllowed']);
        $routes->post('bulk-delete', 'TrialController::bulkDelete', ['filter' => 'isAllowed']);
    });

    $routes->group('uploads', ['filter' => 'isAllowed'], function ($routes) {
        $routes->get('/', [UploadController::class, 'index']);
        $routes->post('create', [UploadController::class, 'store']);
        $routes->get('(:num)/delete', [UploadController::class, 'delete/$1']);
    });

    $routes->group('user', ['filter' => 'isAllowed'], function ($routes) {
        $routes->get('/', [UserController::class, 'index']);
        $routes->match(['get', 'post'], 'create', [UserController::class, 'create']);
        $routes->match(['get', 'post'], '(:num)/edit', [UserController::class, 'edit/$1']);
        $routes->get('(:num)/delete', [UserController::class, 'destroy/$1']);
    });
});
