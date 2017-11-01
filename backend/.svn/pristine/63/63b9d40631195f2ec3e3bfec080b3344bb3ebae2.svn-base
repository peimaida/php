<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_前台首页控制器
 * @Created by MD.Pei
*/

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Posts;
use app\admin\model\Author;
use app\admin\model\Tags;
use app\admin\model\Events;
use app\admin\model\Banners;
use app\admin\model\Maintenance;

class IndexController extends Controller{
	//模块基本信息
	private $data = array(
		'module_name' => 'MiSUMi-VONA 工厂一站式采购平台',
		'module_description' => 'MiSUMi-VONA 工厂一站式采购平台',
        'module_keywords' => 'MiSUMi-VONA 工厂一站式采购平台',
		'module_url'  => '/index/',
		'module_css'  => '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/css/layout.css">',//选择每个模块需要加载的css文件
		'module_js'  => '<script type="text/javascript" src="__FRONT_PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__FRONT_PUBLIC__/js/touchslider.js"></script>
<script type="text/javascript" src="__FRONT_PUBLIC__/js/topjs.js"></script>',//选择每个模块需要加载的js文件
	);

	//前台首页
	public function index(){
		$m_num = maintenance();
		if($m_num==1){
			//查询活动列表
			$events_sql = "select * from backend_events where status = 1 and show_flag = 1 order by start_time desc limit ".config('top_event_num');
			$events_result = Db::query($events_sql);
			//查询新闻列表
			$news_sql = "select * from backend_posts where status = 1 and show_flag = 1 order by start_time desc limit ".config('top_post_num');
			$news_result = Db::query($news_sql);
			//查询Banner列表
			$banners_sql = "select * from backend_banners where status = 1 and show_flag = 1 order by start_time desc limit ".config('top_banner_num');
			$banners_result = Db::query($banners_sql);
			$banners_num = count($banners_result);
			
  	    	$this->assign('data',$this->data);
  	    	$this->assign('events_result',$events_result);
  	    	$this->assign('news_result',$news_result);
  	    	$this->assign('banners_result',$banners_result);
  	    	$this->assign('tag_name',config('tags'));
  	    	$this->assign('banners_num',$banners_num);
  	    	return $this->fetch();
		}else{
			$this->redirect('/maintenance');
		}
	}
}