<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_后台认证模块
 * @Created by MD.Pei
*/
namespace app\admin\controller;
use app\admin\model\Administrator as AdministratorModel;
use think\Controller;
use think\Model;
use think\Request;

//权限认证
class AdminAuth extends Controller {
	protected function _initialize(){
		$request = request();
		//session存在时，不需要验证的权限
		$not_check = array('admin/login','admin/login_action','admin/lostpassword','admin/logout','admin/lost_password');
		//当前操作的请求 模块名/方法名
		if(in_array($request->module().'/'.$request->action(), $not_check) || $request->module() != 'admin'){
			return true;
		}

		//session不存在时，不允许直接访问
		if(!session('uid')){
			//未登陆跳转
			$this->error('还没有登录，正在跳转到登录页','/admin/login');
		}

		//密码校验
		if(config('auth_password_check')){
			$this->auth_password_check();
		}

		//过期时间校验
		if(config('auth_expired_check')){
			$this->auth_expired_check();
	    }
	}

	//密码校验
	protected function auth_password_check(){
		$user = new AdministratorModel;
		$where_query = array(
                'username' => session('admin_username'),
                'password' => session('admin_password'),
                'status'   => 1
            );
		$user = $user->where($where_query)->find();
        if (!$user) {
        	//注销当前账号
        	session(null, 'think');

            $this->error('登录失效:用户密码已更改','/admin/login');
        }
	}

	//session超时校验
	protected function auth_expired_check(){
		$user = new AdministratorModel;
		$where_query = array(
                'username' => session('admin_username'),
                'password' => session('admin_password'),
                'status'   => 1
            );
		$user = $user->where($where_query)->find();
        if ((time() > strtotime($user->expire_time))) { //登录超时
        	//注销当前账号
        	session(null, 'think');
            $this->error('账号已过期','/admin/login');
        }
	}

	//管理员权限查询
	protected function admin_permission(){
		$user = new AdministratorModel;
		if(session('uid')){
			$where_query = array(
            	'username' => session('admin_username'),
            	'password' => session('admin_password'),
            	'status'   => 1
        	);
			$user = $user->where($where_query)->find();
			$permission = $user->permission;
			if($permission==='管理员'){
				$permission = 0;
			}elseif ($permission==='高级管理员') {
				$permission = 1;
			}
		}else{
			$permission = 0;
		}
        return $permission;
	}

	//获取当前登录名
	protected function admin_info(){
		$user = new AdministratorModel;
		if(session('uid')){
			$where_query = array(
            	'username' => session('admin_username'),
            	'password' => session('admin_password'),
        	);
			$user = $user->where($where_query)->find();
		}
		$login_name = $user->username;
        return $login_name;
	}

}