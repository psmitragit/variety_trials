<?php

namespace Config;

use App\Controllers\Admin\CityController;
use App\Controllers\Admin\Reports\TrialReportController;
use App\Controllers\Admin\TreatmentController;
use App\Controllers\Admin\UploadController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\EmailTemplateController;
use App\Controllers\Admin\StateController;
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
        $routes->match(['get', 'post'], 'create', 'LocationController::create');
        $routes->match(['get', 'post'], '(:num)/edit', 'LocationController::edit/$1');
        $routes->get('(:num)/delete', 'LocationController::delete/$1');
        $routes->post('get_single', 'LocationController::getSingle');
        $routes->post('get_location_by_state', 'LocationController::getLocationsByState');
        $routes->get('states', [StateController::class, 'index']);
        $routes->post('states/save', [StateController::class, 'save']);
        $routes->get('states/(:num)/delete', [StateController::class, 'destroy']);
        $routes->get('cities', [CityController::class, 'index']);
        $routes->post('cities/save', [CityController::class, 'save']);
        $routes->get('cities/(:num)/delete', [CityController::class, 'destroy']);
    });

    $routes->group('variety', function ($routes) {
        $routes->get('/', 'VarietyController::index');
        $routes->post('bulk', 'VarietyController::bulkInsert');
        $routes->match(['get', 'post'], 'create', 'VarietyController::create');
        $routes->match(['get', 'post'], '(:num)/edit', 'VarietyController::edit/$1', ['filter' => 'isAllowed']);
        $routes->get('(:num)/delete', 'VarietyController::delete/$1', ['filter' => 'isAllowed']);
        $routes->post('bulk-delete', 'VarietyController::bulkDelete', ['filter' => 'isAllowed']);
        $routes->post('get_single', 'VarietyController::getSingle');
        $routes->get('brand', "VarietyController::manageBrand");
        $routes->post('create-brand', "VarietyController::createBrand");
        $routes->get('brand/(:num)/delete', "VarietyController::deleteBrand/$1");
        $routes->post('get_variety_by_crop', "VarietyController::getVarietiesByCrop");
        $routes->post('approve', 'VarietyController::approve');
        $routes->post('add_entry', 'VarietyController::addEntry');
    });

    $routes->group('treatment', function ($routes) {
        $routes->get('/', 'TreatmentController::index');
        $routes->post('add-variety-id', 'TreatmentController::addVariety');
        $routes->post('bulk', 'TreatmentController::bulkInsert');
        $routes->match(['get', 'post'], 'create', 'TreatmentController::create');
        $routes->match(['get', 'post'], '(:num)/edit', 'TreatmentController::create/$1');
        $routes->get('(:num)/delete', 'TreatmentController::delete/$1', ['filter' => 'isAllowed']);
        $routes->post('bulk-delete', 'TreatmentController::bulkDelete', ['filter' => 'isAllowed']);
        $routes->post('get_single', 'TreatmentController::getSingle');
        $routes->post('approve', 'TreatmentController::approve');
        $routes->post('add_entry', 'TreatmentController::addEntry');
        $routes->get('search', 'TreatmentController::search');
        $routes->get('export', 'TreatmentController::exportCsv');
    });


    $routes->group('trials', function ($routes) {
        $routes->get('/', 'TrialController::index');
        $routes->match(['get', 'post'], 'create', 'TrialController::create');
        $routes->post('bulk', 'TrialController::bulkInsert');
        $routes->match(['get', 'post'], '(:num)/edit', 'TrialController::create/$1');
        $routes->get('(:num)/delete', 'TrialController::delete/$1');
        $routes->get('type', 'TrialController::types');
        $routes->post('create-type', 'TrialController::createTypes', ['filter' => 'isAllowed']);
        $routes->get('type/(:num)/delete', 'TrialController::deleteTypes/$1', ['filter' => 'isAllowed']);
        $routes->post('get_trial_type_by_crop', 'TrialController::getTrialTypeByCrop');
        $routes->post('get_single', 'TrialController::getSingle');
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

    $routes->group('email-template', ['filter' => 'isAllowed'], function ($routes) {
        $routes->get('/', [EmailTemplateController::class, 'index']);
        $routes->match(['get', 'post'], 'create', [EmailTemplateController::class, 'create']);
        $routes->match(['get', 'post'], '(:num)/edit', [EmailTemplateController::class, 'create/$1']);
        $routes->get('(:num)/delete', [EmailTemplateController::class, 'destroy/$1']);
    });



    //Reports
    $routes->group('report', function ($routes) {
        $routes->group('trials', function ($routes) {
            $routes->get('/', [TrialReportController::class, 'index']);
            $routes->post('/', [TrialReportController::class, 'ajaxLoad']);
            $routes->post('bulk', [TrialReportController::class, 'bulkInsert']);
            $routes->match(['get', 'post'], 'create', [TrialReportController::class, 'create']);
            $routes->match(['get', 'post'], '(:num)/edit', [TrialReportController::class, 'edit/$1']);
            $routes->get('(:num)/delete', [TrialReportController::class, 'delete/$1']);
            $routes->post('copy', [TrialReportController::class, 'copy'], ['filter' => 'isAllowed']);
            $routes->post('export/(:any)', [TrialReportController::class, 'export/$1']);
            $routes->post('approve', [TrialReportController::class, 'approve'], ['filter' => 'isAllowed']);
            $routes->post('bulk-approve', [TrialReportController::class, 'bulkApprove'], ['filter' => 'isAllowed']);
            $routes->post('bulk-delete', [TrialReportController::class, 'bulkDelete'], ['filter' => 'isAllowed']);
            $routes->post('format-download', [TrialReportController::class, 'formatDownload']);
            $routes->post('import-process', [TrialReportController::class, 'importProcess']);
        });

        $routes->get('treatment(:any)', [TreatmentController::class, 'report$1']);
    });
});
