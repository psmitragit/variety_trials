<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\User;

class UserController extends BaseController
{
    private $model;
    public function __construct()
    {
        $this->model = new User();
    }
    public function index()
    {
        $users = $this->model->select('users.*,states.code as state_name')->join('states', 'states.id=users.state', 'left')
            ->whereIn('users.type', [0, 1])->where('users.id<>', \auth_admin()['id'])->find();
        return \view('backend/user/index', \compact('users'));
    }

    public function create()
    {
        helper('text');
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'type' => 'required',
                'username' => 'required|is_unique[users.username]',
                'name' => 'required',
                'email' => 'required|is_unique[users.email]',
                'status' => 'required'
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();
            $email = $this->request->getPost('email');
            $name = $this->request->getPost('name');
            $username = $this->request->getPost('username');
            $password = \random_string("alnum", 10);
            $data = [
                'username'  => $username,
                'name'      => $name,
                'email'     => $email,
                'phone'     => $this->request->getPost('phone'),
                'password'  => \password_hash($password, PASSWORD_BCRYPT),
                'type'      => $this->request->getPost('type'),
                'status'    => $this->request->getPost('status'),
                'university' => $this->request->getPost('university') ?? "",
                'state'     => $this->request->getPost('state') ?? "",
                'crop'      => \implode($this->request->getPost('crop') ?? [])
            ];
            $this->model->insert($data);

            Helpers::sendEmail($email, 1, [$name, $username, $password]);

            return \redirect('admin/user')->with('success', 'Moderator added successfully');
        } else {
            return view('backend/user/create');
        }
    }
    public function edit($id)
    {
        $user = $this->model->where(['id' => $id, 'type<' => 2])->first();
        if (empty($user)) {
            return \redirect()->back()->with('error', 'User not found');
        }

        if ($this->request->is('post')) {
            $validate = $this->validate([
                'type' => 'required',
                'id' => 'required',
                'username' => 'required',
                'name' => 'required|is_unique[users.username,id,{id}]',
                'email' => 'required|is_unique[users.email,id,{id}]',
                'status' => 'required'
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();
            $userId = $this->request->getPost('id');
            $username = $this->request->getPost('username');
            $data = [
                'username' => $username,
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'type' => $this->request->getPost('type'),
                'status' => $this->request->getPost('status'),
                'university' => $this->request->getPost('university') ?? "",
                'state'     => $this->request->getPost('state') ?? "",
                'crop'      => \implode(',', $this->request->getPost('crop') ?? [])
            ];

            $this->model->update($userId, $data);

            return \redirect('admin/user')->with('success', 'Moderator updated successfully');
        } else {

            return view('backend/user/create', \compact('user'));
        }
    }
    public function destroy($id)
    {
        $user = $this->model->where(['id' => $id, 'type' => 1])->first();
        if (empty($user)) {
            return \redirect()->back()->with('error', 'User not found');
        }
        $this->model->delete($id);
        return \redirect()->back()->with('success', 'Moderator deleted successfully');
    }

    public function changePassword()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'old' => 'required',
                'new' => 'required|min_length[8]|max_length[16]',
                'confirm' => 'required|matches[new]'
            ]);
            $old = $this->request->getPost('old');
            $password = $this->request->getPost('new');
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();
            $userId = \auth_admin()['id'];
            $user = $this->model->find($userId);
            if ($user && \password_verify($old, $user['password'])) {
                $this->model->update($userId, ['password' => \password_hash($password, \PASSWORD_BCRYPT)]);
                \session()->destroy();
                return \redirect('admin')->with('success', 'Password updated successfully. Please login');
            } else {
                return \redirect()->back()->with('error', 'Old Password is wrong');
            }
        } else {
            return view('backend/user/change_password');
        }
    }
}
