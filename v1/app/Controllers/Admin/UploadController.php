<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\State;
use App\Models\Upload;

class UploadController extends BaseController
{
    private $uploadModel, $stateModel;
    public function __construct()
    {
        $this->uploadModel = new Upload();
        $this->stateModel = new State();
    }
    public function index()
    {
        $states = $this->stateModel->findAll();
        $uploads = $this->uploadModel->select('uploads.*,crops.name as crop')->join('crops', 'crops.id=uploads.crop_id')->where('crops.status', 1)->find();
        return \view('backend/uploads/index', \compact('uploads', 'states'));
    }

    public function store()
    {
        $validate = $this->validate([
            'crop_id' => 'required',
            'year' => 'required|exact_length[4]',
            'state_code' => 'required',
            'name' => 'required',
            'upload' => 'uploaded[upload]|ext_in[upload,pdf]'
        ]);

        $url = Helpers::uploadFile($_FILES['upload'], uniqid(), 'crop');
        $data = [
            'crop_id' => $this->request->getPost('crop_id'),
            'year' => $this->request->getPost('year'),
            'state_code' => $this->request->getPost('state_code'),
            'url' => $url,
            'title' => $this->request->getPost('title')
        ];
        $this->uploadModel->insert($data);

        return \redirect()->back()->with('success', 'PDF uploaded successfully');
    }
    public function delete($id)
    {
        $upload = $this->uploadModel->find($id);
        Helpers::deleteFile(FCPATH . $upload['url']);
        $this->uploadModel->delete($id);
        return \redirect()->back()->with('success', 'PDF deleted successfully');
    }
}
