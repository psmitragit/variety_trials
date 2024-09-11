<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\State;

class StateController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new State();
    }


    public function index()
    {
        $states = $this->model->findAll();
        return \view('backend/state/index', compact('states'));
    }


    public function save()
    {
        $validate = $this->validate([
            'id' => 'required',
            'name' => 'required',
            'code' => 'required|is_unique[states.code,id,{id}]',
        ]);
        if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();
        $data['name'] = $this->request->getPost('name');
        $data['code'] = $this->request->getPost('code');
        $id = $this->request->getPost('id');

        if ($id) {
            $this->model->update($id, $data);
            $message = "State Updated";
        } else {
            $this->model->insert($data);
            $message = "State Created";
        }
        return \redirect()->back()->with('success', $message);
    }


    public function destroy($id)
    {
        $this->model->delete($id);
        return \redirect()->back()->with('success', 'State Deleted');
    }
}
