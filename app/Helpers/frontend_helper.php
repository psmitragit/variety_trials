<?php

use App\Helpers\Helpers;
use App\Models\Crop;

/**
 * Get Authenticated admin
 * @return array
 */
function auth_admin()
{
    return session()->has('admin_data') && !empty(session('admin_data')) ? session('admin_data') : [];
}


function isAllowed()
{
    if (isset(auth_admin()['type']) && auth_admin()['type'] == 0) {
        return true;
    }
    return false;
}


/**
 * Get Authenticated User
 * @return array
 */
function auth_user()
{
    return session()->has('user_data') && !empty(session('user_data')) ? session('user_data') : [];
}

/**
 * Get all States
 * @return array
 */

function get_states($id = false)
{
    return Helpers::getStates($id);
}

/**
 * Get all crops
 * @return array
 */

function get_crops()
{
    return Helpers::getCrops();
}


/**
 * Get Locations by Trial
 * @return array
 */

function get_locations_by_trial($json, $array = false)
{
    return Helpers::getLocationsByTrial($json, $array);
}


/**
 * Get  crops by id string
 * @return array
 */

function get_crops_by_id_string($string, $column = false)
{
    $ids = explode(',', $string);

    $cropModel = new Crop();
    $crops = $cropModel->select('name')->whereIn('id', $ids)->find();
    $names = [];
    foreach ($crops as $l) {
        $names[] = $l['name'];
    }
    return implode(', ', array_filter($names));
}
