<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Brand;
use App\Models\Crop;
use App\Models\CropVariable;
use App\Models\State;
use App\Models\Treatment;
use App\Models\TrialData;
use App\Models\TrialLocation;
use App\Models\Trials;
use App\Models\TrialType;
use App\Models\Upload;
use App\Models\Variety;
use Exception;

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
        $treatmentModel,
        $trialLocationModel;

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
        $this->trialLocationModel = new TrialLocation();
    }

    public function index($slug)
    {
        $crop = $this->model->where('slug', $slug)->first();
        if (empty($crop)) return \redirect()->back()->with('error', "Crop not found");

        //YEAR
        $years = $this->trialDataModel->select('year')->where('crop_id', $crop['id'])->orderBy('year', 'desc')->groupBy('year')->distinct()->findAll();

        //STATES
        $states = $this->stateModel->select('states.name,states.code')->join('trial_data', 'states.code=trial_data.state_code')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->orderBy('states.code')->groupBy('trial_data.state_code')->distinct()->findAll();

        //BRAND
        $brands = $this->brandModel->select('brands.name')->join('varieties', 'brands.name=varieties.brand')
            ->join('trial_data', 'trial_data.variety_code=varieties.code')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->orderBy('brands.name')->groupBy('trial_data.variety_code')->distinct()->findAll();

        //VARIETIES
        $varieties  = $this->varietyModel->select('varieties.code,varieties.short_name')->join('trial_data', 'trial_data.variety_code=varieties.code')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->orderBy('varieties.code')
            ->groupBy('trial_data.variety_code')->distinct()->findAll();

        //TRIALS
        $trials = $this->trialTypeModel->select('trial_types.id,trial_types.name')->join('trial_data', 'trial_data.trial=trial_types.id')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->groupBy('trial_types.name')
            ->orderBy('trial_types.name')->distinct()->findAll();

        //HERBICIDES
        // $herbicides = $this->treatmentModel->select('treatments.herbicide')->join('trial_data', 'trial_data.entry=treatments.name')->where(['trial_data.crop_id' => $crop['id'], 'trial_data.is_approved' => 1])->groupBy('treatments.herbicide')
        $herbicides = [];
        //LOCATION DATA's
        $locationResult = $this->trialLocationModel
            ->select('MIN(avarage_temparature) as min_temp, MAX(avarage_temparature) as max_temp, MIN(avarage_percipitation) as min_precip, MAX(avarage_percipitation) as max_precip')
            ->first();
        $min_temp = $locationResult['min_temp'];
        $max_temp = $locationResult['max_temp'];
        $min_precip = $locationResult['min_precip'];
        $max_precip = $locationResult['max_precip'];
        // $minTemp  = $this->trialLocationModel->select('')


        //VARIABLES
        $allVariables = $this->variableModel->select('crop_variables.show_frontent,crop_variables.name,crop_variables.filter,crop_variables.multiselect')->where('crop_id', $crop['id'])->findAll();
        $multiselect = [];
        $variables = [];


        $trait = [];
        $management = [];
        $numeric = [];
        $other = [];

        foreach ($allVariables as $key => $value) {
            if($value['show_frontent'] < 1){
                continue;
            }
            if ($value['filter'] == 'trait') {
                $trait[] = $value['name'];
            } else if ($value['filter'] == 'management') {
                $management[] = $value['name'];
            } else if ($value['filter'] == 'numeric') {
                $numeric[$value['name']] =  [
                    'max' => 0,
                    'min' => 0
                ];
            } else {
                $other[] = $value['name'];
            }
            $variables[] = $value['name'];
            if ($value['multiselect'] == 1) {
                $multiselect[] = $value['name'];
            }
        }

        $trialVariables = $this->trialDataModel->select('variable')->where('crop_id', $crop['id'])->groupBy('variable')->distinct()->findAll();
        $decoded = array_map(fn($item) => json_decode($item['variable'], true), $trialVariables);

        $varialeData = [];
        foreach ($variables as $i) {
            $varialeData[$i] = [];
            $valueSet[$i] = [];
        }

        foreach ($decoded as $trialVariable) {
            foreach ($variables as $name) {
                if (array_key_exists($name, $numeric)) {
                    if (is_numeric($trialVariable[$name])) {
                        if (empty($numeric[$name]['min'])) {
                            $numeric[$name]['min'] = $trialVariable[$name] ?? 0;
                        } else if ($trialVariable[$name] < $numeric[$name]['min']) {
                            $numeric[$name]['min'] = $trialVariable[$name] ?? 0;
                        }
                        if (empty($numeric[$name]['max'])) {
                            $numeric[$name]['max'] = $trialVariable[$name] ?? 0;
                        } else if ($trialVariable[$name] > $numeric[$name]['max']) {
                            $numeric[$name]['max'] = $trialVariable[$name] ?? 0;
                        }
                    }
                } else {
                    if (!empty($trialVariable[$name])) {
                        $value = $trialVariable[$name];
                        if (!isset($valueSet[$name][$value])) {
                            $varialeData[$name][] = $value;
                            $valueSet[$name][$value] = true;
                        }
                    }
                }
            }
        }


        // dd([$variables]);
        return view('frontend/crop', \compact('crop', 'variables', 'states', 'brands', 'varieties', 'trials', 'years', 'herbicides', 'varialeData', 'trait', 'management', 'numeric', 'other', 'multiselect', 'min_temp', 'max_temp', 'min_precip', 'max_precip'));
    }

    public function get_locations_by_state()
    {
        $cropId = $this->request->getPost('id');
        $states = $this->request->getPost('states');
        if (empty($states)) {
            echo json_encode(['success' => 1, 'location' => []]);
            exit;
        }
        //LOCATIONS
        $locations = $this->trialDataModel->select('location')->where('crop_id', $cropId)->whereIn('state_code', $states)->orderBy('location', 'asc')->groupBy('location')->distinct()->findAll();
        echo json_encode(['success' => 1, 'location' => $locations]);
        exit;
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
            if ($year) {
                $year = explode(',', $year);
            }
            $state = $this->request->getPost('state') ?? false;
            if ($state) {
                $state = explode(',', $state);
            }
            $location = $this->request->getPost('location') ?? false;
            if ($location) {
                $location = explode(',', $location);
            }
            $brand = $this->request->getPost('brand') ?? false;
            if ($brand) {
                $brand = explode(',', $brand);
            }
            $variety = $this->request->getPost('variety') ?? false;
            if ($variety) {
                $variety = explode(',', $variety);
            }
            $trial_type = $this->request->getPost('trial') ?? false;
            $herbicide = $this->request->getPost('herbicide') ?? false;
            $fVariables = $this->request->getPost('variables') ?? '';
            $fVariables = \json_decode($fVariables, true) ?? [];
            $otherVariables = [];
            foreach ($fVariables as $key => $value) {
                $otherVariables[] = $key;
            }

            $variables = $this->variableModel->where('crop_id', $cropId)->find();
            $numericFilter = [];

            foreach ($variables as $key => $value) {
                if ($value['filter'] == 'numeric') {
                    $numericFilter[] = $value['name'];
                }
            }

            $columns = ['trial_data.year', 'trial_data.state_code', 'trial_data.entry', 'trial_types.name', 'trial_data.location_code', 'trial_data.location', 'trial_data.variety_code', 'v.brand', 'variety', 'variety_additional'];

            $select = 'trial_data.*,v.brand,v.short_name as variety,v.additional_name as variety_additional,v.herbicide,l.lat,l.long,trial_types.name as trial_type_name,trial_location.avarage_temparature,trial_location.avarage_percipitation,trial_location.production_pratice,trial_location.water_management';

            if ($order && (count($columns) - 1) < $order['column']) {
                $index = $order['column'] - (count($columns));
                $columnName = $otherVariables[$index] ?? '';
                if (!empty($columnName)) {
                    $select .= ", LOWER(JSON_UNQUOTE(JSON_EXTRACT(variable, '$.\"$columnName\"'))) AS variable_data";
                }
            }

            $trial = $this->trialDataModel->select($select);
            $trial->join('varieties v', 'trial_data.variety_code=v.code', 'left');
            $trial->join('locations l', 'trial_data.location_code=l.code', 'left');
            $trial->join('trial_types', 'trial_data.trial=trial_types.id');
            $trial->join('trial_location', 'trial_location.trial_id=trial_data.id', 'left');
            $trial->where(['trial_data.crop_id' => $cropId, 'trial_data.status' => 1, 'trial_data.is_approved' => 1]);
            !empty($year) ? $trial->whereIn('trial_data.year', $year) : "";
            !empty($state) ? $trial->whereIn('trial_data.state_code', $state) : "";
            !empty($variety) ? $trial->whereIn('trial_data.variety_code', $variety) : "";
            !empty($trial_type) ? $trial->where('trial_data.trial', $trial_type) : "";
            !empty($brand) ? $trial->whereIn('v.brand', $brand) : "";
            !empty($location) ? $trial->whereIn('trial_data.location', $location) : "";

            if (!empty($herbicide)) {
                $trial->join('treatments t', 't.name=trial_data.entry')->where('t.herbicide', $herbicide);
            }

            $location_table = ['avarage_temparature' => '<=', 'avarage_percipitation' => '<=', 'production_pratice' => '=', 'water_management' => '='];
            foreach ($fVariables as $k => $v) {
                if (!empty($v) || ($k == 'water_management' && $v == 0) || ($k == 'production_pratice' && $v == 0)) {
                    if (in_array($k, $numericFilter)) {
                        $trial->where("JSON_EXTRACT(variable, '$.\"$k\"') <=", $v)->where("JSON_EXTRACT(variable, '$.\"$k\"') >", 0);
                    } else {
                        if (is_array($v)) {
                            if (key_exists($k, $location_table)) {
                                $trial->whereIn('trial_location.' . $k, $v);
                            } else {
                                $trial->whereIn("JSON_EXTRACT(variable, '$.\"$k\"')", $v);
                            }
                        } else {
                            if (key_exists($k, $location_table)) {
                                if ($k == 'water_management' || $k == 'production_pratice') {
                                    $v = explode(',', $v);
                                    $trial->whereIn('trial_location.' . $k . ' ' . $location_table[$k], $v);
                                } else {
                                    $trial->where('trial_location.' . $k . ' ' . $location_table[$k], $v);
                                }
                            } else {
                                $trial->where("JSON_EXTRACT(variable, '$.\"$k\"')", $v);
                            }
                        }
                    }
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
            // if ($order && (count($columns) - 1) >= $order['column']) {
            //     $trial->orderBy($columns[$order['column'] ?? 0], $order['dir'] ?? 'asc');
            // } elseif ($order && ((count($columns) + count($variables)) - 1) <= $order['column']) {
            //     dD('d');
            //     $trial->orderBy('variable_data', $order['dir'] ?? 'asc');
            // } elseif ($order && (((count($columns) + count($variables)) + 4) - 1) < $order['column']){
            //     dd('4th');
            //     $trial->orderBy('trial_location.', $order['dir'] ?? 'asc');
            // }else {
            //     dd($order && (((count($columns) + count($variables)) + 4) - 1) , $order['column']);
            //     $trial->orderBy('trial_data.year', 'DESC');
            // }
            if ($order) {
                $orderColumnIndex = $order['column'] ?? 0;
                $orderDir = $order['dir'] ?? 'asc';
                $baseColumnCount = count($columns);
                $variableColumnCount = count($variables);
                $extraColumnsCount = 4;
                if ($orderColumnIndex < $baseColumnCount) {
                    $trial->orderBy($columns[$orderColumnIndex], $orderDir);
                } elseif ($orderColumnIndex < ($baseColumnCount + $variableColumnCount)) {
                    $trial->orderBy('variable_data', $order['dir'] ?? 'asc');
                } elseif ($orderColumnIndex < ($baseColumnCount + $variableColumnCount + $extraColumnsCount)) {
                    $orderColummnArray = ['water_management', 'production_pratice', 'avarage_percipitation', 'avarage_temparature'];
                    $index = ($baseColumnCount + $variableColumnCount + $extraColumnsCount) - ($orderColumnIndex + 1);
                    $trial->orderBy('trial_location.' . $orderColummnArray[$index], $order['dir'] ?? 'asc');
                } else {
                    $trial->orderBy('trial_data.year', 'DESC');
                }
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
                $data[$k]['avarage_temparature'] = $l['avarage_temparature'];
                $data[$k]['avarage_percipitation'] = $l['avarage_percipitation'];
                $production_pratice = $l['production_pratice'];
                $production_pratice_show = "-";
                if ("" . $production_pratice == "0") {
                    $production_pratice_show = 'Full-Season';
                } else if ("" . $production_pratice == "1") {
                    $production_pratice_show = 'Double-Crop';
                }
                $data[$k]['production_pratice'] = $production_pratice_show;
                $water_management = $l['water_management'];
                $water_management_show = "-";
                if ("" . $water_management == "0") {
                    $water_management_show = 'Irrigated';
                } else if ("" . $water_management == "1") {
                    $water_management_show = 'Non-Irrigated';
                }
                $data[$k]['water_management'] = $water_management_show;
                // $data[$k]['herbicide'] = $l['herbicide'];
                $varArray = \json_decode($l['variable']);
                foreach ($variables as $v) {
                    $data[$k][$v['name']] = $varArray->{$v['name']} ?? "";
                }

                $coordinates[$k]['lat'] = (float)$l['lat'];
                $coordinates[$k]['lng'] = (float)$l['long'];
                // $coordinates[$k]['lat'] = $this->getDecimalDegree($l['lat']);
                // $coordinates[$k]['lng'] = $this->getDecimalDegree($l['long']);
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

    public function avarage($slug)
    {
        $crop = $this->model->where('slug', $slug)->first();
        if (empty($crop)) return \redirect()->back()->with('error', "Crop not found");

        //YEAR
        $years = $this->trialDataModel->select('year')->where('crop_id', $crop['id'])->orderBy('year', 'desc')->groupBy('year')->distinct()->findAll();

        //VARIETY
        $varieties  = $this->trialDataModel->select('varieties.code,varieties.short_name')
            ->join('varieties', 'varieties.code=trial_data.variety_code')
            ->where('trial_data.crop_id', $crop['id'])
            ->groupBy('varieties.id')
            ->distinct()
            ->findAll();

        return view('frontend/avarage', compact('crop', 'years', 'varieties'));
    }

    public function location($slug)
    {
        $crop = $this->model->where('slug', $slug)->first();
        if (empty($crop)) return \redirect()->back()->with('error', "Crop not found");

        //LOCATIONS
        $locations = $this->trialDataModel->select('location')
            ->where('crop_id', $crop['id'])->orderBy('location', 'asc')
            ->groupBy('location')
            ->distinct()
            ->findAll();

        //VARIETY
        $varieties  = $this->trialDataModel->select('varieties.code,varieties.short_name')
            ->join('varieties', 'varieties.code=trial_data.variety_code')
            ->where('trial_data.crop_id', $crop['id'])
            ->groupBy('varieties.id')
            ->distinct()
            ->findAll();

        $cropVariableModel = new CropVariable();
        $numeric = $cropVariableModel
            ->select('name')
            ->where('crop_id', $crop['id'])
            ->where('filter', 'numeric')
            ->findAll();
        $numericFilters = array_column($numeric, 'name');

        return view('frontend/location_view', compact('crop', 'locations', 'varieties', 'numericFilters'));
    }

    public function getAvarage()
    {
        $varieties = $this->request->getPost('varieties');
        $years = $this->request->getPost('years');
        $crop_id = $this->request->getPost('crop_id');
        $page = (int) $this->request->getPost('page');
        $perPage = (int) $this->request->getPost('per_page');
        $orderBy = $this->request->getPost('order_by') ?? '';
        $orderDir = $this->request->getPost('order_dir') === 'desc' ? 'desc' : 'asc';

        $offset = ($page - 1) * $perPage;

        $trialDataQuery = $this->trialDataModel
            ->select('varieties.short_name, trial_data.variable,trial_data.year')
            ->where('trial_data.crop_id', $crop_id)
            ->join('varieties', 'varieties.code = trial_data.variety_code', 'left');

        if (!empty($varieties)) {
            $trialDataQuery->whereIn('trial_data.variety_code', $varieties);
        }

        if (!empty($years)) {
            $trialDataQuery->whereIn('trial_data.year', $years);
        }

        $allTrials = $trialDataQuery->findAll();

        $cropVariableModel = new CropVariable();
        $numeric = $cropVariableModel
            ->select('name')
            ->where('crop_id', $crop_id)
            ->where('filter', 'numeric')
            ->findAll();
        $numericFilters = array_column($numeric, 'name');

        // Sort if needed
        if (!empty($orderBy) && in_array($orderBy, $numericFilters)) {
            usort($allTrials, function ($a, $b) use ($orderBy, $orderDir) {
                $aData = json_decode($a['variable'], true);
                $bData = json_decode($b['variable'], true);
                $valA = $aData[$orderBy] ?? 0;
                $valB = $bData[$orderBy] ?? 0;
                return $orderDir === 'desc' ? $valB <=> $valA : $valA <=> $valB;
            });
        }

        $averages = [];
        $validCounts = [];

        foreach ($allTrials as $trial) {
            $jsonData = json_decode($trial['variable'], true);
            foreach ($numericFilters as $field) {
                $val = isset($jsonData[$field]) && is_numeric($jsonData[$field]) ? floatval($jsonData[$field]) : null;
                if ($val !== null) {
                    $averages[$field] = ($averages[$field] ?? 0) + $val;
                    $validCounts[$field] = ($validCounts[$field] ?? 0) + 1;
                }
            }
        }

        foreach ($averages as $key => $total) {
            $averages[$key] = round($total / $validCounts[$key], 2);
        }

        $totalRecords = count($allTrials);
        $paginatedTrials = array_slice($allTrials, $offset, $perPage);

        $html = "<table class='table table-bordered table-striped'>
            <thead class='table-light'>
            <tr>
                <th scope='col' style='padding-bottom:0px;'>
                    <div class='d-flex justify-content-between align-items-center'>
                        <span>Variety</span>
                    </div>
                </th>
                <th scope='col' style='padding-bottom:0px;'>
                    <div class='d-flex justify-content-between align-items-center'>
                        <span>Year</span>
                    </div>
                </th>";

        foreach ($numericFilters as $value) {
            $isActive = ($orderBy === $value);
            $ascClass = $isActive && $orderDir === 'asc' ? 'text-primary' : '';
            $descClass = $isActive && $orderDir === 'desc' ? 'text-primary' : '';

            $html .= "<th scope='col' class='sortable' data-field='" . $value . "' style='padding-bottom:0px; cursor:pointer;'>
                <div class='d-flex justify-content-between align-items-center'>
                    <span>" . htmlspecialchars($value) . "</span>
                    <span class='sort-icons'>
                        <i class='bi bi-caret-up-fill sort-icon $ascClass' data-dir='asc' title='Sort Asc'></i>
                        <i class='bi bi-caret-down-fill sort-icon $descClass' data-dir='desc' title='Sort Desc'></i>
                    </span>
                </div>
            </th>";
        }

        $html .= "</tr><tr><th style='padding-top:0px;'></th><th style='padding-top:0px;'></th>";

        foreach ($numericFilters as $field) {
            $avg = $averages[$field] ?? 0;
            $html .= "<th align='center' style='padding-top:0px; text-align:center;'>(" . htmlspecialchars($avg) . ")</th>";
        }

        $html .= "</tr></thead><tbody>";

        foreach ($paginatedTrials as $value) {
            try {
                $html .= "<tr>";
                $html .= "<td>" . htmlspecialchars($value['short_name']) . "</td>";
                $html .= "<td>" . htmlspecialchars($value['year']) . "</td>";
                $jsonData = json_decode($value['variable'], true);
                foreach ($numericFilters as $field) {
                    $cellValue = !empty($jsonData[$field]) ? $jsonData[$field] : 0;
                    $html .= "<td>" . htmlspecialchars($cellValue) . "</td>";
                }
                $html .= "</tr>";
            } catch (Exception $err) {
                continue;
            }
        }

        $html .= "</tbody></table>";

        echo json_encode([
            'success' => 1,
            'html' => $html,
            'total_records' => $totalRecords,
            'current_page' => $page,
            'per_page' => $perPage,
            'order_by' => $orderBy,
            'order_dir' => $orderDir
        ]);
        exit;
    }

    public function getLocationData()
    {
        $varieties = $this->request->getPost('varieties');
        $locations = $this->request->getPost('locations');
        $crop_id = $this->request->getPost('crop_id');
        $traitName = $this->request->getPost('trait_name');
        $page = (int) $this->request->getPost('page');
        $perPage = (int) $this->request->getPost('per_page');
        $orderBy = $this->request->getPost('order_by') ?? '';
        $orderDir = $this->request->getPost('order_dir') === 'desc' ? 'desc' : 'asc';

        $offset = ($page - 1) * $perPage;
        if (empty($traitName)) {
            echo json_encode([
                'success' => 2,
                'html' => "<table class='table table-bordered table-striped'>
                    <thead class='table-light'>
                    <tr>
                        <th scope='col'>Variety</th>
                        <th scope='col'>Location</th>
                        <th scope='col' class='sortable'>-</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan='3' class='text-center'>Please select a trait</td>
                        </tr>
                    </tbody>
                </table>"
            ]);
            return;
        }


        $trialDataQuery = $this->trialDataModel
            ->select('varieties.short_name, trial_data.variable, trial_data.location')
            ->where('trial_data.crop_id', $crop_id)
            ->join('varieties', 'varieties.code = trial_data.variety_code', 'left');

        if (!empty($varieties)) {
            $trialDataQuery->whereIn('trial_data.variety_code', $varieties);
        }

        if (!empty($locations)) {
            $trialDataQuery->whereIn('trial_data.location', $locations);
        }

        $allTrials = $trialDataQuery->findAll();

        if (!empty($orderBy) && $orderBy === $traitName) {
            usort($allTrials, function ($a, $b) use ($orderBy, $orderDir) {
                $aData = json_decode($a['variable'], true);
                $bData = json_decode($b['variable'], true);
                $valA = $aData[$orderBy] ?? 0;
                $valB = $bData[$orderBy] ?? 0;
                return $orderDir === 'desc' ? $valB <=> $valA : $valA <=> $valB;
            });
        }


        $filteredTrials = [];
        foreach ($allTrials as $trial) {
            $jsonData = json_decode($trial['variable'], true);
            $traitVal = $jsonData[$traitName] ?? null;
            if (!empty($traitVal)) {
                $trial['trait_value'] = $traitVal;
                $filteredTrials[] = $trial;
            }
        }

        $totalRecords = count($filteredTrials);
        $paginatedTrials = array_slice($filteredTrials, $offset, $perPage);
        $html = "<table class='table table-bordered table-striped'>
            <thead class='table-light'>
            <tr>
                <th scope='col'>Variety</th>
                <th scope='col'>Location</th>
                <th scope='col' class='sortable' data-field='" . htmlspecialchars($traitName) . "'>
                    <div class='d-flex justify-content-between align-items-center'>
                        <span>" . htmlspecialchars($traitName) . "</span>
                        <span class='sort-icons'>
                            <i class='bi bi-caret-up-fill sort-icon " . (!empty($orderBy) &&  $orderDir === 'asc' ? 'text-primary' : '') . "' data-dir='asc' title='Sort Asc'></i>
                            <i class='bi bi-caret-down-fill sort-icon " . (!empty($orderBy) && $orderDir === 'desc' ? 'text-primary' : '') . "' data-dir='desc' title='Sort Desc'></i>
                        </span>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>";

        // Step 1: Collect trait values for classification
        $traitValues = array_column($filteredTrials, 'trait_value');
        $numericTraitValues = array_filter($traitValues, 'is_numeric');

        // Step 1: Collect and sort numeric trait values
        $traitValues = array_column($filteredTrials, 'trait_value');
        $numericTraitValues = array_filter($traitValues, 'is_numeric');
        sort($numericTraitValues);
        $total = count($numericTraitValues);

        $p10 = getPercentile($numericTraitValues, 10);
        $p30 = getPercentile($numericTraitValues, 30);
        $p70 = getPercentile($numericTraitValues, 70);
        $p90 = getPercentile($numericTraitValues, 90);

        foreach ($paginatedTrials as $value) {
            $traitVal = floatval($value['trait_value']);
            $bgColor = getTraitColorByPercentile($traitVal, $p10, $p30, $p70, $p90);
            $color = $bgColor == '#e8ebed' ? '#7b809a' : 'white';

            $html .= "<tr>
                <td>" . htmlspecialchars($value['short_name']) . "</td>
                <td>" . htmlspecialchars($value['location']) . "</td>
                <td style='background-color: " . $bgColor . "; color:" . $color . ";'>" . htmlspecialchars($value['trait_value']) . "</td>
            </tr>";
        }

        $html .= "</tbody></table>";

        echo json_encode([
            'success' => 1,
            'html' => $html,
            'total_records' => $totalRecords,
            'current_page' => $page,
            'per_page' => $perPage,
            'order_by' => $orderBy,
            'order_dir' => $orderDir
        ]);
        exit;
    }
}
