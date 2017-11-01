<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_维护页面控制器
 * @Created by MD.Pei
*/

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Maintenance;

class MaintenanceController extends Controller{
	public function index(){
		$m = new Maintenance;
		$m_query = array('useless'=>0);
       	$m_result = $m->where($m_query)->find();
		$this->assign('start_time',$m_result['start_time']);
		$this->assign('end_time',$m_result['end_time']);

		return $this->fetch();
	}
}