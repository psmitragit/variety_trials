<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Brand;
use App\Models\State;
use App\Models\Crop;
use App\Models\CropVariable;
use App\Models\Location;
use App\Models\Trials;
use App\Models\Variety;
use League\Csv\Reader;
use League\Csv\Writer;

class TrialController extends BaseController
{
    private $model;
    public function __construct()
    {
        $this->model  = new Trials();
    }
    public function index()
    {
        $cropModel = new Crop();
        $crops = $cropModel->findAll();
        return view('backend/trials/index', \compact('crops'));
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

            $columns = ['id', 'crops.name', 'trials.year', 'trials.program', 'trials.trial', 'trials.state_code', 'trials.location', 'trials.variety_code', 'varieties.brand', 'trials.is_approved'];

            $trial = $this->model->select('trials.*,varieties.brand as brand,crops.name as crop');
            $trial->join('crops', 'trials.crop_id=crops.id');
            $type > 0 ? $trial->where('trials.user_id', $userId) : "";
            $trial->join('varieties', 'trials.variety_code=varieties.code', 'left');
            !empty($cropId) ? $trial->where('trials.crop_id', $cropId) : "";
            $status >= 0 ? $trial->where('trials.is_approved', $status) : "";
            if ($search) {
                $trial->groupStart();
                $trial->where('crops.name like ', '%' . $search . '%');
                $trial->orWhere('trials.year like ', '%' . $search . '%');
                $trial->orWhere('trials.state_code like ', '%' . $search . '%');
                $trial->orWhere('trials.program like ', '%' . $search . '%');
                $trial->orWhere('trials.trial like ', '%' . $search . '%');
                $trial->orWhere('trials.location like ', '%' . $search . '%');
                $trial->orWhere('trials.variety_code like ', '%' . $search . '%');
                $trial->orWhere('varieties.brand like ', '%' . $search . '%');
                $trial->groupEnd();
            }

            if ($order) {
                $trial->orderBy($columns[$order['column'] ?? 0], $order['dir'] ?? 'asc');
            } else {
                $trial->orderBy('trials.year', 'DESC');
            }
            $totalEntry = $trial->countAllResults(false);
            $trials = $trial->limit($length, $start)->find();


            $data = array();
            foreach ($trials as $k => $l) {
                $data[$k]['ids'] = '<input type="checkbox" name="ids[]" class="selectId" value="' . $l['id'] . '">';
                $data[$k]['crop'] = $l['crop'];
                $data[$k]['year'] = $l['year'];
                $data[$k]['program'] = $l['program'];
                $data[$k]['trial'] = $l['trial'];
                $data[$k]['state'] = $l['state_code'];
                $data[$k]['location'] = $l['location'];
                $data[$k]['variety'] = $l['variety_code'];
                $data[$k]['brand'] = $l['brand'];
                $data[$k]['status'] = $l['is_approved'] ? '<span class="badge badge-success approve_trial" data-bs-toggle="tooltip" data-id="' . $l['id'] . '" role="button">Approved</span>' : '<span class="badge badge-danger approve_trial" data-id="' . $l['id'] . '" role="button">Unapproved</span>';
                $action = '<a class="text-decoration-none text-warning" title="Edit" href="' . base_url('admin/trials/' . $l['id'] . '/edit') . '"><i class="ti ti-pencil-alt"></i></a>&ensp;
                                        <a class="text-decoration-none text-danger" data-bs-toggle="tooltip" title="Delete" href="' . base_url('admin/trials/' . $l['id'] . '/delete') . '"><i class="ti ti-trash"></i></a>';
                $data[$k]['action'] = $action;
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
            ];

            $this->model->insert($data);
            return \redirect()->back()->with('success', "Trial added successfully");
        } else {
            $locationModel = new Location();
            $locations = $locationModel->where('status', 1)->findAll();
            $varietyModel = new Variety();
            $varieties = $varietyModel->where('status', 1)->findAll();
            $variables = array();
            return view('backend/trials/create', compact('locations', 'varieties', 'variables'));
        }
    }
    public function edit($id)
    {

        $trial = $this->model->find($id);
        if (!$trial) return \redirect()->back('error', 'Unauthorized access!');
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'crop_id' => 'required',
                'year' => 'required|is_natural_no_zero|exact_length[4]',
                'trial' => 'required',
                'program' => 'required',
                'locid' => 'required',
                'variety' => 'required',
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
                'is_approved' => $this->request->getVar('is_approved')
            ];
            $this->model->update($trial['id'], $data);
            return \redirect()->back()->with('success', "Trial updated successfully");
        } else {
            $locationModel = new Location();
            $locations = $locationModel->where('status', 1)->findAll();
            $varietyModel = new Variety();
            $varieties = $varietyModel->where('status', 1)->findAll();
            $variableModel = new CropVariable();
            $varSaved = $variableModel->where('crop_id', $trial['crop_id'])->findAll();
            $varVal = \json_decode($trial['variable']);
            $variables = array();
            foreach ($varSaved as $l) {
                $variables[$l['name']] = $varVal->{$l['name']} ?? "";
            }

            return view('backend/trials/create', compact('locations', 'varieties', 'trial', 'variables'));
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
            return redirect()->back()->with('error', $this->validator->getError('crop_id') . "-" . $this->validator->getError('bulk_file'));
        }

        $cropId = $this->request->getPost('crop_id');

        $filePath = $_FILES['bulk_file']['tmp_name'];
        $csv = Reader::createFromPath($filePath);
        $expectedHeaders = ['Year', 'State', 'Program', 'Trial', 'LocID', 'Location', 'VarietyID', 'Brand', 'Variety', 'Variety Additional', 'Herbicide Package'];
        $headers = $csv->getHeader();
        $records = $csv->getRecords();

        foreach ($records as $k => $record) {
            if ($k == 0) {
                if (empty($headers)) {
                    $headers = $record;
                    $splice = \array_splice($headers, 0, 11);
                    if ($expectedHeaders != $splice) {
                        return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
                    }
                    continue;
                } else {
                    $splice = \array_splice($headers, 0, 5);
                    if ($expectedHeaders != $splice) {
                        return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
                    }
                }
            }


            $brandModel = new Brand();
            $varietyModel = new Variety();
            $stateModel = new State();

            if (!empty(\trim($record['1'])) && !$stateModel->where('code', \trim($record['1']))->find()) {
                $stateModel->insert(['code' => \trim($record['1'])]);
            }

            if (!empty(\trim($record[6])) && !$varietyModel->where('code', \trim($record[6]))->find()) {
                $varietyModel->insert(
                    [
                        'code' => trim($record[6]),
                        'brand' => trim($record[7]),
                        'short_name' => trim($record[8]),
                        'additional_name' => trim($record[9]),
                        'herbicide' => trim($record[10]),
                    ]
                );
            }


            if (!empty(trim($record[7])) && !$brandModel->where('name', trim($record[7]))->first()) {
                $brandModel->insert(['name' => trim($record[1])]);
            }



            $data = [
                'year' => trim($record[0]),
                'crop_id' => $cropId,
                'user_id' => auth_admin()['id'],
                'state_code'  => trim($record[1]),
                'program'  => trim($record[2]),
                'trial'  => trim($record[3]),
                'location'  => trim($record[5]),
                'variety_code'  => trim($record[6]),
            ];
            $chkData = $data;
            $data['location_code'] = trim($record[4]);

            if ($trial = $this->model->where($chkData)->first()) {
                $this->model->update($trial['id'], $data);
                $trialId = $trial['id'];
            } else {
                $data['is_approved'] = auth_admin()['type'] == 0 ? 1 : 0;
                $trialId = $this->model->insert($data);
            }

            //variable
            \array_splice($record, 0, 11);
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
        return \redirect()->back()->with('success', 'Data imported successfully');
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
     * @return stream
     */

    public function export($type = 'csv')
    {
        $userId = \auth_admin()['id'];
        $type  = \auth_admin()['type'];
        $cropId = $this->request->getPost('crop_id');
        $cropModel = new Crop();
        $crop = $cropModel->find($cropId);
        $crop_name = $crop['name'];
        $year = $this->request->getPost('year');
        $trials = $this->model->select('trials.*,v.brand as brand,v.short_name as variety,v.additional_name as additional,v.herbicide');
        $trials->join('varieties v', 'v.code=trials.variety_code', 'left');
        $type > 0 ? $trials->where('trials.user_id', $userId) : "";
        $year ? $trials->where('year', $year) : "";
        $trials->where('crop_id', $cropId);
        $result = $trials->orderBy('year', 'desc')->find();
        if (empty($result)) return \redirect()->back()->with('warning', 'No data found');
        $variableModel = new CropVariable();
        $variables = $variableModel->where('crop_id', $cropId)->find();
        $headers = array('Year', 'State', 'Program', 'Trial', 'LocID', 'Location', 'VarietyID', 'Brand', 'Variety', 'Variety Additional', 'Herbicide Package');
        $records = array();
        foreach ($result as $k => $l) {
            $records[$k][] = $l['year'];
            $records[$k][] = $l['state_code'];
            $records[$k][] = $l['program'];
            $records[$k][] = $l['trial'];
            $records[$k][] = $l['location_code'];
            $records[$k][] = $l['location'];
            $records[$k][] = $l['variety_code'];
            $records[$k][] = $l['brand'];
            $records[$k][] = $l['variety'];
            $records[$k][] = $l['additional'];
            $records[$k][] = $l['herbicide'];

            $vars = json_decode($l['variable']);

            foreach ($variables as $v) {
                if ($k == 0) {
                    $headers[] = $v['name'];
                }
                $records[$k][] = $vars->{$v['name']} ?? "";
            }
        }

        if ($type == "csv") {
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
}
