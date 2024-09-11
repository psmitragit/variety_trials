<?php

use App\Helpers\Helpers;

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
 * Get Authenticated admin
 * @return array
 */
function auth_user()
{
    return session()->has('user_data') && !empty(session('user_data')) ? session('user_data') : [];
}

/**
 * Get all crops
 * @return array
 */

function get_crops()
{
    return Helpers::getCrops();
}
