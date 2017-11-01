<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_Topic控制器
 * @Created by MD.Pei
*/

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Model;
use app\admin\model\Events;
use app\admin\model\Author;
use app\admin\model\Tags;
use app\admin\model\Maintenance;

class TopicController extends Controller{
	//模块基本信息
	private $data = array(
		'module_name' => '活动特辑｜MISUMI-VONA｜MISUMI的综合Web产品目录',
		'module_description' => '活动特辑',
        'module_keywords' => '活动特辑',
		'module_url'  => '/index/topic/detail/',
		'module_css'  => '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/css/topicStyle.css">',//选择每个模块需要加载的css文件
		'module_js'  => '<script type="text/javascript" src="__FRONT_PUBLIC__/js/jquery.js"></script>',//选择每个模块需要加载的js文件
	);

	//展示TOPIC板块首页
	public function index(){
        $m_num = maintenance();
        if($m_num==1){
            $sql = "select * from backend_events where status = 1 and show_flag = 1 order by start_time desc";
            $list = Db::query($sql);

 	        $this->assign('data',$this->data);
            $this->assign('list',$list);
            return $this->fetch();
        }else{
            $this->redirect('/maintenance');
        }
	}

	//TOPIC详情页面
	public function detail($id=''){
        $m_num = maintenance();
        if($m_num==1){
            unset($this->data['module_css']);
            $this->data['module_css'] = '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/css/tn-detail.css">';

            //查询当前新闻的作者ID
            $author_id = Events::where('id',$id)->column('event_author');
            //获取当前新闻作者名
            $author_name = Author::where('id',$author_id[0])->column('author');
            $tmp_name = $author_name[0];
            //查询相关活动
            $link_sql = "select * from backend_events where status = 1 and show_flag = 1 and id<> ".$id." order by start_time desc limit ".config('detail_event_num');
            $links_result = Db::query($link_sql);
            //查询前一篇和后一篇活动
            $item = Events::get($id);
            $origin = "select e.id from backend_events as e where e.status = 1 and e.show_flag = 1 order by e.start_time desc";
            $origin_result = Db::query($origin);
            $origin_length = count($origin_result);
            $item['last_cnt'] = 0;
            $item['next_cnt'] = 0;
            $next_key = 0;
            foreach($origin_result as $key=>$value){
                if($value['id']==$id){
                    if($key==0){//第一条活动
                        $item['last_cnt'] = 0;
                        if($origin_length==1){//只有一条活动
                            $item['next_cnt'] = 0;
                        }else{
                            $item['next_cnt'] = 1;
                            $tmp = Events::get($origin_result[1]['id']);
                            $item['next_id'] = $origin_result[1]['id'];
                            $item['next_event_title'] = $tmp['event_title'];
                        }
                    }else{//第二条开始的活动
                        $item['last_cnt'] = 1;
                        $tmp = Events::get($origin_result[$key-1]['id']);
                        $item['last_id'] = $origin_result[$key-1]['id'];
                        $item['last_event_title'] = $tmp['event_title'];
                        if($key!=($origin_length-1)){
                            $item['next_cnt'] = 1;
                            $tmp = Events::get($origin_result[$key+1]['id']);
                            $item['next_id'] = $origin_result[$key+1]['id'];
                            $item['next_event_title'] = $tmp['event_title'];
                        }else{
                            $item['next_cnt'] = 0;
                        }
                    }
                }
            }
            $item['author_name']  =  $tmp_name;
        
            $this->assign('data',$this->data);
            $this->assign('item',$item);
            $this->assign('list',$links_result);
        return view();
        }else{
            $this->redirect('/maintenance');
        }
	}
}