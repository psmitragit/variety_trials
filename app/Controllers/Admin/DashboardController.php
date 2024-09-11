<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Location;
use App\Models\Trials;
use App\Models\Variety;

class DashboardController extends BaseController
{
    public function index()
    {
        $locationModel = new Location();
        $varietyModel = new Variety();
        $trialModel = new Trials();
        $totalLocation = $locationModel->countAllResults();
        $totalVariety = $varietyModel->countAllResults();
        $totalTrial = $trialModel->countAllResults();
        return view('backend/dashboard', \compact('totalLocation', 'totalVariety', 'totalTrial'));
    }
}
