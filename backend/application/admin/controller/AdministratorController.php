<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_管理员控制器
 * @Created by MD.Pei
*/
namespace app\admin\controller;
use app\admin\model\Administrator;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;

class AdministratorController extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '管理员管理',
        'module_title' => '管理员',
		'module_url'  => '/admin/administrator/',
		'module_slug' => 'administrator',
		'upload_path' => UPLOAD_PATH,
		'upload_url'  => '/public/uploads/',
		);

    //获取管理员列表
    public function index(){
        $list =  Administrator::where('status','>=','-1')->order('id', 'ASC')->paginate();
        $this->data['permission'] = parent::admin_permission();
        $this->assign('data',$this->data);
        $this->assign('list',$list);
        return $this->fetch();
    }

    //新增管理员页面
    public function create(){
        $this->data['permission'] = parent::admin_permission();
    	$this->data['edit_fields'] = array(
			'username' => array('type' => 'text', 'label'     => '用户名'),
			'password' => array('type' => 'password', 'label' => '密码'),
			'tel'   => array('type' => 'text', 'label'     => '分机号'),
			'status'   => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '禁用', 1 => '正常')),
            'permission'   => array('type' => 'radio', 'label' => '管理员权限','default'=> array(0 => '管理员', 1 => '高级管理员')),
        );

        //默认值设置
        $item['status'] = '正常';
        $item['permission'] = '管理员';
        $item['salt'] = rand(100,999);
        $item['last_login_time'] = 0;
        $item['expire_time'] = 0;

        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();
    }

    //新增管理员操作
    public function add(){
        $user = new Administrator;
        $data = input('post.');
        $rule = [
            //管理员登陆字段验证
			'username|用户名' => 'require|alphaDash|min:5|unique:administrator',
			'password|密码'   => 'require|min:5',
			'tel|分机号'   => 'length:4',
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        /*
        if(!$result){
            echo '<script>alert("'.$validate->getError().'");</script>';
            exit;
            //return  $validate->getError();
        }
        */
        if($result){
            $data['password'] =  (isset($data['salt']) && $data['salt']) ? md5($data['password'].$data['salt']) : md5($data['password']);
            $data['create_time'] = time();
            $data['update_time'] = time();

            if ($id = $user->validate(true)->insertGetId($data)) {
                $this->redirect($this->data['module_url']);
            } else {
                echo '<script>alert("新增管理员失败");</script>';
            }
        }
    }

    //读取管理员用户信息
    public function read($id='')
    {
        $this->data['permission'] = parent::admin_permission();
        $user = new Administrator;
        $this->data['edit_fields'] = array(
			'username' => array('type' => 'text', 'label'     => '用户名'),
			'password' => array('type' => 'password', 'label' => '密码'),
			'tel'   => array('type' => 'text', 'label'     => '分机号'),
			'status'   => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '禁用', 1 => '正常')),
            'permission'   => array('type' => 'radio', 'label' => '管理员权限','default'=> array(0 => '管理员', 1 => '高级管理员')),
        );
        //默认值设置
        $default = $user::get($id);
        $item['status'] = $default['status'];
        $item['permission'] = $default['permission'];
        $item['id'] = $default['id'];
        $item['username'] = $default['username'];
        $item['tel'] = $default['tel'];

        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();
    }

    //更新管理员信息
    public function update($id)
    {
        $user = new Administrator;
        $data = input('post.');
        $preview = $user->where(array('username'=>$data['username']))->find();

        $rule = [
            //管理员登陆字段验证
			'username|用户名' => 'require|alphaDash|min:5|unique:administrator,username,'.$id.',id', //更新时用户名唯一，但排除当前ID
			'password|密码'   => 'require|min:5',
            'tel|分机号'   => 'require|length:4',
        ];
        $msg = [];

        //当修改密码时,新生成一个加密盐
        $data['salt'] = rand(100,999);
        // 数据验证
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }
        if(input('password')){
	        $data['password'] =  (isset($data['salt']) && $data['salt']) ? md5($data['password'].$data['salt']) : md5($data['password']);
	    }else{
	    	unset($data['password']);
	    }
        $data['id'] = $id;
        if ($user->update($data)) {
            $this->redirect($this->data['module_url']);
        } else {
            return $user->getError();
        }
    }

    //删除管理员账号(非物理删除)
    public function delete($id)
    {
        $user = new Administrator;
        $data['id'] = $id;
        $data['status'] = -1;
        if ($user->update($data)) {
        	$data['error'] = 0;
        	$data['msg'] = '删除成功';
        } else {
        	$data['error'] = 1;
        	$data['msg'] = '删除失败';
        }
        return $data;
    }

    //禁用管理员账号
    public function block($id)
    {
        $user = new Administrator;
        $data['id'] = $id;
        $data['status'] = 0;
        if ($user->update($data)) {
            $data['error'] = 0;
            $data['msg'] = '禁用成功';
        } else {
            $data['error'] = 1;
            $data['msg'] = '禁用失败';
        }
        return $data;
    }
}