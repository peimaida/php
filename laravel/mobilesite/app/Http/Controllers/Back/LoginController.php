<?php

namespace App\Http\Controllers\Back;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    /**
     * 展示登录首页
     *
     * @param
     * @return
     */
    public function index(Request $request)
    {
        $data = $request->all();
        dd($data);die;
        return view('back.login.index');
    }

    /**
     * 执行登录操作
     *
     * @param  
     * @return
     */
    public function dologin(Request $request)
    {
        $data = $request->all();
        dd($data);
        /*
        if($request->isMethod('post'))
        {
            echo 'test';
            //return view('back.login.index');
        }*/        
    }


    /**
     * 展示给定用户的信息
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }
}