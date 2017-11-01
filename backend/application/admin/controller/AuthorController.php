<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_作者控制器
 * @Created by MD.Pei
*/
namespace app\admin\controller;
use app\admin\model\Author;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Request;
class AuthorController extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '作者',
		'module_url'  => '/admin/author/',
		'module_slug' => 'author',
		'upload_path' => UPLOAD_PATH,
		'upload_url'  => '/public/uploads/',
	);

	//获取作者列表
    public function index(){
        $list =  Author::where('status','>=','0')->order('id', 'ASC')->paginate();
        $this->assign('data',$this->data);
        $this->assign('list',$list);
        //return $this->fetch();
    }
}