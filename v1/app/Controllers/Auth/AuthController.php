<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\User;
use CodeIgniter\Config\Services;

use function PHPUnit\Framework\returnSelf;

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
            $validate = $this->validate([
                'email' => 'required|trim',
                'password' => 'required|trim|max_length[16]|min_length[8]'
            ]);

            if (!$validate) {
                return view('backend/auth/login');
            }

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $user = $this->userModel->where(['username' => $email])->whereIn('type', [0, 1])->first();
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
                $subject = "Forgot Password";
                $message = "Dear " . $user['name'] . ",<br/><br/>";
                $message .= "Please <a style='color:#e4316f;text-decoration:underline;' href='" . \base_url('forgot-password/' . $hash) . "'>Click Here</a> to set a new password.<br/><br/>";
                $message .= "N.B: This link valid for next 30 minutes.<br/><br/>";
                $message .= "---- VarietyTrials";

                Helpers::sendEmail($user['email'], $subject, $message);
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
                $validate = $this->validate(['password' => 'required|max_length[16]|min_length[8]', 'conf_password' => "required|matches[password]"]);
                if (!$validate) return \redirect()->back()->withInput();
                $this->userModel->update($userId, ['password' => \password_hash($this->request->getPost('password'), \PASSWORD_BCRYPT)]);
                return \redirect('admin/login')->with('success', "Password changed. Plesae login");
            } else {
                return view('backend/auth/change_password');
            }
        }
    }
}
