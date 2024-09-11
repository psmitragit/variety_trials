<?php

namespace App\Controllers\Admin\Reports;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\Crop;
use App\Models\CropVariable;
use App\Models\Location;
use App\Models\Treatment;
use App\Models\TrialData;
use App\Models\TrialLocation;
use App\Models\Trials;
use App\Models\TrialType;
use App\Models\Variety;
use League\Csv\Reader;
use League\Csv\Writer;
use SplTempFileObject;

class TrialReportController extends BaseController
{
    private $model;
    public function __construct()
    {
        $this->model  = new TrialData();
    }
    public function index()
    {
        $crops = Helpers::getCrops();
        return view('backend/report/trials/index', \compact('crops'));
    }

    public function ajaxLoad()
    {
        if ($this->request->isAJAX()) {
            $userId = \auth_admin()['id'];
            $type  = \auth_admin()['type'];
            $cropId = $this->request->getPost('id');
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start');
            $length = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'] ?? "";
            $order = $this->request->getPost('order')[0] ?? false;
            $status = $this->request->getPost('status') ?? -1;

            $columns = ['id', 'crops.name', 'trial_data.year', 'trial_data.program', 'trial_data.trial', 'trial_data.state_code', 'trial_data.location', 'trial_data.variety_code', 'trial_types.name', 'varieties.brand', 'trial_data.is_approved'];

            $trial = $this->model->select('trial_data.*,varieties.brand as brand,crops.name as crop,trial_types.name as trial_type_name,vt_trials.treatment_group as treatment_group,vt_trials.name as trial_id');
            $trial->join('vt_trials', 'trial_data.program=vt_trials.id', 'left');
            $trial->join('crops', 'trial_data.crop_id=crops.id');
            $trial->join('trial_types', 'trial_data.trial=trial_types.id');
            $type > 0 ? $trial->where('trial_data.user_id', $userId) : "";
            $trial->join('varieties', 'trial_data.variety_code=varieties.code', 'left');
            !empty($cropId) ? $trial->where('trial_data.crop_id', $cropId) : "";
            $status >= 0 ? $trial->where('trial_data.is_approved', $status) : "";
            if ($search) {
                $trial->groupStart();
                $trial->where('crops.name like ', '%' . $search . '%');
                $trial->orWhere('trial_data.year like ', '%' . $search . '%');
                $trial->orWhere('trial_data.state_code like ', '%' . $search . '%');
                // $trial->orWhere('trial_data.program like ', '%' . $search . '%');
                $trial->orWhere('trial_types.name like ', '%' . $search . '%');
                $trial->orWhere('trial_data.location like ', '%' . $search . '%');
                $trial->orWhere('trial_data.variety_code like ', '%' . $search . '%');
                $trial->orWhere('varieties.brand like ', '%' . $search . '%');
                $trial->groupEnd();
            }

            if ($order) {
                $trial->orderBy($columns[$order['column'] ?? 0], $order['dir'] ?? 'asc');
            } else {
                $trial->orderBy('trial_data.year', 'DESC');
            }
            $totalEntry = $trial->countAllResults(false);
            $trials = $trial->limit($length, $start)->find();

            $data = array();

            $treatmentModel  = new Treatment();

            $k = 0;

            foreach ($trials as $l) {

                $treatment_group = $l['treatment_group'];

                $treatments = $treatmentModel->where('group', $treatment_group)->findAll();

                if (!empty($treatments)) {
                    foreach ($treatments as $treatment) {
                        $data[$k]['ids'] = '<input type="checkbox" name="ids[]" class="selectId" value="' . $l['id'] . '">';
                        $data[$k]['crop'] = $l['crop'];
                        $data[$k]['treatment_group'] = $treatment_group;
                        $data[$k]['treatment'] = $treatment['name'];
                        $data[$k]['year'] = $l['year'];
                        $data[$k]['trial_id'] = $l['trial_id'];
                        $data[$k]['trial_type'] = $l['trial_type_name'];
                        $data[$k]['state'] = $l['state_code'];
                        $data[$k]['location'] = $l['location'];
                        $data[$k]['variety'] = $l['variety_code'];
                        $data[$k]['brand'] = $l['brand'];
                        $data[$k]['status'] = $l['is_approved'] ? '<span class="badge badge-success approve_trial" data-bs-toggle="tooltip" data-id="' . $l['id'] . '" role="button">Approved</span>' : '<span class="badge badge-danger approve_trial" data-id="' . $l['id'] . '" role="button">Unapproved</span>';
                        $action = '<a class="text-decoration-none text-warning" title="Edit" href="' . base_url('admin/report/trials/' . $l['id'] . '/edit') . '"><i class="ti ti-pencil-alt"></i></a>&ensp;
                                                <a class="text-decoration-none text-danger confirmDelete" data-bs-toggle="tooltip" title="Delete" href="javascript:void(0)" data-href="' . base_url('admin/report/trials/' . $l['id'] . '/delete') . '"><i class="ti ti-trash"></i></a>';
                        $data[$k]['action'] = $action;
                        $k++;
                    }
                }
            }

            $resData = [
                'draw' => \intval($draw),
                'data' => $data,
                'recordsTotal' => $totalEntry,
                'recordsFiltered' => $totalEntry,
                'hash' => \csrf_hash()
            ];
            return \response()->setJSON($resData);
        }
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'crop_id' => 'required',
                'year' => 'required|is_natural_no_zero|exact_length[4]',
                'trial' => 'required',
                'program' => 'required',
                'locid' => 'required',
                'variety' => 'required',
                // 'entry' => 'required',
            ]);
            if (!$validate)
                return \redirect()->back()->withInput();


            $variables = array();
            foreach ($this->request->getPost('variable') as $k => $v) {
                $variables[$k] = $v;
            }
            $data = [
                'year' => $this->request->getPost('year'),
                'trial' => $this->request->getPost('trial'),
                'crop_id' => $this->request->getPost('crop_id'),
                'user_id' => auth_admin()['id'],
                'program' => $this->request->getPost('program'),
                'location_code' => $this->request->getPost('locid'),
                'state_code' => $this->request->getPost('state'),
                'location' => $this->request->getPost('location'),
                'variety_code' => $this->request->getPost('variety'),
                'variable' => \json_encode($variables),
                'is_approved' => auth_admin()['type'] == 0 ? 1 : 0,
                // 'entry' => $this->request->getPost('entry'),
            ];

            $this->model->insert($data);
            return \redirect()->back()->with('success', "Trial added successfully");
        } else {
            $trials = array();
            $trial = array();
            $locations = array();
            $varieties = array();
            $variables = array();
            $treatments = array();
            return view('backend/report/trials/create', compact('locations', 'varieties', 'variables', 'trials', 'trial', 'treatments'));
        }
    }
    public function edit($id)
    {
        $trialData = $this->model->select('trial_data.*,trial_types.name as trial_type_name')
            ->join('trial_types', 'trial_data.trial=trial_types.id', 'left')->find($id);
        if (!$trialData) return \redirect()->back('error', 'Unauthorized access!');
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'crop_id' => 'required',
                'year' => 'required|is_natural_no_zero|exact_length[4]',
                'trial' => 'required',
                'program' => 'required',
                'locid' => 'required',
                'variety' => 'required',
                // 'entry' => 'required',
            ]);
            if (!$validate)
                return \redirect()->back()->withInput();


            $variables = array();
            foreach ($this->request->getPost('variable') as $k => $v) {
                $variables[$k] = $v;
            }
            $data = [
                'year' => $this->request->getPost('year'),
                'trial' => $this->request->getPost('trial'),
                'crop_id' => $this->request->getPost('crop_id'),
                'program' => $this->request->getPost('program'),
                'location_code' => $this->request->getPost('locid'),
                'state_code' => $this->request->getPost('state'),
                'location' => $this->request->getPost('location'),
                'variety_code' => $this->request->getPost('variety'),
                'variable' => \json_encode($variables),
                'is_approved' => $this->request->getVar('is_approved'),
                // 'entry' => $this->request->getPost('entry'),
            ];

            $this->model->update($trialData['id'], $data);
            return \redirect()->back()->with('success', "Trial updated successfully");
        } else {
            $trialsModel = new Trials;
            $trials = Helpers::getTrials($trialData['crop_id']);
            $trial = $trialsModel->where('crop_id', $trialData['crop_id'])
                ->where('year', $trialData['year'])
                ->where('trial_type_id', $trialData['trial'])
                ->first();

            $locations = Helpers::getLocationsByTrial($trial['locations']);
            $varieties = Helpers::getVarieties($trialData['crop_id']);
            $locationModel = new Location();
            $location = $locationModel->where('code', $trialData['location_code'])->first();
            $varietyModel = new Variety();
            $variety = $varietyModel->where('code', $trialData['variety_code'])->first();

            $variableModel = new CropVariable();
            $varSaved = $variableModel->where('crop_id', $trialData['crop_id'])->findAll();
            $varVal = \json_decode($trialData['variable']);
            $variables = array();
            foreach ($varSaved as $l) {
                $variables[$l['name']] = $varVal->{$l['name']} ?? "";
            }

            $treatmentModel = new Treatment;
            $treatments = !empty($variety) ? $treatmentModel->where('variety_id', $variety['id'])->where('is_approved', 1)->where('name IS NOT NUll')->findAll() : array();

            return view('backend/report/trials/create', compact('locations', 'varieties', 'trialData', 'trials', 'trial', 'variables', 'location', 'variety', 'treatments'));
        }
    }

    public function delete($id)
    {
        $userId = \auth_admin()['id'];

        if (auth_admin()['type'] > 0) {
            $this->model->where(['user_id' => $userId, 'id' => $id])->delete();
        } else {
            $this->model->delete($id);
        }
        return \redirect()->back()->with('success', 'Trial deleted successfully');
    }

    public function bulkInsert()
    {
        $validate = $this->validate([
            'crop_id' => 'required|is_natural_no_zero',
            'bulk_file' => 'uploaded[bulk_file]|ext_in[bulk_file,csv]'
        ]);
        if (!$validate) {
            return response()->setJSON(['status' => false, 'error' => $this->validator->getError('crop_id') . "-" . $this->validator->getError('bulk_file')]);
        }

        $cropId = $this->request->getPost('crop_id');

        $filePath = $_FILES['bulk_file']['tmp_name'];
        $csv = Reader::createFromPath($filePath);
        $expectedHeaders = $this->formatDownload($cropId, true);
        $headers = $csv->getHeader();
        $records = $csv->getRecords();

        $validatedData = array();

        foreach ($records as $k => $record) {
            if ($k == 0) {
                if (empty($headers)) {
                    $headers = $record;
                    if ($expectedHeaders != $headers) {
                        return response()->setJSON(['status' => false, 'error' => 'Uploaded CSV format is not valid!']);
                    }
                    continue;
                } else {
                    if ($expectedHeaders != $headers) {
                        return response()->setJSON(['status' => false, 'error' => 'Uploaded CSV format is not valid!']);
                    }
                }
            }

            $validatedData[] = $this->validateImportRecord($cropId, $record);
        }

        if (empty($validatedData)) {
            return response()->setJSON(['status' => false, 'error' => "No data Found!"]);
        }


        $view = view('backend/report/trials/validation', compact('validatedData', 'expectedHeaders'));
        $returnUrl = $_SERVER['HTTP_REFERER'];

        return response()->setJSON(['status' => true, 'html' => $view, 'data' => json_encode($validatedData), 'header' => json_encode($expectedHeaders), 'crop_id' => $cropId, 'return' => $returnUrl]);
    }

    /**
     * Validate Imported Record
     * @return array
     */

    public function validateImportRecord($cropId, $record)
    {
        $trialModel = new Trials;
        $trialTypeModel = new TrialType;
        $varietyModel = new Variety;
        $locationModel = new Location;
        $trialLocationModel = new TrialLocation;
        $treatmentModel = new Treatment;
        $error = null;


        $trial = $record[0];
        $site = $record[1];
        $variety_code = $record[2];

        //Trial Validation
        if (!empty($trial)) {
            $trial_row = $trialModel->where('name', $trial)->find();
            if (empty($trial_row)) {
                $error .= "<li>Trial not found</li>";
            }
        } else {
            $error .= "<li>Trial value required</li>";
        }

        //Location Validation
        if (empty($site)) {
            $error .= "<li>Site value required</li>";
        } elseif (!empty($trial_row)) {
            $location = $locationModel->where('code', $site)->first();
            if (empty($location)) {
                $error .= "<li>Site not found</li>";
            } else {
                $trialLocation = $trialLocationModel->where('trial_id', $trial_row[0]['id'])->where('location_id', $location['id'])->first();
                if (empty($trialLocation)) {
                    $error .= "<li>Given Site not associated with trial</li>";
                } else {
                    $record['state_code'] = $location['state_code'];
                    $record['location'] = $location['location'];
                }
            }
        }

        //Variety Validation
        if (empty($variety_code)) {
            $error .= "<li>Variety/Hybrid required</li>";
        } else {
            $variety = $varietyModel->where('code', $variety_code)->first();
            if (empty($variety)) {
                $error .= "<li>Variety/Hybrid not valid</li>";
            }
        }
        $record['error'] = $error;

        return $record;
    }


    /**
     * Import Trial Data
     * @return mixed
     */

    public function importProcess()
    {
        $cropId = $this->request->getPost('crop_id');
        $records = (string) $this->request->getPost('data');
        $return = (string) $this->request->getPost('return');
        $records = json_decode($records, true);

        $trialModel = new Trials();

        foreach ($records as $record) {
            if (!empty($record['error'])) continue;

            $headers = (string) $this->request->getPost('header');
            $headers = json_decode($headers, true);

            $trial = trim($record[0]);
            $location_code = trim($record[1]);
            $state_code = trim($record['state_code']);
            $location = trim($record['location']);
            $variety_code = trim($record[2]);

            $trial_row = $trialModel->where('name', $trial)->find()[0];

            $data = [
                'year' => $trial_row['year'],
                'trial' => $trial_row['trial_type_id'],
                'crop_id' => $cropId,
                'user_id' => auth_admin()['id'],
                'program' => $trial_row['id'],
                'location_code' => $location_code,
                'state_code' => $state_code,
                'location' => $location,
                'variety_code' => $variety_code,
            ];

            $chkData = [
                'year' => $trial_row['year'],
                'trial' => $trial_row['trial_type_id'],
                'crop_id' => $cropId,
                'program' => $trial_row['id'],
                'location_code' => $location_code,
                'state_code' => $state_code,
                'location' => $location,
                'variety_code' => $variety_code,
            ];

            if ($chk_trial = $this->model->where($chkData)->first()) {
                $this->model->update($chk_trial['id'], $data);
                $trialId = $chk_trial['id'];
            } else {
                $data['is_approved'] = auth_admin()['type'] == 0 ? 1 : 0;
                $trialId = $this->model->insert($data);
            }

            unset($record['state_code']);
            unset($record['location']);
            unset($record['error']);

            \array_splice($record, 0, 3);
            \array_splice($headers, 0, 3);

            $varData = [];
            foreach ($headers as $k => $l) {
                $variableModel = new CropVariable();
                if (!empty(trim($l)) && !$variableModel->where(['crop_id' => $cropId, 'name' => trim($l)])->find()) {
                    $variableModel->insert(['name' => $l, 'crop_id' => $cropId]);
                }
                $varData[$l] = $record[$k];
            }

            $this->model->update($trialId, ['variable' => \json_encode($varData)]);
        }



        return \redirect()->to($return)->with('success', 'Data imported successfully');
    }

    /**
     * Copy Previous Year data
     * @return void  
     */

    public function copy()
    {
        $trials = $this->model->where('year', date('Y', strtotime('-1 year')))->find();
        if (empty($trials)) return \redirect()->back()->with('error', 'Previous year data not found');
        array_walk($trials, function (&$trial) {
            unset($trial['id']);
            unset($trial['created_at']);
            unset($trial['updated_at']);
            unset($trial['deleted_at']);
            $trial['year'] = date('Y');
            $trial['is_approved'] = 0;
            return $trial;
        });
        if ($this->model->insertBatch($trials)) {
            return redirect()->back()->with('success', 'Trials copied successfully');
        } else {
            return redirect()->back()->with('error', 'Something wents wrong!');
        }
    }

    /**
     * Export crop and yearwise trials
     * @return null
     */

    public function export($file_type = 'csv')
    {
        $userId = \auth_admin()['id'];
        $type  = \auth_admin()['type'];
        $cropId = $this->request->getPost('crop_id');
        $isApproved = $this->request->getPost('status');
        $cropModel = new Crop();
        $crop = $cropModel->find($cropId);
        $crop_name = $crop['name'];
        $year = $this->request->getPost('year');
        $trialData = $this->model->select('trial_data.*,v.brand as brand,v.short_name as variety,v.additional_name as additional,tt.name as trial_type,vt_trials.treatment_group as treatment_group');
        $trialData->join('vt_trials', 'trial_data.program=vt_trials.id', 'left');
        $trialData->join('varieties v', 'v.code=trial_data.variety_code', 'left');
        $trialData->join('trial_types tt', 'tt.id=trial_data.trial');
        $type > 0 ? $trialData->where('trial_data.user_id', $userId) : "";
        $year ? $trialData->where('trial_data.year', $year) : "";
        $isApproved > -1 ? $trialData->where('is_approved', $isApproved) : "";
        $trialData->where('trial_data.crop_id', $cropId);
        $result = $trialData->orderBy('year', 'desc')->find();
        if (empty($result)) return \redirect()->back()->with('warning', 'No data found');
        $variableModel = new CropVariable();
        $variables = $variableModel->where('crop_id', $cropId)->find();
        $headers = array('Year', 'Site', 'Trial', 'Treatment', 'Company/Brand', 'Hybrid/Variety');
        $records = array();

        $treatmentModel  = new Treatment();

        foreach ($result as $l) {
            $treatment_group = $l['treatment_group'];
            $treatments = $treatmentModel->where('group', $treatment_group)->findAll();
            if (!empty($treatments)) {
                foreach ($treatments as $k => $treatment) {
                    $records[$k][] = $l['year'];
                    $records[$k][] = $l['location_code'];
                    $records[$k][] = $l['trial_type'];
                    $records[$k][] = $treatment['name'];
                    $records[$k][] = $l['brand'];
                    $records[$k][] = $l['variety_code'];

                    $vars = json_decode($l['variable']);

                    foreach ($variables as $v) {
                        if ($k == 0) {
                            $headers[] = $v['name'];
                        }
                        $records[$k][] = $vars->{$v['name']} ?? "";
                    }
                }
            }
        }


        if ($file_type == "csv") {
            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            $csv->insertOne($headers);
            $csv->insertAll($records);
            $csv->output($crop_name);
            exit;
        }
    }

    public function approve()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $trial = $this->model->find($id);
            $data['is_approved'] = !$trial['is_approved'];
            $this->model->update($id, $data);
            return \response()->setJSON(['status' => true, 'value' => $data['is_approved'], 'hash' => \csrf_hash()]);
        }
    }

    public function bulkApprove()
    {
        $ids = $this->request->getPost('ids');
        if (!empty($ids)) {
            $this->model->update($ids, ['is_approved' => 1]);
            return redirect()->back()->with('success', 'Trials approved successfully');
        } else {
            return redirect()->back()->with('warning', 'Plase select atleast one trial');
        }
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $this->delete($id);
            }
            return redirect()->back()->with('success', 'Trials deleted successfully');
        } else {
            return redirect()->back()->with('warning', 'Plase select atleast one trial');
        }
    }

    public function formatDownload($cropId = false, $return = false)
    {
        $id = $this->request->is('post') ? $this->request->getPost('crop_id') : $cropId;
        $cropModel = new Crop;
        $crop = $cropModel->find($id);
        $fileName = ($crop['name'] ?? "") . " Trial Data Format.csv";
        $columns = ['Trial', 'Site', 'Hybrid/Variety'];
        $variableModel = new CropVariable;
        $variables = $variableModel->where('crop_id', $id)->findColumn('name');
        $columns = !empty($variables) ? array_merge($columns, $variables) : $columns;
        if ($return) {
            return $columns;
        } else {
            $csv = Writer::createFromFileObject(new SplTempFileObject());
            $csv->insertOne($columns);
            $csv->output($fileName);
            die();
        }
    }
}
