<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Services;

// use CodeIgniter\Config\Services;

class HomeController extends BaseController
{
    public function index(): string
    {
        return view('frontend/index');
    }
}
