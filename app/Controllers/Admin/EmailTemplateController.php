<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailTemplate;

class EmailTemplateController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new EmailTemplate();
    }
    public function index()
    {
        $templates = $this->model->findAll();
        return view('backend/email/index', compact('templates'));
    }

    public function create($id = 0)
    {
        $template = $id ? $this->model->find($id) : [];

        if ($this->request->is('post')) {
            $validate = $this->validate([
                'id'        => 'required',
                'name'      => 'required',
                'subject'   => 'required',
                'code'      => 'required|is_unique[email_templates.code,id,{id}]',
            ]);

            if (!$validate) return redirect()->back()->withInput();

            $data = [
                'name'          => $this->request->getPost('name'),
                'code'          => $this->request->getPost('code'),
                'content'       => $this->request->getPost('content'),
                'placeholder'   => $this->request->getPost('placeholder'),
                'subject'       => $this->request->getPost('subject'),
            ];

            if (!empty($template)) {
                $this->model->update($id, $data);
                $message = "EmailTemplate Updated!";
            } else {
                $this->model->insert($data);
                $message = "New EmailTemplate Added!";
            }

            return redirect('admin/email-template')->with('success', $message);
        } else {
            return view('backend/email/create', compact('template'));
        }
    }

    public function destroy($id)
    {
        $this->model->where('id', $id)->delete();
        return \redirect()->back()->with('success', 'Email template deleted successfully');
    }
}
