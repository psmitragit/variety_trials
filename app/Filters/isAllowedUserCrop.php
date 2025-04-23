<?php

namespace App\Filters;

use App\Models\User;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class isAllowedUserCrop implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('admin_data') || empty(session('admin_data'))) {
            return redirect('admin/login');
        } else {
            $loggedin_user = auth_admin();
            if(isset($loggedin_user['type']) && ($loggedin_user['type'] == 0 || $loggedin_user['type'] == 1)){
                if($loggedin_user['type'] == 1){
                    $userModel = new User();
                    $user = $userModel->select('crop')->where('id', $loggedin_user['id'])->find();
                    $user_allowed_crops = isset($user[0]['crop']) ? explode(',', $user[0]['crop']) : [];
                    if(!$user){
                        return \redirect()->back()->with('warning', 'Unauthorized access detected');
                    }
                    $segments = $request->uri->getSegments();
                    $cropId = $segments[2] ?? null;
                    if(!in_array(0, $user_allowed_crops) && !in_array($cropId, $user_allowed_crops)){
                        return \redirect()->back()->with('warning', 'Unauthorized access detected');
                    }
                }
            }else{
                return \redirect()->back()->with('warning', 'Unauthorized access detected');
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
