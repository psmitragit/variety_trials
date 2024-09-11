<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\Crop;
use App\Models\Location;
use App\Models\Treatment;
use App\Models\TrialLocation;
use App\Models\Trials;
use App\Models\TrialType;
use League\Csv\Reader;

class TrialController extends BaseController
{
    private $model, $treatmentModel;
    public function __construct()
    {
        $this->model  = new Trials();
        $this->treatmentModel = new Treatment();
    }
    public function index()
    {
        $trials = $this->model->select('trials.*,crops.name as crop_name,trial_types.name as trial_type,GROUP_CONCAT(vt_locations.location) as location_names')
            ->join('crops', 'crops.id=trials.crop_id')
            ->join('trial_types', 'trial_types.id=trials.trial_type_id')
            ->join('trial_location', 'trial_location.trial_id=trials.id', 'left')
            ->join('locations', 'locations.id=trial_location.location_id', 'left')
            ->groupBy('trials.id')
            ->where('crops.status', 1);
        !\isAllowed() ? $trials->where('trials.user_id', \auth_admin()['id']) : "";
        $trials = $trials->where('trials.status', 1)
            ->findAll();
        return view('backend/trials/index', \compact('trials'));
    }

    public function create($id = false)
    {
        if ($id) {
            $unauthorize = $this->isAuthorize($id);
            if ($unauthorize) return \redirect('admin/trials')->with('warning', 'Unauthorized access detected!');
        }
        $trialLocationModel = new TrialLocation;

        if ($this->request->is('post')) {
            $validate = $this->validate([
                'id' => 'required',
                'treatment_group' => 'required',
                'name' => 'required',
                'crop_id' => 'required|is_natural_no_zero',
                'trial_type' => 'required|is_natural_no_zero',
                'year' => 'required',
                'locids' => 'required'
            ]);
            if (!$validate) return redirect()->back()->with('error', 'Please fill all required fields')->withInput();

            $id = $this->request->getPost('id');
            $locationIds = $this->request->getPost('locids');
            $harvestDates = $this->request->getPost('harvest_date');
            $plantingDates = $this->request->getPost('planting_date');

            $locations = !empty($locationIds) ? array_values($locationIds) : [];

            // foreach ($locationIds as $k => $loc) {
            // }

            // \dd($locationIds);

            $data = [
                'name' => $this->request->getPost('name'),
                'treatment_group' => $this->request->getPost('treatment_group'),
                'crop_id' => $this->request->getPost('crop_id'),
                'user_id' => auth_admin()['id'],
                'trial_type_id' => $this->request->getPost('trial_type'),
                'year' => $this->request->getPost('year'),
                // 'planting_date' => $this->request->getPost('planting_date') ?? null,
                'locations' => json_encode($locations),
            ];


            if (!empty($id)) {
                $this->model->update($id, $data);
                $trialLocationModel->where('trial_id', $id)->delete();
                $message = "Trial updated";
            } else {
                $id = $this->model->insert($data);
                $message = "Trial Created";
            }

            foreach ($locationIds as $k => $lid) {
                $trialLocationModel->insert(['trial_id' => $id, 'location_id' => $lid, 'harvest_date' => $harvestDates[$k], 'planting_date' => $plantingDates[$k]]);
            }

            return redirect()->to(base_url('admin/trials'))->with('success', $message);
        } else {
            $locationModel = new Location();

            $trial = !empty($id) ? $this->model->find($id) : false;
            $types = !empty($trial) ? $this->getTrialTypeByCrop($trial['crop_id']) : array();
            $selected_tretment = !empty($trial) ? $trial['treatment_group'] : null;
            $locations = $locationModel->where('status', 1);
            !\isAllowed() ? $locations->where('user_id', \auth_admin()['id']) : "";
            $locations = $locations->orderBy('location', 'asc')->findAll();


            $trialLocations = $trialLocationModel->select('trial_location.*,locations.state_code')->where('trial_location.trial_id', $id)
                ->join('locations', 'locations.id=trial_location.location_id')
                ->findAll();

            $treatments = $this->treatmentModel->select('group')->where(['is_approved' => 1]);
            \auth_admin()['type'] == 0 ? '' : $treatments = $treatments->where(['user_id' => \auth_admin()['id']]);
            $treatments = $treatments->groupBy('group');
            $treatments = $treatments->find();

            return view('backend/trials/create', compact('locations', 'trial', 'types', 'trialLocations', 'treatments', 'selected_tretment'));
        }
    }


    public function delete($id)
    {
        $unauthorize = $this->isAuthorize($id);
        if ($unauthorize) return \redirect('admin/trials')->with('warning', 'Unauthorized access detected!');
        $trialLocationModel = new TrialLocation;
        $trialLocationModel->where('trial_id', $id)->delete();
        $this->model->delete($id);
        return redirect()->back()->with('success', "Trial Deleted");
    }


    public function types()
    {
        $typeModel = new TrialType();
        $types = $typeModel->select('trial_types.*,crops.name as crop_name,crops.id as crop_id')->join('crops', 'crops.id=trial_types.crop_id')->where('trial_types.status', 1)->find();
        return view('backend/trials/types', compact('types'));
    }

