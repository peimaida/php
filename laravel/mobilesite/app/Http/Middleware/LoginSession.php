<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use app\Model\Admin as Admin;
use Illuminate\Http\Request;

class LoginSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $request->input('username');
        print_r($data);die;
        // if($request->){
        //     return redirect('admin/login');
        // }

        //session不存在时，不允许直接访问
        if(!isset($_SESSION['uid'])){
            return redirect('admin/login');
        }
        /*
        //密码校验
        if(config('auth_password_check')){
            $this->PasswordCheck();
        }

        //过期时间校验
        if(config('session.lifetime')){
            $this->ExpiredCheck();
        }*/
        return $next($request);
    }
/*
    protected function PasswordCheck()
    {
        //return redirect('admin/login');
    }

    protected function ExpiredCheck()
    {
        //return redirect('admin/login');
    }
*/
}
