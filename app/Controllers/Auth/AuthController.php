<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\User;

class AuthController extends BaseController
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
    public function index()
    {
        //
    }

    /**
     * Get and Post Request for admin Login
     * @return void
     */

    public function adminLogin()
    {
        if (!empty(session('admin_data'))) {
            return redirect('admin');
        }

        if ($this->request->is("get")) {
            return view('backend/auth/login');
        } else {
            $validate = $this->validate(
                [
                    'email' => 'required|trim',
                    'password' => 'required|trim|max_length[32]|min_length[8]'
                ],
                [
                    'email' => ['required' => "The username field is required."]
                ]
            );

            if (!$validate) {
                return view('backend/auth/login');
            }

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $user = $this->userModel->groupStart()->where(['username' => $email])->orWhere('email', $email)->groupEnd()->whereIn('type', [0, 1])->where('status', 1)->first();
            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']);
                session()->set('admin_data', $user);
                return redirect('admin');
            }
            return redirect()->back()->with('error', 'Username or Password is invalid');
        }
    }

    /**
     * Signout User
     */

    public function adminSignOut()
    {
        \session()->destroy();
        return \redirect('admin/login');
    }

    /**
     * Forgot Password
     */

    public function forgot()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate(['email' => 'required|valid_email']);
            if (!$validate) return \redirect()->back()->withInput();
            $user = $this->userModel->where('email', $this->request->getPost('email'))->first();
            if (empty($user)) {
                return redirect()->back()->with('error', 'Email not registered');
            } else {
                $hash = \base64_encode($user['id'] . '_' . date('Y-m-d H:i:s', \strtotime('+30 mins')));
                $hash = \urlencode($hash);

                Helpers::sendEmail($user['email'], 2, [$user['name'], base_url('forgot-password/' . $hash)]);
                return \redirect('admin/login')->with('success', "A 'forgot password' link has been sent to your registered email address.");
            }
        } else {
            return view('backend/auth/forgot');
        }
    }
    /**
     * Chnage Password
     */
    public function changePassword($hash)
    {
        $hash = \urldecode($hash);
        $hash = \base64_decode($hash);
        $hash = \explode('_', $hash);
        $userId = $hash[0];
        $date = $hash[1];
        if ($date < date('Y-m-d H:i:s')) {
            return redirect("admin/login")->with('error', "Link Expired");
        } else {
            if ($this->request->is('post')) {
                $validate = $this->validate(['password' => 'required|max_length[32]|min_length[8]', 'conf_password' => "required|matches[password]"]);
                if (!$validate) return \redirect()->back()->withInput();
                $this->userModel->update($userId, ['password' => \password_hash((string)$this->request->getPost('password'), \PASSWORD_BCRYPT)]);
                return \redirect('admin/login')->with('success', "Password changed. Plesae login");
            } else {
                return view('backend/auth/change_password');
            }
        }
    }

    /**
     * Register new staff
     *
     * @return Response
     */

    public function register()
    {
        if ($this->request->is('post')) {

            $validate = $this->validate([
                'name'          => 'required',
                'email'         => 'required|valid_email|is_unique[users.email]',
                'state'         => 'required',
                'crop'          => 'required',
                'university'    => 'required',
                'password'      => 'required',
            ]);

            if (!$validate) return \redirect()->back()->withInput();
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $username = $this->getUserName($name);
            $password =  trim((string)$this->request->getPost('password'));

            $data = [
                'name'          => $name,
                'username'      => $username,
                'email'         => $email,
                'state'         => $this->request->getPost('state'),
                'crop'          => $this->request->getPost('crop'),
                'university'    => $this->request->getPost('university'),
                'password'      => \password_hash($password, \PASSWORD_BCRYPT),
                'type'          => 1,
                'status'        => 0
            ];

            $this->userModel->insert($data);


            Helpers::sendEmail($email, 1, [$name, $username, $password]);
            return \redirect()->back()->with('success', 'Registration successfull. We forworded your application for verification.');
        } else {
            $states = Helpers::getStates();
            $crops = Helpers::getCrops();
            return \view('frontend/auth/register', \compact('states', 'crops'));
        }
    }

    public function getUserName($string)
    {
        $tempUername = \strtolower(url_title($string, '_'));
        $i = 0;
        do {
            $username = $i ? $tempUername . $i : $tempUername;
            $user = $this->userModel->where('username', $username)->first();
            $i++;
        } while (!empty($user));

        return $username;
    }
}
