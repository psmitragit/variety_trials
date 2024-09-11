<?php

namespace App\Helpers;

use App\Controllers\BaseController;
use App\Models\Brand;
use App\Models\City;
use App\Models\Crop;
use App\Models\State;
use App\Models\CropVariable;

class Helpers extends BaseController
{
    /**
     * Get All States
     * @return array
     */
    public static function getStates()
    {
        $stateModel = new State();
        $states = $stateModel->where('status', 1);
        return $states->orderBy('code')->findAll();
    }

    /**
     * Get State
     * @return array
     */
    public static function getState($code)
    {
        $stateModel = new State();
        $states = $stateModel->where('status', 1);
        $states->where('code', $code);
        return $states->first();
    }

    /**
     * Get Cities By State code
     * @var $state_code
     * @return array
     */

    public static function getCities($stateCode = false)
    {
        $cityModel = new City();
        $cities = $cityModel->select('cities.*')->join('states', 'cities.state_id=states.id', 'left');
        $stateCode ? $cities->where(['states.code' => $stateCode, 'states.status' => 1]) : "";
        return $cities->where('cities.status', 1)->orderBy('cities.code')->findAll();
    }

    /**
     * Get All Brands
     * @return array
     */
    public static function getBrands()
    {
        $brandModel = new Brand();
        return $brandModel->where('status', 1)->findAll();
    }

    /**
     * Get Variables by Crop Id
     * @var $id
     * @return array
     */
    public static function getVariables($id)
    {
        $variableModel = new CropVariable();
        return $variableModel->where('crop_id', $id)->where('status', 1)->findAll();
    }

    /**
     * GET Crops
     * @return array
     */

    public static function getCrops()
    {
        $cropModel = new Crop();
        return $cropModel->where('status', 1)->findAll();
    }


    /**
     * Upload file
     * @return string
     */

    public static function uploadFile($file, $name = "", $des = '')
    {
        $tempName = $file['tmp_name'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = $name . '_' . time() . '.' . $ext;
        $path = 'uploads/' . $des . '/' . $name;
        $uploadPath = FCPATH . $path;

        move_uploaded_file($tempName, $uploadPath);
        return $path;
    }

    /**
     * Delete file from a specific location
     * @return bool
     */
    public static function deleteFile($path)
    {
        if (file_exists($path))
            unlink($path);
        return true;
    }

    /**
     * Get File Extension
     */

    public static function getFileExtension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * Send email
     */
    public static function sendEmail($to, $subject = false, $message = false, $attach = array())
    {
        $email = \Config\Services::email();
        $email->setTo($to);
        $subject ? $email->setSubject($subject) : "";
        $message ? $email->setMessage($message) : "";
        foreach ($attach as $l) {
            $email->attach($l);
        }
        $email->send();
    }
}
