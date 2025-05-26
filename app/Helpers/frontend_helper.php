<?php

use App\Helpers\Helpers;
use App\Models\Crop;
use App\Models\User;

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

function isAllowedUserCrop($cropId = false)
{
    $user = auth_admin();
    if (isset($user['type']) && $user['type'] == 0) {
        return true;
    }
    //check if staff
    if (isset($user['type']) &&  $user['type'] == 1) {
        if (!$cropId) {
            return true;
        } else {
            $userModel = new User();
            $user = $userModel->select('crop')->where('id', $user['id'])->find();
            $user_allowed_crops = isset($user[0]['crop']) ? explode(',', $user[0]['crop']) : [];
            if (in_array(0, $user_allowed_crops) || in_array($cropId, $user_allowed_crops)) {
                return true;
            } else {
                return false;
            }
        }
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

function get_user_allowed_crops()
{
    return Helpers::getUserAllowedCrops();
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
    if (in_array(0, $ids)) {
        $names[] = 'All Crops';
    }
    foreach ($crops as $l) {
        $names[] = $l['name'];
    }
    return implode(', ', array_filter($names));
}

function dd($text = "~")
{
    echo '<pre class="pre-code">';
    if (is_array($text)) {
        foreach ($text as $key => $value) {
            print_r($value);
            echo '</pre>';
            echo '<br><br>';
            echo '<pre class="pre-code">';
        }
    } else {
        print_r($text);
    }
    exit();
}
