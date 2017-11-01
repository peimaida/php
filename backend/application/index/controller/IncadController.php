<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_inCAD控制器
 * @Created by MD.Pei
*/

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Model;
use app\admin\model\Maintenance;

class IncadController extends Controller{
	//模块基本信息
	private $data = array(
		'module_url'  => '/incad/detail/',
		'module_name' => 'inCAD｜MISUMI-VONA｜MISUMI的综合Web产品目录',
        'module_description' => 'inCAD',
        'module_keywords' => 'inCAD',
		'module_css'  => '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/css/incadList.css">',//选择每个模块需要加载的css文件
        'module_js'  => '<script type="text/javascript" src="__FRONT_PUBLIC__/js/jquery.js"></script>',//选择每个模块需要加载的js文件
	);

	//展示inCAD页面
	public function index(){
		$m_num = maintenance();
		if($m_num==1){
	 	    $this->assign('data',$this->data);
    	    return $this->fetch();
		}else{
			$this->redirect('/maintenance');
		}
	}

	//展示000288页面
	public function page_000288(){
		$m_num = maintenance();
		if($m_num==1){
			unset($this->data['module_css']);
			$this->data['module_download_url'] = '/incad/download/';
			$this->data['module_css'] = '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/incad_detail/000288/css/style.css">';
 	    	$this->assign('data',$this->data);
        	return view();
    	}else{
    		$this->redirect('/maintenance');
    	}	
	}

	//展示000288下载页面
	public function download_000288(){
		$m_num = maintenance();
		if($m_num==1){
			unset($this->data);
        	$this->view->engine->layout(false);
        	return view();
    	}else{
    		$this->redirect('/maintenance');
    	}
	}

	//展示000223页面
	public function page_000223(){
		$m_num = maintenance();
		if($m_num==1){
			unset($this->data['module_css']);
			$this->data['module_download_url'] = '/incad/download/';
			$this->data['module_css'] = '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/incad_detail/000288/css/style.css">';
 	    	$this->assign('data',$this->data);
        	return view();
    	}else{
    		$this->redirect('/maintenance');
    	}
	}

	//展示000223下载页面
	public function download_000223(){
		$m_num = maintenance();
		if($m_num==1){
			unset($this->data);
        	$this->view->engine->layout(false);
        	return view();
    	}else{
    		$this->redirect('/maintenance');
    	}
	}

	//展示000016页面
	public function page_000016(){
		$m_num = maintenance();
		if($m_num==1){
			unset($this->data['module_css']);
			$this->data['module_download_url'] = '/incad/download/';
			$this->data['module_css'] = '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/incad_detail/000288/css/style.css">';
 	    	$this->assign('data',$this->data);
        	return view();
    	}else{
    		$this->redirect('/maintenance');
    	}
	}

	//展示000016下载页面
	public function download_000016(){
		$m_num = maintenance();
		if($m_num==1){
			unset($this->data);
        	$this->view->engine->layout(false);
        	return view();
    	}else{
    		$this->redirect('/maintenance');
    	}
	}
}