    public function createTypes()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'id' => 'required',
                'crop_id' => 'required|is_natural_no_zero',
                'name' => "required|is_unique[trial_types.name,id,{id}]",
            ]);

            if (!$validate) return redirect()->back()->with('error', 'Please fill all required fields')->withInput();

            $typeModel = new TrialType();
            $id = $this->request->getPost('id');
            $data = [
                'crop_id' => $this->request->getPost('crop_id'),
                'name' => $this->request->getPost('name')
            ];
            if ($id > 0) {
                $typeModel->update($id, $data);
                $message = "Trial type updated";
            } else {
                $typeModel->insert($data);
                $message = "Trial type created";
            }
            return redirect()->back()->with('success', $message);
        }
        return redirect()->back()->with('error', 'Unauthorised access detected!');
    }

    public function deleteTypes($id)
    {
        $typeModel = new TrialType();
        $typeModel->delete($id);
        return redirect()->back()->with('success', 'Trial Type deleted');
    }


    public function getTrialTypeByCrop($cropId = false)
    {
        $cropId = $this->request->isAJAX() ? $this->request->getPost('crop_id') : $cropId;
        $typeModel = new TrialType();
        $types = $typeModel->where('crop_id', $cropId)->orderBy('name', 'asc')->findAll();
        if ($this->request->isAJAX()) {
            return response()->setJSON(['status' => true, 'types' => $types]);
        } else {
            return $types;
        }
    }


    public function getSingle($id = false)
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $trial = $this->model->select('trials.*,trial_types.id as trial_type_id,trial_types.name as trial_type')->join('trial_types', 'trials.trial_type_id=trial_types.id')->find($id);
            $locations = !empty($trial['locations']) ? Helpers::getLocationsByTrial($trial['locations']) : [];
            return response()->setJSON(['status' => true, 'trial' => $trial, 'locations' => $locations]);
        } else {
        }
    }

    public function isAuthorize($id)
    {
        $trial = $this->model->find($id);
        $unauthorize = \false;

        if (empty($trial)) {
            $unauthorize = true;
        } else if (!isAllowed()) {
            $unauthorize = \auth_admin()['id'] != $trial['user_id'] ? true : $unauthorize;
        }
        return $unauthorize;
    }


    public function bulkInsert()
    {
        $trialTypeModel = new TrialType();
        $cropModel = new Crop();
        $locationModel = new Location();


        $validate = $this->validate(['bulk_file' => 'uploaded[bulk_file]|ext_in[bulk_file,csv,xlsx]']);
        if (!$validate) {
            return redirect()->back()->with('error', $this->validator->getError('bulk_file'));
        }

        $filePath = $_FILES['bulk_file']['tmp_name'];
        $csv = Reader::createFromPath($filePath);
        $expectedHeaders = ['trial name', 'crop', 'trial type', 'year', 'treatment_group', 'location_code', 'harvest date', 'planting date'];
        $headers = $csv->getHeader();
        $records = $csv->getRecords();

        foreach ($records as $k => $record) {
            if (empty($headers) && $k == 0) {
                $headers = $record;
                if ($expectedHeaders != $headers) {
                    return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
                }
                continue;
            } else {
                if ($expectedHeaders != $headers) {
                    return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
                }
            }

            $trialName = trim($record[0]);
            $year = trim($record[3]);

            $crop = trim($record[1]);
            $crop = $cropModel->where('name', $crop)->first();
            if (!$crop) continue;
            $trialType = trim($record[2]);
            $trialType = $trialTypeModel->where('name', $trialType)->first();
            if (!$trialType) continue;

            $treatment_group = trim($record[4]);

            $location = trim($record[5]);
            $location = $locationModel->where('code', $location)->first();
            if (!$location) continue;

            $trial = $this->model->where('name', $trialName)
                ->where('crop_id', $crop['id'])
                ->where('trial_type_id', $trialType['id'])
                ->where('year', $year)
                ->where('user_id', \auth_admin()['id'])
                ->find();


            $data = [
                'name' => $trialName,
                'treatment_group' => $treatment_group,
                'trial_type_id' => $trialType['id'],
                'year' => $year,
                'crop_id' => $crop['id'],
                'user_id' => \auth_admin()['id']
            ];

            if ($trial) {
                $locations = [];
                $trial = $trial[0];
                $this->addTrialLocation($trial['id'], $location['id'], trim($record[7]), trim($record[6]));
                $locations = json_decode($trial['locations']);
                array_push($locations, $location['id']);
                $locations = array_unique($locations);
                $this->model->update($trial['id'], ['locations' => json_encode($locations)]);
            } else {
                $data['locations'] = json_encode([$location['id']]);
                $trialId = $this->model->insert($data);
                $this->addTrialLocation($trialId, $location['id'], trim($record[7]), trim($record[6]));
            }
        }
        return \redirect()->back()->with('success', 'Data imported successfully');
    }

    public function addTrialLocation($trialId, $location_id, $planting_date, $harvest_date)
    {
        $trialLocationModel = new TrialLocation();

        if ($trialLocationModel->where(['trial_id' => $trialId, 'location_id'=> $location_id])->first()) {
            $trialLocationModel->where(['trial_id' => $trialId, 'location_id' => $location_id])->update('null', ['planting_date' => date('Y-m-d', strtotime($planting_date)), 'harvest_date' => date('Y-m-d', strtotime($harvest_date))]);
        } else {
            $trialLocationModel->insert(['trial_id' => $trialId, 'location_id' => $location_id, 'planting_date' => date('Y-m-d', strtotime($planting_date)), 'harvest_date' => date('Y-m-d', strtotime($harvest_date))]);
        }
    }
}
