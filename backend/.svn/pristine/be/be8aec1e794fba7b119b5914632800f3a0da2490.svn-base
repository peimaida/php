<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_后台入口控制器
 * @Created by MD.Pei
*/
namespace app\admin\controller;
use app\admin\model\Administrator;
use app\admin\model\Posts;
use app\admin\model\Events;
use app\admin\model\Banners;
use app\admin\model\Maintenance;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Request;
use think\Db;

class IndexController extends AdminAuth{
   
    //后台登录页面
    public function login(){
        //$_SERVER["REMOTE_ADDR"]='192.168.0.12';
        //开启IP限制访问
        if(config('IP_limit_toggle')){
            foreach(config('IP_limit') as $value){
                if(strcmp($value,$_SERVER["REMOTE_ADDR"])==0){
                    //不使用默认layout模板，使用view下html页面
                    $this->view->engine->layout(false);
                    return view();
                }     
            }
            echo '您无权访问该页面';exit;
        }else{//不开启IP限制访问
            $this->view->engine->layout(false);
            return view();
        }
    }

    //登录验证
    public function login_action(){
        $user = new Administrator;
        $data = input('post.');
        //获取某个post变量值的两种方法
        //Request::instance()->post('admin_username');
        //input('post.admin_username');
        $rule = [
            //管理员登陆字段验证
            //管理员账号密码长度最小为5
            'admin_username|管理员账号' => 'require|min:5',
            'admin_password|管理员密码' => 'require|min:5',
        ];
        //账户名和密码验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        /*
        if(!$result){
            echo '<script>setTimeout(alert("'.$validate->getError().'"),2000);</script>';
            $this->redirect('/admin/login');
            //return $validate->getError();
        }
        */
        $preview = $user->where(array('username'=>$data['admin_username'],'status'=>1))->find();
        if(!$preview){
            $this->error('用户不存在');
        }

        $where_query = array(
                'username' => $data['admin_username'],
                'password' => (isset($preview['salt']) && $preview['salt']) ? md5($data['admin_password'].$preview['salt']) : md5($data['admin_password']),
                'status'   => 1
            );
        if ($user = $user->where($where_query)->find()) {
            //注册session
            session('uid',$user->id,'think');
            session('admin_username',$user->username,'think');
            session('admin_password',$user->password,'think');

            //更新最后请求IP及时间
            $request = request();
            $ip = $request->ip();
            $time = time();
            $expire_time = time()+config('auth_expired_time');
            $user->where($where_query)->update(['last_login_ip' => $ip, 'last_login_time' => $time,'expire_time'=>$expire_time]);
            $this->redirect('/admin');
        } else {
            $this->error('登录失败:账号或密码错误');
        }
    }

    //进入后台首页
    public function index(){
        $this->data['admin_count'] = Administrator::where('status','>=',-1)->count();
        $this->data['post_count_all'] = Posts::where('status','>=',-1)->count();
        $this->data['event_count_all'] = Events::where('status','>=',-1)->count();
        $this->data['banner_count_all'] = Banners::where('status','>=',-1)->count();
        $this->data['permission'] = parent::admin_permission();
        $this->assign('data',$this->data);
        return $this->fetch();
    }

    //忘记密码页面
    public function lost_password(){
        //$_SERVER["REMOTE_ADDR"]='192.168.0.12';
        //开启IP限制访问
        if(config('IP_limit_toggle')){
            foreach(config('IP_limit') as $value){
                if(strcmp($value,$_SERVER["REMOTE_ADDR"])==0){
                    $this->view->engine->layout(false);
                    return view();
                }
            }
            echo '您无权访问该页面';exit;
        }else{//不开启IP限制访问
            $this->view->engine->layout(false);
            return view();
        }
    }

    //退出操作
    public function logout(){
        $request = request();
        session(null, 'think');//清除session
        $this->redirect('/admin/login');
    }
}