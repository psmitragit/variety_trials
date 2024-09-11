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
    public function index()
    {
        $locationModel = new Location();
        $locations = $locationModel->where('status', 1)->orderBy('code', 'ASC')->findAll();
        return view('backend/location/index', compact('locations'));
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'code' => 'required|is_unique[locations.code]',
                'location' => 'required',
                'state_code' => 'required',
                'city_code' => 'required',
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();

            $locationModel = new Location();
            $data = [
                'code' => $this->request->getPost('code'),
                'location' => $this->request->getPost('location'),
                'state_code' => $this->request->getPost('state_code'),
                'city_code' => $this->request->getPost('city_code'),
                'farm' => $this->request->getPost('farm'),
                'lat' => $this->request->getPost('lat'),
                'long' => $this->request->getPost('long'),
                'soil_type' => $this->request->getPost('soil_type'),
            ];
            $locationModel->insert($data);

            return \redirect('admin/location')->with('success', 'Lacation added successfully');
        } else {
            $states = Helpers::getStates();
            $cities = [];
            return view('backend/location/create', compact('states', 'cities'));
        }
    }
    public function edit($id)
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'id' => 'required|is_natural_no_zero',
                'code' => 'required|is_unique[locations.code,id,{id}]',
                'location' => 'required',
                'state_code' => 'required',
                'city_code' => 'required',
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();

            $locationModel = new Location();
            $data = [
                'code' => $this->request->getPost('code'),
                'location' => $this->request->getPost('location'),
                'state_code' => $this->request->getPost('state_code'),
                'city_code' => $this->request->getPost('city_code'),
                'farm' => $this->request->getPost('farm'),
                'lat' => $this->request->getPost('lat'),
                'long' => $this->request->getPost('long'),
                'soil_type' => $this->request->getPost('soil_type'),
            ];
            $id = $this->request->getPost('id');
            $locationModel->update($id, $data);

            return \redirect('admin/location')->with('success', 'Lacation added successfully');
        } else {
            $locationModel = new Location();
            $location = $locationModel->find($id);
            $states = Helpers::getStates();
            $cities = Helpers::getCities($location['state_code']);
            return view('backend/location/create', \compact('location', 'states', 'cities'));
        }
    }

    public function delete($id)
    {
        $locationModel = new Location();
        $locationModel->delete($id);
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
        $expectedHeaders = ['LocID', 'Location', 'Station / Farm', 'City or County', 'State', 'lat', 'long', 'soil type', 'irrigation (y/n)'];
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

            $locationModel = new Location();
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
                'code' => trim($record[0]),
                'location' => trim($record[1]),
                'farm' => trim($record[2]),
                'city_code' => trim($record[3]),
                'state_code' => trim($record[4]),
                'lat' => str_replace(['?', '/'], ['°', ''], mb_convert_encoding($record[5], 'UTF-8')),
                'long' => str_replace(['?', '/'], ['°', ''], mb_convert_encoding($record[6], 'UTF-8')),
                'soil_type' => trim($record[7]),
                'irrigation' => trim($record[8]),
            ];

            if ($location = $locationModel->where('code', trim($record[0]))->first()) {
                $locationModel->update($location['id'], $data);
            } else {
                $locationModel->insert($data);
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
            $locationModel = new Location();
            $location = $locationModel->where('code', $locid)->first();
            return \response()->setJSON(['status' => true, 'data' => $location, 'hash' => \csrf_hash()]);
        }
        return \response()->setJSON(['status' => false, 'hash' => \csrf_hash()]);
    }
}
