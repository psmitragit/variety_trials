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

function dd(...$params)
{
    echo '<pre class="pre-code">';
    if(count($params) > 0){
        foreach ($params as $index => $param) {
            print_r($param);
            echo '</pre>';
            echo '<br><br>';
            echo '<pre class="pre-code">';
        }
    }else{
        echo '~';
    }
    echo "</pre>";
    exit;
}

function getTraitColorByPercentile($val, $p10, $p30, $p70, $p90) {
    if ($val >= $p90) return '#008000';         // Best
    if ($val >= $p70) return '#008000';         // Above Average
    if ($val >= $p30) return '#ffff00';         // Mean Range
    if ($val >= $p10) return '#ff0000';         // Below Average
    return '#ff0000';                           // Worst
}
// function getTraitColorByPercentile($val, $p10, $p30, $p70, $p90) {
//     if ($val >= $p90) return '#44b4a6';         // Best
//     if ($val >= $p70) return '#6495d2';         // Above Average
//     if ($val >= $p30) return '#e8ebed';         // Mean Range
//     if ($val >= $p10) return '#f3c076';         // Below Average
//     return '#d26a5c';                           // Worst
// }

// Worst	Bottom 10%	#d26a5c
// Below Average	10% - 30%	#f3c076
// Mean Range	30% - 70%	#e8ebed
// Above Average	70% - 90%	#6495d2
// Best	Top 10%	#44b4a6

function getPercentile($sorted, $percent) {
    $index = ($percent / 100) * (count($sorted) - 1);
    $floor = floor($index);
    $ceil = ceil($index);
    if ($floor == $ceil) return $sorted[$floor];
    return $sorted[$floor] + ($sorted[$ceil] - $sorted[$floor]) * ($index - $floor);
}