<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Brand;
use App\Models\Crop;
use App\Models\CropVariable;
use App\Models\State;
use App\Models\Treatment;
use App\Models\TrialData;
use App\Models\Trials;
use App\Models\TrialType;
use App\Models\Upload;
use App\Models\Variety;

class CropController extends BaseController
{
    private $model,
        $trialModel,
        $variableModel,
        $stateModel,
        $uploadModel,
        $trialDataModel,
        $brandModel,
        $varietyModel,
        $trialTypeModel,
        $treatmentModel;

    public function __construct()
    {
        $this->model = new Crop();
        $this->trialModel = new Trials();
        $this->trialDataModel = new TrialData();
        $this->variableModel = new CropVariable();
        $this->stateModel = new State();
        $this->uploadModel = new Upload();
        $this->brandModel = new Brand();
        $this->varietyModel = new Variety();
        $this->trialTypeModel = new TrialType();
        $this->treatmentModel = new Treatment();
    }

    public function index($slug)
    {
        $crop = $this->model->where('slug', $slug)->first();
        if (empty($crop)) return \redirect()->back()->with('error', "Crop not found");
        $variables = $this->variableModel->where('crop_id', $crop['id'])->find();
        $states = $this->stateModel->join('trial_data', 'states.code=trial_data.state_code')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->orderBy('states.code')->find();
        $brands = $this->brandModel->distinct()->select('brands.name')->join('varieties', 'brands.name=varieties.brand')
            ->join('trial_data', 'trial_data.variety_code=varieties.code')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->orderBy('brands.name')->find();
        $varieties  = $this->varietyModel->distinct()->select('varieties.code,varieties.short_name')->join('trial_data', 'trial_data.variety_code=varieties.code')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])
            ->orderBy('varieties.code')->find();

        $years = $this->trialDataModel->select('DISTINCT(year)')->where('crop_id', $crop['id'])->orderBy('year', 'desc')->find();
        $trials = $this->trialTypeModel->select('trial_types.id,trial_types.name')->distinct()->join('trial_data', 'trial_data.trial=trial_types.id')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->orderBy('trial_types.name')->find();
        $herbicides = $this->treatmentModel->select('DISTINCT(herbicide)')->join('trial_data', 'trial_data.entry=treatments.name')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->orderBy('treatments.herbicide')->find();

        $trialVariables = $this->trialDataModel->select('variable')->where('crop_id', $crop['id'])->find();

        $varialeData = array();
        foreach ($trialVariables as $l) {
            $data = \json_decode(($l['variable'] ?? ""), true);
            foreach ($variables as $v) {
                $varialeData[$v['name']][] = $data[$v['name']];
            }
        }


        return view('frontend/crop', \compact('crop', 'variables', 'states', 'brands', 'varieties', 'trials', 'years', 'herbicides', 'varialeData'));
    }

    public function ajaxLoad()
    {
        if ($this->request->isAJAX()) {
            $cropId = $this->request->getPost('id');
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start');
            $length = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'] ?? "";
            $order = $this->request->getPost('order')[0] ?? false;
            $year = $this->request->getPost('year') ?? false;
            $state = $this->request->getPost('state') ?? false;
            $brand = $this->request->getPost('brand') ?? false;
            $variety = $this->request->getPost('variety') ?? false;
            $trial_type = $this->request->getPost('trial') ?? false;
            $herbicide = $this->request->getPost('herbicide') ?? false;
            $fVariables = $this->request->getPost('variables') ?? '';
            $fVariables = \json_decode($fVariables, true) ?? [];

            $variables = $this->variableModel->where('crop_id', $cropId)->find();

            $columns = ['trial_data.year', 'trial_data.state_code', 'trial_data.entry', 'trial_types.name', 'trial_data.location_code', 'trial_data.location', 'trial_data.variety_code', 'v.brand', 'v.additional_name', 'v.short_name', 'v.herbicide'];

            $trial = $this->trialDataModel->select('trial_data.*,v.brand,v.short_name as variety,v.additional_name as variety_additional,v.herbicide,l.lat,l.long,,trial_types.name as trial_type_name');
            $trial->join('varieties v', 'trial_data.variety_code=v.code', 'left');
            $trial->join('locations l', 'trial_data.location_code=l.code', 'left');
            $trial->join('trial_types', 'trial_data.trial=trial_types.id');
            $trial->where(['trial_data.crop_id' => $cropId, 'trial_data.status' => 1, 'trial_data.is_approved' => 1]);
            !empty($year) ? $trial->where('trial_data.year', $year) : "";
            !empty($state) ? $trial->where('trial_data.state_code', $state) : "";
            !empty($variety) ? $trial->where('trial_data.variety_code', $variety) : "";
            !empty($trial_type) ? $trial->where('trial_data.trial', $trial_type) : "";
            !empty($brand) ? $trial->where('v.brand', $brand) : "";

            if (!empty($herbicide)) {
                $trial->join('treatments t', 't.name=trial_data.entry')->where('t.herbicide', $herbicide);
            }


            foreach ($fVariables as $k => $v) {
                if (!empty($v)) {
                    $trial->where("JSON_EXTRACT(variable, '$.\"$k\"')", $v);
                }
            }



            if ($search) {
                $trial->groupStart();
                $trial->where('trial_data.year like ', '%' . $search . '%');
                $trial->orWhere('trial_data.state_code like ', '%' . $search . '%');
                $trial->orWhere('trial_data.entry like ', '%' . $search . '%');
                $trial->orWhere('trial_types.name like ', '%' . $search . '%');
                $trial->orWhere('trial_data.location_code like ', '%' . $search . '%');
                $trial->orWhere('trial_data.location like ', '%' . $search . '%');
                $trial->orWhere('trial_data.variety_code like ', '%' . $search . '%');
                $trial->orWhere('v.brand like ', '%' . $search . '%');
                $trial->orWhere('v.additional_name like ', '%' . $search . '%');
                $trial->orWhere('v.short_name like ', '%' . $search . '%');
                // $trial->orWhere('v.herbicide like ', '%' . $search . '%');
                $trial->groupEnd();
            }
            if ($order && count($columns) > $order['column']) {
                $trial->orderBy($columns[$order['column'] ?? 0], $order['dir'] ?? 'asc');
            } elseif ($order && count($columns) <= $order['column']) {
                $trial->orderBy('rand()');
            } else {
                $trial->orderBy('trial_data.year', 'DESC');
            }
            $totalEntry = $trial->countAllResults(false);
            $trials = $trial->limit($length, $start)->find();


            $data = array();
            $coordinates = array();
            $locTitle = array();
            foreach ($trials as $k => $l) {
                $data[$k]['year'] = $l['year'];
                $data[$k]['state'] = $l['state_code'];
                $data[$k]['program'] = $l['entry'];
                $data[$k]['trial'] = $l['trial_type_name'];
                $data[$k]['loc_id'] = $l['location_code'];
                $data[$k]['location'] = $l['location'];
                $data[$k]['variety_id'] = $l['variety_code'];
                $data[$k]['brand'] = $l['brand'];
                $data[$k]['variety'] = $l['variety'];
                $data[$k]['variety_additional'] = $l['variety_additional'];
                // $data[$k]['herbicide'] = $l['herbicide'];
                $varArray = \json_decode($l['variable']);
                foreach ($variables as $v) {
                    $data[$k][$v['name']] = $varArray->{$v['name']} ?? "";
                }

                $coordinates[$k]['lat'] = $this->getDecimalDegree($l['lat']);
                $coordinates[$k]['lng'] = $this->getDecimalDegree($l['long']);
                if (!empty($coordinates[$k]['lat']) && !empty($coordinates[$k]['lng'])) {
                    $locTitle[] = $l['location'] ?? "";
                }
            }
            $coordinates = array_map('array_filter', $coordinates);
            $coordinates = \array_filter($coordinates);
            $coordinates = \array_values($coordinates);
            $resData = [
                'draw' => \intval($draw),
                'data' => $data,
                'recordsTotal' => $totalEntry,
                'recordsFiltered' => $totalEntry,
                'hash' => \csrf_hash(),
                'coordinates' => $coordinates,
                'pointname' => $locTitle,
            ];
            return \response()->setJSON($resData);
        }
    }

    public function downloads($slug)
    {
        $states = $this->stateModel->findAll();
        $crop = $this->model->where('slug', $slug)->first();
        if (empty($crop)) return \redirect()->back()->with('error', "Crop not found");
        return \view('frontend/download', \compact('crop', 'states'));
    }

    public function ajaxDownloadLoad()
    {
        if ($this->request->isAJAX()) {
            $cropId = $this->request->getPost('id');
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start');
            $length = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'] ?? "";
            $order = $this->request->getPost('order')[0] ?? false;
            $year = $this->request->getPost('year') ?? false;
            $state_code = $this->request->getPost('state') ?? false;
            $columns = ['crop_id', 'year', 'title', 'state_code'];

            $q = $this->uploadModel->select('year,state_code,url,title')->where('status', 1)->where('crop_id', $cropId);
            $year ? $q->where('year', $year) : "";
            $state_code ? $q->where('state_code', $state_code) : "";

            if ($search) {
                foreach ($columns as $l) {
                    $q->where($l, 'like', '%' . $search . '$');
                }
            }

            if ($order) {
                $q->orderBy($columns[$order['column'] ?? 0], $order['dir'] ?? 'asc');
            } else {
                $q->orderBy('year', 'DESC');
            }

            $totalEntry = $q->countAllResults(false);
            $uploads = $q->limit($length, $start)->find();

            $data = array();
            foreach ($uploads as $k => $l) {
                $data[$k]['year'] = $l['year'];
                $data[$k]['state'] = $l['state_code'];
                $data[$k]['title'] = '<a class="text-decoration-none" target="_blank" href="' . base_url($l['url']) . '">' . $l['title'] . '</a>';
                $data[$k]['url'] = '<a class="text-decoration-none" href="' . base_url($l['url']) . '" download><i class="ti ti-download text-primary fw-bold"></i></a>';
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
    public function documents()
    {
        $states = $this->stateModel->findAll();
        return \view('frontend/documents', compact('states'));
    }

    public function getDocuments()
    {
        if ($this->request->isAJAX()) {
            $start = $this->request->getPost('start');
            $end = $this->request->getPost('end');
            $cropId = $this->request->getPost('crop');
            $state = $this->request->getPost('state') ?? false;

            $q = $this->uploadModel->select('uploads.*,c.name as crop')->join('crops c', 'c.id=uploads.crop_id')->where('uploads.crop_id', $cropId)->where('uploads.year>=', $start)->where('uploads.year<=', $end);
            $state ? $q->where('state_code', $state) : "";
            $documents = $q->find();

            return \response()->setJSON(['status' => true, 'documents' => $documents]);
        }
    }


    public function getDecimalDegree($val)
    {
        $pattern = '/(\d+)°(\d+)\'(\d+(\.\d+)?)\"([NSWE]?)$/';
        if (preg_match($pattern, $val, $matches)) {
            $dd = (float) $matches[1] + (float) $matches[2] / 60 +  (float) $matches[3] / 3600;
            if ($matches[5] == 'S' || $matches[5] == 'W') {
                $dd = -$dd;
            }
            return $dd;
        } else {
            return;
        }
    }
}
