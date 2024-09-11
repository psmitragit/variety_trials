<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Crop;
use App\Models\State;
use App\Models\Treatment;
use App\Models\Trials;
use App\Models\TrialType;
use App\Models\Variety;
use League\Csv\Reader;

class TreatmentController extends BaseController
{
    private $model;
    public function __construct()
    {
        $this->model = new Treatment;
    }
    public function index()
    {
        $year = (isset($_GET['year']) && $_GET['year'] !== '') ? $_GET['year'] : null;
        $trial_type = (isset($_GET['trial_type']) && $_GET['trial_type'] !== '') ? $_GET['trial_type'] : null;
        $state = (isset($_GET['state']) && $_GET['state'] !== '') ? $_GET['state'] : null;

        $trialTypesModel = new TrialType();
        $statesModel = new State();

        $trials = $trialTypesModel->findAll();
        $states = $statesModel->findAll();

        $treatments = $this->model->select('treatments.*,crops.name as crop_name,varieties.code as variety_name,users.name as user_name, states.code as state_code, varieties.brand as variety_brand, varieties.short_name as variety_short_name, trial_types.name as trial_name')
            ->join('varieties', 'varieties.id=treatments.variety_id', 'left')
            ->join('crops', 'treatments.crop_id=crops.id', 'left')
            ->join('trial_types', 'treatments.trial_type_id=trial_types.id', 'left')
            ->join('users', 'users.id=treatments.user_id', 'left')
            ->join('states', 'states.id=treatments.state', 'left');
        if ($year) {
            $treatments->where('treatments.year', $year);
        }
        if ($state) {
            $treatments->where('treatments.state', $state);
        }
        if ($trial_type) {
            $treatments->where('treatments.trial_type_id', $trial_type);
        }
        // ->where('treatments.status', 1);

        if (!isAllowed()) {
            $treatments = $treatments->where('treatments.user_id', auth_admin()['id']);
        }

        $treatments = $treatments->orderBy('treatments.created_at', 'desc')->findAll();

        $varietyModel = new Variety();
        $allVarieties = $varietyModel->select('*')->where('status', 1)->findAll();

        return view('backend/treatment/index', compact('treatments', 'allVarieties', 'year', 'trial_type', 'state', 'trials', 'states'));
    }
    public function create($id = false)
    {
        if ($this->request->is('post')) {

            $validate = $this->validate([
                'id'        => 'required',
                'group'        => 'required',
                'crop_id'   => 'required|is_natural_no_zero',
                'trial_type_id'   => 'required|is_natural_no_zero',
                'state'   => 'required|is_natural_no_zero',
                'variety_id' => auth_admin()['type'] == '0' ? 'required|is_natural_no_zero' : 'permit_empty',
                'entry'     => auth_admin()['type'] == '0' ? 'if_exist|required|alpha_numeric|is_unique[treatments.name,id,{id}]' : 'permit_empty',
            ]);



            if (!$validate) return redirect()->back()->with('error', 'Please fill all required fields')->withInput();

            $data = [
                'name' => $this->request->getPost('entry') ?? null,
                'group' => $this->request->getPost('group'),
                'crop_id' => $this->request->getPost('crop_id'),
                'variety_id' => $this->request->getPost('variety_id'),
                'year' => $this->request->getPost('year'),
                'state' => $this->request->getPost('state'),
                'trial_type_id' => $this->request->getPost('trial_type_id'),
                'relative_maturity' => $this->request->getPost('relative_maturity'),
                'frogeye' => $this->request->getPost('frogeye'),
                'sds' => $this->request->getPost('sds'),
                'scn' => $this->request->getPost('scn'),
                'seed_treatment' => $this->request->getPost('seed_treatment'),
                'insecticide' => $this->request->getPost('insecticide'),
                'herbicide' => $this->request->getPost('herbicide'),
                'refuge' => $this->request->getPost('refuge'),
            ];

            if (!empty($id)) {
                if (auth_admin()['type'] == '0') {
                    $data['is_approved'] = $this->request->getPost('is_approved');
                    $data['approved_by'] = auth_admin()['id'];
                } else {
                    $data['is_approved'] = 0;
                    $data['approved_by'] = null;
                }
                $this->model->update($this->request->getPost('id'), $data);

                $message = "Treatment updated";
            } else {
                $data['user_id'] = auth_admin()['id'];
                if (auth_admin()['type'] == '0') {
                    $data['is_approved'] = 1;
                    $data['approved_by'] = auth_admin()['id'];
                }
                $this->model->insert($data);
                $message = "Treatment created";
            }
            return redirect('admin/treatment')->with('success', $message);
        } else {
            $treatment = !empty($id) ? $this->model->find($id) : false;

            $varietyModel = new Variety;
            $trialModel = new Trials();
            $trialTypesModel = new TrialType();
            $statesModel = new State();

            $allStates = $statesModel->findAll();
            $trialTypes = $trialTypesModel->where('status', 1)->findAll();
            $varieties = !empty($treatment) ? $varietyModel->where('crop_id', $treatment['crop_id'])->orderBy('name')->findAll() : array();
            $trials = !empty($treatment) ? $trialModel->where('crop_id', $treatment['crop_id'])->orderBy('name')->findAll() : array();

            return view('backend/treatment/create', compact('treatment', 'varieties', 'trials', 'trialTypes', 'allStates'));
        }
    }


