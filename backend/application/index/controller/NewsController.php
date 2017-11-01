<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_News控制器
 * @Created by MD.Pei
*/

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Model;
use app\admin\model\Posts;
use app\admin\model\Author;
use app\admin\model\Tags;
use app\admin\model\Maintenance;

class NewsController extends Controller{
	//模块基本信息
	private $data = array(
		'module_name' => '新闻中心｜MISUMI-VONA｜MISUMI的综合Web产品目录',
        'module_description' => '新闻中心',
        'module_keywords' => '新闻中心',
		'module_url'  => '/index/news/detail/',
		'module_css'  => '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/css/newsStyle.css">',//选择每个模块需要加载的css文件
        'module_js'  => '<script type="text/javascript" src="__FRONT_PUBLIC__/js/jquery.js"></script>',//选择每个模块需要加载的js文件
	);

	//展示News板块首页
	public function index(){
        $m_num = maintenance();
        if($m_num==1){
            $sql = "select * from backend_posts where status = 1 and show_flag = 1 order by start_time desc";
            $list = Db::query($sql);

 	        $this->assign('data',$this->data);
            $this->assign('list',$list);
            return $this->fetch();
        }else{
            $this->redirect('/maintenance');
        }
	}

	//News详情页面
	public function detail($id=''){
        $m_num = maintenance();
        if($m_num==1){
            unset($this->data['module_css']);
            $this->data['module_css'] = '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/css/tn-detail.css">';

            //查询当前新闻的作者ID
            $author_id = Posts::where('id',$id)->column('post_author');
            //获取当前新闻作者名
            $author_name = Author::where('id',$author_id[0])->column('author');
            $tmp_name = $author_name[0];
            //查询当前新闻的标签ID
            $tag_id = Posts::where('id',$id)->column('tag_id');
            //获取当前新闻标签名
            $tag_name = Tags::where('id',$tag_id[0])->column('tag_name');
            $tmp_tag_name = $tag_name[0];
            //查询相关标签
            $link_tag_sql = "select * from backend_posts where status = 1 and show_flag = 1 and tag_id = ".$tag_id[0]." and id<> ".$id." order by start_time desc limit ".config('detail_post_num');
            $tags_result = Db::query($link_tag_sql);
            //查询前一篇和后一篇新闻
            $item = Posts::get($id);
            $origin = "select p.id from backend_posts as p where p.status = 1 and p.show_flag = 1 order by p.start_time desc";
            $origin_result = Db::query($origin);
            $origin_length = count($origin_result);
            $item['last_cnt'] = 0;
            $item['next_cnt'] = 0;
            $next_key = 0;
            foreach($origin_result as $key=>$value){
                if($value['id']==$id){
                    if($key==0){//第一条新闻
                        $item['last_cnt'] = 0;
                        if($origin_length==1){//只有一条新闻
                            $item['next_cnt'] = 0;
                        }else{
                            $item['next_cnt'] = 1;
                            $tmp = Posts::get($origin_result[1]['id']);
                            $item['next_id'] = $origin_result[1]['id'];
                            $item['next_post_title'] = $tmp['post_title'];
                        }
                    }else{//第二条开始的新闻
                        $item['last_cnt'] = 1;
                        $tmp = Posts::get($origin_result[$key-1]['id']);
                        $item['last_id'] = $origin_result[$key-1]['id'];
                        $item['last_post_title'] = $tmp['post_title'];
                        if($key!=($origin_length-1)){
                            $item['next_cnt'] = 1;
                            $tmp = Posts::get($origin_result[$key+1]['id']);
                            $item['next_id'] = $origin_result[$key+1]['id'];
                            $item['next_post_title'] = $tmp['post_title'];
                        }else{
                            $item['next_cnt'] = 0;
                        }
                    }
                }
            }
            $item['author_name']  =  $tmp_name;
            $item['tag_name']  =  $tmp_tag_name;
            $item['tag_id'] = $tag_id[0];

            $this->assign('data',$this->data);
            $this->assign('item',$item);
            $this->assign('list',$tags_result);
            return view();
        }else{
            $this->redirect('/maintenance');
        }
	}
}