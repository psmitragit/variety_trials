<?php

namespace App\Helpers;

use App\Controllers\BaseController;
use App\Models\Brand;
use App\Models\City;
use App\Models\Crop;
use App\Models\Location;
use App\Models\State;
use App\Models\CropVariable;
use App\Models\EmailTemplate;
use App\Models\Trials;
use App\Models\User;
use App\Models\Variety;

class Helpers extends BaseController
{

    public function varietiesByUserAndCrop($user_id, $crop_id, $getOnlyIds = false)
    {
        $varietyModel = new Variety();
        $userModel = new User();
        $user = $userModel->find($user_id);
        $userType = (int)$user['type'];

        $varietyModel->select('*');
        if ($userType !== 0) {
            $varietyModel->where('user_id', $user_id);
        }
        $varietyModel->where('crop_id', $crop_id);
        $varietyModel->where('status', 1);
        $varieties = $varietyModel->findAll();
        if (!$getOnlyIds) {
            return (!empty($varieties)) ? $varieties : false;
        } else {
            $ids = [];
            if (!empty($varieties)) {
                foreach ($varieties as $variety) {
                    $ids[] = (int)$variety['id'];
                }
            }
            return $ids;
        }
    }
    /**
     * Get All States
     * @return array
     */
    public static function getStates($stateId = 0)
    {
        $stateModel = new State();
        $states = $stateModel->where('status', 1);
        $stateId ? $stateModel->where('id', $stateId) : '';
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
        $admin = \auth_admin() ?? [];
        $cropModel = new Crop();
        $crops = $cropModel->where('status', 1);
        !empty($admin['type']) && $admin['type'] == 1 && !empty($admin['crop']) ? $crops->whereIn('crops.id', \explode(',', $admin['crop'])) : "";
        return $crops->findAll();
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
    public static function sendEmail($to, $templateId = false, $values = [], $attach = array())
    {
        $prepared = self::prepareTemplate($templateId, $values);
        $email = \Config\Services::email();
        $email->setTo($to);
        !empty($prepared['subject']) ? $email->setSubject($prepared['subject']) : "";
        !empty($prepared['html']) ? $email->setMessage($prepared['html']) : "";
        foreach ($attach as $l) {
            $email->attach($l);
        }
        $email->send();
    }


    /**
     * Prepare Email template
     * @return array
     */

    public static function prepareTemplate($templateId, $values)
    {
        $templateModel = new EmailTemplate();
        $template = $templateModel->find($templateId);
        $html = '';
        if (!empty($template['content'])) {
            $placeholder = !empty($template['placeholder']) ? \json_decode($template['placeholder'], true) : [];
            $html = \str_replace($placeholder, $values, $template['content']);
        }
        return array('subject' => $template['subject'] ?? "", 'html' => $html);
    }


    /**
     * Get Locations by Trial
     * @return array
     */

    public static function getLocationsByTrial($json)
    {
        $ids = json_decode($json, true);
        $locationModel = new Location;
        return $locationModel->whereIn('id', $ids)->findAll();
    }

    /**
     * Get Varieties By Crop
     * @return array
     */
    public static function getVarieties($cropId)
    {

        $varityModel = new Variety;
        return $varityModel->where('crop_id', $cropId)->where('status', 1)->orderBy('name', 'asc')->findAll();
    }

    /**
     * Get Trials By Crop
     * @return array
     */
    public static function getTrials($cropId)
    {
        $trialModel = new Trials;
        return $trialModel->where('crop_id', $cropId)->where('status', 1)->orderBy('name', 'asc')->findAll();
    }
}
