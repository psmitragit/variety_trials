<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\City;

class CityController extends BaseController
{
    private $model;
    public function  __construct()
    {
        $this->model = new City();
    }
    public function index()
    {
        $cities = $this->model->select('cities.*,states.name as state_name,states.code as state_code')->join('states', 'cities.state_id=states.id')->findAll();
        return \view('backend/city/index', \compact('cities'));
    }

    public function save()
    {
        $validate = $this->validate([
            'id' => 'required',
            'state_id' => 'required',
            'code' => 'required|is_unique[states.code,id,{id}]',
        ]);
        if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();
        $data['state_id'] = $this->request->getPost('state_id');
        $data['code'] = $this->request->getPost('code');
        $id = $this->request->getPost('id');

        if ($id) {
            $this->model->update($id, $data);
            $message = "City Updated";
        } else {
            $this->model->insert($data);
            $message = "City Created";
        }
        return \redirect()->back()->with('success', $message);
    }


    public function destroy($id)
    {
        $this->model->delete($id);
        return \redirect()->back()->with('success', 'City Deleted');
    }
}
