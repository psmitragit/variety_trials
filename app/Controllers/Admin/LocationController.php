<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\City;
use App\Models\Location;
use App\Models\State;
use League\Csv\Reader;

class LocationController extends BaseController
{
    private $locationModel;

    public function __construct()
    {
        $this->locationModel = new Location;
    }


    public function index()
    {

        $locations = $this->locationModel->where('status', 1);
        !isAllowed() ? $locations->where('user_id', \auth_admin()['id']) : '';
        $locations = $locations->orderBy('code', 'ASC')->findAll();

        return view('backend/location/index', compact('locations'));
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'code'          => 'required|is_unique[locations.code]',
                'location'      => 'required',
                'state_code'    => 'required',
                'city_code'     => 'required',
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();


            $data = [
                'code'          => $this->request->getPost('code'),
                'location'      => $this->request->getPost('location'),
                'state_code'    => $this->request->getPost('state_code'),
                'city_code'     => $this->request->getPost('city_code'),
                'farm'          => $this->request->getPost('farm'),
                'lat'           => $this->request->getPost('lat'),
                'long'          => $this->request->getPost('long'),
                'soil_type'     => $this->request->getPost('soil_type'),
                'irrigation'    => $this->request->getPost('irrigation'),
                'user_id'       => \auth_admin()['id']
            ];
            $this->locationModel->insert($data);

            return \redirect('admin/location')->with('success', 'Lacation added successfully');
        } else {
            $states = Helpers::getStates(\auth_admin()['state']);
            $cities = [];
            return view('backend/location/create', compact('states', 'cities'));
        }
    }
    public function edit($id)
    {
        $unauthorize = $this->isAuthorize($id);
        if ($unauthorize) return \redirect('admin/location')->with('warning', 'Unauthorized access detected!');




        $location = $this->locationModel->find($id);

        if ($this->request->is('post')) {
            $validate = $this->validate([
                'id'            => 'required|is_natural_no_zero',
                'code'          => 'required|is_unique[locations.code,id,{id}]',
                'location'      => 'required',
                'state_code'    => 'required',
                'city_code'     => 'required',
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();


            $data = [
                'code'          => $this->request->getPost('code'),
                'location'      => $this->request->getPost('location'),
                'state_code'    => $this->request->getPost('state_code'),
                'city_code'     => $this->request->getPost('city_code'),
                'farm'          => $this->request->getPost('farm'),
                'lat'           => $this->request->getPost('lat'),
                'long'          => $this->request->getPost('long'),
                'soil_type'     => $this->request->getPost('soil_type'),
                'irrigation'    => $this->request->getPost('irrigation'),
            ];
            $id = $this->request->getPost('id');
            $this->locationModel->update($id, $data);

            return \redirect('admin/location')->with('success', 'Lacation added successfully');
        } else {
            $states = Helpers::getStates(\auth_admin()['state']);
            $cities = Helpers::getCities($location['state_code']);
            return view('backend/location/create', \compact('location', 'states', 'cities'));
        }
    }

    public function delete($id)
    {
        $unauthorize = $this->isAuthorize($id);
        if ($unauthorize) return \redirect('admin/location')->with('warning', 'Unauthorized access detected!');
        $this->locationModel->delete($id);
        return \redirect('admin/location')->with('success', 'Lacation deleted successfully');
    }

    public function bulkInsert()
    {
        $validate = $this->validate(['bulk_file' => 'uploaded[bulk_file]|ext_in[bulk_file,csv,xlsx]']);
        if (!$validate) {
            return redirect()->back()->with('error', $this->validator->getError('bulk_file'));
        }

        $filePath = $_FILES['bulk_file']['tmp_name'];
        $csv = Reader::createFromPath($filePath);
        $expectedHeaders = ['LocID', 'Location', 'Management', 'City or County', 'State', 'lat', 'long', 'soil type', 'irrigation'];
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


            $stateModel = new State();
            $cityModel = new City();
            $state = $stateModel->where('code', trim($record[4]))->first();
            if ($state) {
                $stateId = $state['id'];
            } else {
                $stateId = $stateModel->insert(['code' => trim($record[4])]);
            }
            if (!$cityModel->where('code', trim($record[3]))->first()) {
                $cityModel->insert(['code' => trim($record[3]), 'state_id' => $stateId]);
            }
            $data = [
                'code'          => trim($record[0]),
                'location'      => trim($record[1]),
                'farm'          => trim($record[2]),
                'city_code'     => trim($record[3]),
                'state_code'    => trim($record[4]),
                'lat'           => str_replace(['?', '/'], ['°', ''], mb_convert_encoding($record[5], 'UTF-8')),
                'long'          => str_replace(['?', '/'], ['°', ''], mb_convert_encoding($record[6], 'UTF-8')),
                'soil_type'     => trim($record[7]),
                'irrigation'    => trim($record[8]),
                'user_id'       => \auth_admin()['id']
            ];

            if ($location = $this->locationModel->where('code', trim($record[0]))->first()) {
                $this->locationModel->update($location['id'], $data);
            } else {
                $this->locationModel->insert($data);
            }
        }
        return \redirect()->back()->with('success', 'Data imported successfully');
    }

    public function getCity()
    {
        if ($this->request->isAJAX() && $this->request->is('post')) {
            $stateCode = $this->request->getPost('state');
            $cities = Helpers::getCities($stateCode);
            return \response()->setJSON(['status' => true, 'data' => $cities, 'hash' => \csrf_hash()]);
        }
        return \response()->setJSON(['status' => false, 'hash' => \csrf_hash()]);
    }

    public function getSingle()
    {
        if ($this->request->isAJAX() && $this->request->is('post')) {
            $locid = $this->request->getPost('id');

            $location = $this->locationModel->where('code', $locid)->first();
            return \response()->setJSON(['status' => true, 'data' => $location, 'hash' => \csrf_hash()]);
        }
        return \response()->setJSON(['status' => false, 'hash' => \csrf_hash()]);
    }


    public function isAuthorize($id)
    {
        $location = $this->locationModel->find($id);
        $unauthorize = \false;

        if (empty($location)) {
            $unauthorize = true;
        } else if (!isAllowed()) {
            $unauthorize = \auth_admin()['id'] != $location['user_id'] ? true : $unauthorize;
        }
        return $unauthorize;
    }

    public function getLocationsByState()
    {
        $state = $this->request->getPost('state');
        $locations = $this->locationModel->where('state_code', $state)->findAll();
        return \response()->setJSON(['status' => true, 'locations' => $locations]);
    }
}
