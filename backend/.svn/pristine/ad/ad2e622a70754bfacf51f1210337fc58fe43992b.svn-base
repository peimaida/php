<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_Category控制器
 * @Created by MD.Pei
*/

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Model;
use app\admin\model\Maintenance;

class CategoryController extends Controller{
	//模块基本信息
	private $data = array(
		'module_name' => '商品分类｜MISUMI-VONA｜MISUMI的综合Web产品目录',
        'module_description' => '商品分类',
        'module_keywords' => '商品分类',
		'module_css'  => '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/css/category.css">',//选择每个模块需要加载的css文件
        'module_js'  => '<script type="text/javascript" src="__FRONT_PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__FRONT_PUBLIC__/js/category-json.js"></script>',//选择每个模块需要加载的js文件
	);

	//展示商品分类页面
	public function index(){
		$m_num = maintenance();
		if($m_num==1){
			$this->assign('data',$this->data);
        	return $this->fetch();
        }else{
        	$this->redirect('/maintenance');
        }
	}
}