    public function delete($id)
    {
        $treament = $this->model->find($id);
        if (!(auth_admin()['type'] == 0 || auth_admin()['id'] == $treament['user_id'])) {
            return redirect()->back()->with('error', 'You are not authorized for this');
        }
        $this->model->delete($id);
        return redirect()->back()->with('success', 'Treatment deleted');
    }


    public function approve()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $treatment = $this->model->find($id);
            $data['is_approved'] = !$treatment['is_approved'];
            $this->model->update($id, $data);
            return  response()->setJSON(['status' => true, 'is_approved' => $data['is_approved']]);
        }
    }


    public function report($id = false)
    {
        if ($id) {
            $treatments = $this->model->select('treatments.*,varieties.brand,varieties.short_name,varieties.additional_name,varieties.name as variety_name, states.code as state_name, trial_types.name as trial_name')
                ->join('varieties', 'varieties.id=treatments.variety_id')
                ->join('states', 'states.id=treatments.state')
                ->join('trial_types', 'trial_types.id=treatments.trial_type_id')
                ->where('treatments.status', 1)
                ->where('treatments.is_approved', 1)
                ->where('vt_treatments.name IS NOT NULL')
                ->where('treatments.crop_id', $id)
                ->findAll();
        } else {
            $treatments = array();
        }
        return view('backend/treatment/report', compact('treatments'));
    }

    public function addEntry()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $data = [
                'name' => $this->request->getPost('value'),
            ];
            $this->model->update($id, $data);
            return  response()->setJSON(['status' => true]);
        }
    }
    public function addVariety()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('treId');
            $variety_id = $this->request->getPost('variety_id');
            $varAndBrand = explode(' || ', (string)$this->request->getPost('varCode'));
            $varCode = $varAndBrand[0];
            $varBrand = $varAndBrand[1];
            $data = [
                'variety_id' => $variety_id,
            ];
            $this->model->update($id, $data);
            return  response()->setJSON(['status' => true, 'varCode' => $varCode, 'varBrand' => $varBrand, 'btnId' => $id]);
        }
    }

    public function search()
    {
        $entry = $this->request->getVar('entry') ?? '';

        $treatment = $this->model->select('treatments.*,varieties.brand,varieties.short_name,varieties.additional_name,varieties.name as variety_name,crops.name as crop')
            ->join('varieties', 'varieties.id=treatments.variety_id')
            ->join('crops', 'crops.id=treatments.crop_id')
            ->where('treatments.status', 1)
            ->where('treatments.is_approved', 1)
            ->where('vt_treatments.name', $entry)
            ->first();
        return view('backend/treatment/search', compact('treatment', 'entry'));
    }


    public function bulkInsert()
    {
        $validate = $this->validate(['bulk_file' => 'uploaded[bulk_file]|ext_in[bulk_file,csv,xlsx]']);
        if (!$validate) {
            return redirect()->back()->with('error', $this->validator->getError('bulk_file'));
        }

        $filePath = $_FILES['bulk_file']['tmp_name'];
        $csv = Reader::createFromPath($filePath);
        $expectedHeaders = ['Entry', 'VarietyID', 'Brand', 'Crop', 'Year', 'State', 'Trial', 'Herbicide Package', 'Insecticide Package', 'Relative Maturity', 'Frogeye', 'SDS', 'SCN', 'Refuge', 'Seed Treatment', 'UniqueId', 'Group'];
        $headers = $csv->getHeader();
        $records = $csv->getRecords();

        $i = 0;
        // $data = [];
        foreach ($records as $k => $record) {
            $data = [];
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

            $i++;

            $stateModel = new State();
            $cropModel = new Crop();
            $trialTypeModel = new TrialType();
            $varietyModel = new Variety();

            if (auth_admin()['type'] == '0') {
                $entry = $record[0];
                $varietyID = $record[1];
                $brand = $record[2];
                $crop = $record[3];
                $year = $record[4];
                $state = $record[5];
                $trial = $record[6];
                $herbicide = $record[7];
                $insecticide = $record[8];
                $relative_maturity = $record[9];
                $frogeye = $record[10];
                $sds = $record[11];
                $scn = $record[12];
                $refuge = $record[13];
                $seed_treatment = $record[14];
                $UniqueId = $record[15];
                $Group = $record[16];

                if ($entry == null || $varietyID == null) {
                    continue;
                }

                $treatment = null;
                if ($UniqueId) {
                    $treatment = $this->model->where('treatments.id', $UniqueId)->first();
                }

                $crop = $cropModel->where('crops.name', $crop);
                $crop = $crop->first();
                if (empty($crop)) continue;

                $state = $stateModel->where('states.code', $state);
                $state = $state->first();
                if (empty($state)) continue;

                $variety = $varietyModel->select('varieties.id')->where('varieties.code', $varietyID)->where('varieties.crop_id', $crop['id'])
                // ->join('users', 'users.id=varieties.user_id')
                ->first();
                if (empty($variety)) continue;

                $trial = $trialTypeModel->where(['trial_types.name' => $trial, 'trial_types.crop_id' => $crop['id'], 'trial_types.status' => 1]);
                $trial = $trial->first();
                if (empty($trial)) continue;

                $data = [
                    'name'          => $entry,
                    'group'          => $Group,
                    'crop_id'       => $crop['id'],
                    'trial_type_id' => $trial['id'],
                    'variety_id'    => $variety['id'],
                    'herbicide'     => $herbicide,
                    'insecticide'   => $insecticide,
                    'refuge'        => $refuge,
                    'seed_treatment' => $seed_treatment,
                    'year' => $year,
                    'state' => $state['id'],
                    'relative_maturity' => $relative_maturity,
                    'frogeye' => $frogeye,
                    'sds' => $sds,
                    'scn' => $scn,
                    'user_id'       => \auth_admin()['id'],
                    'is_approved' => 1,
                ];


            } else {
                $user_entered_varietyID = $record[1];
                $user_entered_brand = $record[2];
                $crop = $record[3];
                $year = $record[4];
                $state = $record[5];
                $trial = $record[6];
                $herbicide = $record[7];
                $insecticide = $record[8];
                $relative_maturity = $record[9];
                $frogeye = $record[10];
                $sds = $record[11];
                $scn = $record[12];
                $refuge = $record[13];
                $seed_treatment = $record[14];
                $Group = $record[16];

                if ($user_entered_varietyID == null || $user_entered_brand == null) {
                    continue;
                }

                // $treatment = $this->model->where('treatments.user_entered_variety', $user_entered_varietyID)->where('treatments.user_entered_brand', $user_entered_brand)->where('treatments.user_id', \auth_admin()['id'])->first();

                $treatment = null;

                // if ($UniqueId) {
                //     $treatment = $this->model->where('treatments.id', $UniqueId)->first();
                // }

                $crop = $cropModel->where('crops.name', $crop);
                $crop = $crop->first();
                if (empty($crop)) continue;

                $state = $stateModel->where('states.code', $state);
                $state = $state->first();
                if (empty($state)) continue;

                $trial = $trialTypeModel->where(['trial_types.name' => $trial, 'trial_types.crop_id' => $crop['id'], 'trial_types.status' => 1]);
                $trial = $trial->first();
                if (empty($trial)) continue;

                $data = [
                    'crop_id'       => $crop['id'],
                    'group'          => $Group,
                    'trial_type_id' => $trial['id'],
                    'herbicide'     => $herbicide,
                    'insecticide'   => $insecticide,
                    'refuge'        => $refuge,
                    'seed_treatment' => $seed_treatment,
                    'year' => $year,
                    'state' => $state['id'],
                    'relative_maturity' => $relative_maturity,
                    'frogeye' => $frogeye,
                    'sds' => $sds,
                    'scn' => $scn,
                    'user_id'       => \auth_admin()['id'],
                    'is_approved' => 0,
                    'user_entered_variety' => $user_entered_varietyID,
                    'user_entered_brand' => $user_entered_brand,
                ];
            }

            $treatmentId = false;
            if ($treatment) {
                $this->model->update($treatment['id'], $data);
                $treatmentId = $treatment['id'];
            } else {
                $treatmentId = $this->model->insert($data);
            }
        }
        // dd($data, $i);
        return \redirect()->back()->with('success', 'Data imported successfully');
    }

    public function exportCsv()
    {

        $year = (isset($_GET['year']) && $_GET['year'] !== '') ? $_GET['year'] : null;
        $trial_type = (isset($_GET['trial_type']) && $_GET['trial_type'] !== '') ? $_GET['trial_type'] : null;
        $state = (isset($_GET['state']) && $_GET['state'] !== '') ? $_GET['state'] : null;

        $treatments = $this->model->select('treatments.*,crops.name as crop_name,varieties.code as variety_name,users.name as user_name, states.code as state_code, varieties.brand as variety_brand, varieties.short_name as variety_short_name, trial_types.name as trial_name')
            ->join('varieties', 'varieties.id=treatments.variety_id', 'left')
            ->join('crops', 'treatments.crop_id=crops.id', 'left')
            ->join('trial_types', 'treatments.trial_type_id=trial_types.id', 'left')
            ->join('users', 'users.id=treatments.user_id', 'left')
            ->join('states', 'states.id=treatments.state', 'left');

        if ($year) {
            $treatments->where('treatments.year', $year);
        }
        if ($state) {
            $treatments->where('treatments.state', $state);
        }
        if ($trial_type) {
            $treatments->where('treatments.trial_type_id', $trial_type);
        }

        $data = $treatments->findAll();

        $columns = ['Entry', 'VarietyID', 'Brand', 'Crop', 'Year', 'State', 'Trial', 'Herbicide Package', 'Insecticide Package', 'Relative Maturity', 'Frogeye', 'SDS', 'SCN', 'Refuge', 'Seed Treatment', 'UniqueId', 'Group'];

        $modifiedData = [];
        foreach ($data as $row) {
            if ($row['variety_id'] == null) {
                $row['variety_id'] = $row['user_entered_variety'];
                $row['variety_brand'] = $row['user_entered_brand'];
            } else {
                $row['variety_id'] = $row['variety_name'];
            }
            $modifiedRow = [
                $row['name'],
                $row['variety_id'],
                $row['variety_brand'],
                $row['crop_name'],
                $row['year'],
                $row['state_code'],
                $row['trial_name'],
                $row['herbicide'],
                $row['insecticide'],
                $row['relative_maturity'],
                $row['frogeye'],
                $row['sds'],
                $row['scn'],
                $row['refuge'],
                $row['seed_treatment'],
                $row['id'],
                $row['group'],
            ];
            $modifiedData[] = $modifiedRow;
        }


        $csvFileName = 'export.csv';
        $csvFilePath = WRITEPATH . 'uploads/' . $csvFileName;

        $file = fopen($csvFilePath, 'w');
        fputcsv($file, $columns);
        foreach ($modifiedData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
        return $this->response->download($csvFilePath, null)->setFileName($csvFileName);
    }
}
