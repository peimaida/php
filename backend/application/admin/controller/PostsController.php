<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_新闻控制器
 * @Created by MD.Pei
*/
namespace app\admin\controller;
use app\admin\model\Administrator as AdministratorModel;
use app\admin\model\Author;
use app\admin\model\Tags;
use app\admin\model\Posts;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
use think\Db;

class PostsController extends AdminAuth{
	//模块基本信息
	private $data = array(
        'image_delete_flag' => 0,
		'module_name' => '新闻管理',
        'module_title' => '新闻',
		'module_url'  => '/admin/posts/',
		'module_slug' => 'posts',
		'upload_path' => UPLOAD_PATH,
		'upload_url'  => '/public/uploads/',
        'ckeditor'    => array(
            'id'     => 'ckeditor_post_content',
            'config' => array(
                'width'  => "100%",
                'height' => '400px',
                'toolbar'   =>  array(
                    array('Save','NewPage','DocProps','Preview','Templates'),
                    array('Cut','Copy','Paste','-','Undo','Redo','-','Link','Unlink'),
                    array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','RemoveFormat'),
                    array('Styles','Format','Font','FontSize'),
                    array('TextColor','BGColor'),
                )
            )
        ),
	);

    //读取新闻列表
    public function search($id=''){
        $this->data['permission'] = parent::admin_permission();
        $request = request();
        $param = $request->param();
        $sql ='select * from backend_posts where status >= -1';

        $tags = Tags::where('status',1)->column('tag_name','id');
        
        if(!empty($param)){
            $this->data['search'] = $param;
            //搜索标题
            if(!empty($param['title'])){
                $sql .= ' and post_title like "%'.$param['title'].'%"';
            }
            //搜索新闻创建时间
            if(!empty($param['create_time'])){
                $sql .= ' and create_time between '.strtotime($param['create_time']).' and '.strtotime($param['create_time']." +1 day");
            }
            //搜索新闻更新时间
            if(!empty($param['update_time'])){
                $sql .= ' and update_time between '.strtotime($param['update_time']).' and '.strtotime($param['update_time']." +1 day");
            }
            //搜索新闻开始和结束时间
            if(!empty($param['post_start_time']) || !empty($param['post_end_time'])){
                if(!empty($param['post_start_time']) && !empty($param['post_end_time'])){
                    $tmp_start_date = date("Y-m-d",strtotime($param['post_start_time']." -1 day"));
                    $tmp_end_date = date("Y-m-d",strtotime($param['post_end_time']." +1 day"));
                    $tmp1 = date("Y-m-d",strtotime($param['post_start_time']));
                    $tmp2 = date("Y-m-d",strtotime($param['post_end_time']));
                    $sql .= ' and (start_time > '.strtotime($tmp_start_date).' and end_time < '.strtotime($tmp_end_date).' or (start_time <= '.strtotime($tmp1).' and end_time between '.strtotime($tmp1).' and '.strtotime($tmp_end_date).') or (start_time between '.strtotime($tmp1).' and '.strtotime($tmp2).' and end_time >= '.strtotime($tmp2).') or (start_time <= '.strtotime($tmp1).' and end_time >= '.strtotime($tmp2).'))';
                }
                if(!empty($param['post_start_time']) && empty($param['post_end_time'])){
                    $tmp3 = strtotime(date("Y-m-d",strtotime($param['post_start_time']." -1 day")));
                    $sql .= ' and end_time > '.$tmp3;
                }
                if(empty($param['post_start_time']) && !empty($param['post_end_time'])){
                    $tmp4 = strtotime(date("Y-m-d",strtotime($param['post_end_time']." +1 day")));
                    $sql .= ' and start_time < '.$tmp4;
                }
            }
            //搜索新闻标签
            if(isset($param['tag']) && $param['tag']>0){
                $sql .= ' and tag_id = '.$param['tag'];
            }
        }
        $sql_total = $sql.' order by create_time desc';
        $list_total = Db::query($sql_total);
        //总条数
        $total_num = count($list_total);
        //页码数
        if($total_num>=config('per_page')){
            $page_num = ceil($total_num/config('per_page'));
        }else{
            $page_num = 1;
        }
        //前一页后一页
        if($id==1){
            $prev_link = 0;
        }else{
            $prev_link = 1;
        }
        if($id==$page_num){
            $next_link = 0;
        }else{
            $next_link = 1;
        }

        $sql .= ' order by create_time desc limit '.(config('per_page')*$id-10).','.config('per_page');
        $list = Db::query($sql);

        if(!empty($param['title']) || !empty($param['tag']) || !empty($param['create_time']) || !empty($param['update_time']) || !empty($param['post_start_time']) || !empty($param['post_end_time'])){
            $query['title'] = $param['title'];
            $query['tag'] = $param['tag'];
            $query['create_time'] = $param['create_time'];
            $query['update_time'] = $param['update_time'];
            $query['post_start_time'] = $param['post_start_time'];
            $query['post_end_time'] = $param['post_end_time'];
            $query['flag'] = 1;
            $this->assign('query',$query);
        }else{
            $query['flag'] = 0;
            $this->assign('query',$query);
        }

        $this->assign('cur_page',$id);
        $this->assign('prev_link',$prev_link);
        $this->assign('next_link',$next_link);
        $this->assign('total_num',$total_num);
        $this->assign('page_num',$page_num);
        $this->assign('data',$this->data);
        $this->assign('list',$list);
        $this->assign('tags',$tags);
        return $this->fetch();
    }

    //打开新增新闻页面
    public function create()
    {
        $this->data['permission'] = parent::admin_permission();
        //获取当前作者名
        $author = Author::where('status',1)->column('author','id');
        //获取标签
        $tags = Tags::where('status',1)->column('tag_name','id');
        
        $this->data['edit_fields'] = array(
            'post_title'     => array('type' => 'text', 'label' => '标题','notes'=>'请控制字数在16字以内'),
            'title_image'  => array('type' => 'file','label' => '首页封面图片', 'id'=>'title_image'),
            'feature_image'  => array('type' => 'file','label' => '详情页面图片', 'id'=>'feature_image'),
            'post_content'   => array('type' => 'textarea', 'label' => '内容','id'=>'ckeditor_post_content'),
            'post_author'   => array('type' => 'text', 'label' => '作者', 'disabled'=>'true', 'extra'=>array('wrapper'=>'col-sm-4')),
            'start_time'    => array('type' => 'text', 'label' => '新闻公开时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD'),'wrapper'=>'col-sm-3')),
            'end_time'    => array('type' => 'text', 'label' => '新闻关闭时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD'),'wrapper'=>'col-sm-3')),
            'tags'    => array('type' => 'select', 'label' => '标签','default' => $tags, 'extra'=>array('wrapper'=>'col-sm-3')),
            'status'         => array('type' => 'radio', 'label' => '状态','default'=> array(0 => '非公开', 1 => '公开')),
        );

        //默认值设置
        $item['status']  = '非公开';
        $item['post_author']  =  $author[1];//默认作者为【米思米中国】
        $item['post_author_hide'] = $author[1];
        
        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();
    }

    //新增新闻操作
    public function add()
    {
        $posts = new Posts;
        $data = input('post.');

        //获取当前作者名对应ID
        $author_id = Author::where('author',$data['post_author_hide'])->column('id');
        $data['post_author'] = $author_id[0];

        if($_POST['status']){
            $data['start_time'] = strtotime($_POST['start_time']);
            $data['end_time'] = strtotime($_POST['end_time']);
            $rule = [
                'status|状态' => 'require',
                'post_author|新闻作者' => 'require',
                'post_content|新闻内容' => 'require',
                'tags|标签' => 'require',
                'start_time|新闻开始时间' => 'require',
                'end_time|新闻结束时间' => 'require',
            ];
        }else{
            if(empty($data['start_time'])){
                $data['start_time'] = 0;
            }else{
                $data['start_time'] = strtotime($data['start_time']);
            }
            if(empty($data['end_time'])){
                $data['end_time'] = 0;
            }else{
                $data['end_time'] = strtotime($data['end_time']);
            }
            $rule = [
                'status|状态' => 'require',
                'post_author|新闻作者' => 'require',
                'post_content|新闻内容' => 'require',
                'tags|标签' => 'require',
            ];
        }

        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        //获取标签ID
        $data['tag_id'] = $_POST['tags'];
        //保存图片原名
        $data['origin_image_name'] = $_FILES['feature_image']['name'];
        $data['origin_title_image_name'] = $_FILES['title_image']['name'];

        //保存图片信息
        //保存新闻封面图片
        if(is_uploaded_file($_FILES['title_image']['tmp_name'])){  
            if(config('system_toggle')){//设为linux文件系统路径
                $path = ROOT_PATH . 'public' . DS . 'uploads/posts/title_image/';
            }else{//设为windows文件系统路径
                $path = ROOT_PATH . 'public' . DS . '/uploads\posts\title_image\/';
            }
            //重命名文件名
            $title_new_name = explode('.',$_FILES['title_image']['name']);
            $title_new = time().'.'.$title_new_name[count($title_new_name)-1];
            if(!move_uploaded_file($_FILES['title_image']['tmp_name'],$path.$title_new)){
                $this->error('上传首页封面图片失败','/admin/posts/create');
            }else{
                $data['source_title_image'] = $title_new;
            }
        }else{
            $this->error('请选择首页封面图片','/admin/posts/create');
        }

        //保存新闻详情图片
        if(is_uploaded_file($_FILES['feature_image']['tmp_name'])){  
            if(config('system_toggle')){//设为linux文件系统路径
                $path = ROOT_PATH . 'public' . DS . 'uploads/posts/';
            }else{//设为windows文件系统路径
                $path = ROOT_PATH . 'public' . DS . '/uploads\posts\/';
            }
            //重命名文件名
            $new_name = explode('.',$_FILES['feature_image']['name']);
            $new = time().'.'.$new_name[count($new_name)-1];
            if(!move_uploaded_file($_FILES['feature_image']['tmp_name'],$path.$new)){
                $this->error('上传新闻详情页图片失败','/admin/posts/create');
            }else{
                $data['source_image'] = $new;
            }
        }else{
            $this->error('请选择新闻详情页图片','/admin/posts/create');
        }

        //清除数据库中没有的字段
        unset($data['posts_create']);
        unset($data['post_author_hide']);
        unset($data['create_time_hide']);
        unset($data['image_url']);
        unset($data['title_image_url']);
        unset($data['tags']);

        //获取当前登录管理员ID
        $user = new AdministratorModel;
        if(session('uid')){
            $where_query = array(
                'username' => session('admin_username'),
                'password' => session('admin_password'),
            );
            $user = $user->where($where_query)->find();
        }
        $login_admin_id = $user->id;
        $data['post_admin'] = $login_admin_id;
        $data['update_admin'] = $login_admin_id;
        $data['create_time'] = time();
        $data['update_time'] = time();

        if ($id = $posts->validate(true)->insertGetId($data)) {
            $this->redirect($this->data['module_url'].'search/1');
        } else {
            return $this->error($posts->getError());
        }
    }

    //读取新闻信息
    public function read($id='')
    {
        $this->data['permission'] = parent::admin_permission();
        //查询当前新闻的作者ID
        $author_id = Posts::where('id',$id)->column('post_author');
        //获取当前新闻作者名
        $author_name = Author::where('id',$author_id[0])->column('author');
        $tmp_name = $author_name[0];
        //获取当前新闻开始时间和结束时间
        $tmp_start_time = Posts::where('id',$id)->column('start_time');
        $post_start_time = date('Y-m-d',$tmp_start_time[0]);
        $tmp_end_time = Posts::where('id',$id)->column('end_time');
        $post_end_time = date('Y-m-d',$tmp_end_time[0]);
        //获取当前新闻状态
        $tmp_status = Posts::where('id',$id)->column('status');
        $post_status = $tmp_status[0];
        //查询当前新闻的标签ID
        $tag_id = Posts::where('id',$id)->column('tag_id');
        //获取当前新闻标签名
        $tag_name = Tags::where('id',$tag_id[0])->column('tag_name');
        $tmp_tag_name = $tag_name[0];
        //获取标签列表
        $tags = Tags::where('status',1)->column('tag_name','id');
        //获取上传的图片名称
        $tmp_origin_image_name = Posts::where('id',$id)->column('origin_image_name');
        $origin_image_name = $tmp_origin_image_name[0];
        $tmp_origin_title_image_name = Posts::where('id',$id)->column('origin_title_image_name');
        $origin_title_image_name = $tmp_origin_title_image_name[0];
        //获取图片名称
        $tmp_feature_image = Posts::where('id',$id)->column('source_image');
        $feature_image = $tmp_feature_image[0];
        $tmp_title_image = Posts::where('id',$id)->column('source_title_image');
        $title_image = $tmp_title_image[0];

        $this->data['edit_fields'] = array(
            'post_title'     => array('type' => 'text', 'label' => '标题','notes'=>'请控制字数在16字以内'),
            'title_image'  => array('type' => 'file','label' => '首页封面图片', 'id'=>'title_image'),
            'feature_image'  => array('type' => 'file','label' => '详情页面图片', 'id'=>'feature_image'),
            'post_content'   => array('type' => 'textarea', 'label' => '内容','id'=>'ckeditor_post_content'),
            'post_author'   => array('type' => 'text', 'label' => '作者', 'disabled'=>'true','extra'=>array('wrapper'=>'col-sm-4')),
            'start_time'    => array('type' => 'text', 'label' => '新闻公开时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD'),'wrapper'=>'col-sm-3')),
            'end_time'    => array('type' => 'text', 'label' => '新闻关闭时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD'),'wrapper'=>'col-sm-3')),
            'tags'    => array('type' => 'select', 'label' => '标签','default' => $tags, 'extra'=>array('wrapper'=>'col-sm-3')),
            'status'         => array('type' => 'radio', 'label' => '状态','default'=> array(0 => '非公开', 1 => '公开')),
        );

        //默认值设置
        $item = Posts::get($id);
        $item['post_content'] = str_replace('&', '&amp;', $item['post_content']);
        $item['update_time']  =  date('Y-m-d H:i:s');
        $item['update_time_hide'] = date('Y-m-d H:i:s');
        $item['post_author']  =  $tmp_name;
        $item['post_author_hide'] = $tmp_name;
        $item['tags']  =  $tmp_tag_name;
        $item['feature_image'] = 'posts/'.$feature_image;
        $item['title_image'] = 'posts/title_image/'.$title_image;
        $item['start_time'] = '';
        $item['end_time'] = '';
        $item['start_time_hide'] = $post_start_time;
        $item['end_time_hide'] = $post_end_time;
        $item['status_hide'] = $post_status;
        $item['image_hide'] = $origin_image_name;
        $item['title_image_hide'] = $origin_title_image_name;

        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();
    }

    //更新新闻信息
    public function update($id)
    {
        $posts = new Posts;
        $where_query_post = array(
            'id' => $id,
        );
        $post_result = $posts->where($where_query_post)->find();
        
        $data = input('post.');
        $data['tag_id'] = $_POST['tags'];
 
        //获取当前作者名对应ID
        $author_id = Author::where('author',$data['post_author_hide'])->column('id');
        $data['post_author'] = $author_id[0];

        if($_POST['status_hide']==1){
            $data['start_time'] = strtotime($_POST['start_time']);
            $data['end_time'] = strtotime($_POST['end_time']);
            $rule = [
                'status|状态' => 'require',
                'post_author|新闻作者' => 'require',
                'post_content|新闻内容' => 'require',
                'tags|标签' => 'require',
                'start_time|新闻开始时间' => 'require',
                'end_time|活动结束时间' => 'require',
            ];
        }else{
            if(empty($data['start_time'])){
                $data['start_time'] = 0;
            }else{
                $data['start_time'] = strtotime($data['start_time']);
            }
            if(empty($data['end_time'])){
                $data['end_time'] = 0;
            }else{
                $data['end_time'] = strtotime($data['end_time']);
            }
            $rule = [
                'status|状态' => 'require',
                'post_author|新闻作者' => 'require',
                'post_content|新闻内容' => 'require',
                'tags|标签' => 'require',
            ];
        }
        $msg = [];

        // 数据验证
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        $data['id'] = $id;
        $data['origin_image_name'] = $_POST['image_hide'];
        $data['origin_title_image_name'] = $_POST['title_image_hide'];

        //保存图片信息
        //保存新闻封面图片
        if(is_uploaded_file($_FILES['title_image']['tmp_name']) && !empty($_FILES['title_image']['tmp_name'])){  
            if(config('system_toggle')){//设为linux文件系统路径
                $path = ROOT_PATH . 'public' . DS . 'uploads/posts/title_image/';
            }else{//设为windows文件系统路径
                $path = ROOT_PATH . 'public' . DS . '/uploads\posts\title_image\/';
            }
            //重命名文件名
            $title_new_name = explode('.',$_FILES['title_image']['name']);
            $title_new = time().'.'.$title_new_name[count($title_new_name)-1];
            if(!move_uploaded_file($_FILES['title_image']['tmp_name'],$path.$title_new)){
                $this->error('上传首页封面图片失败','/admin/posts/'.$id);
            }else{
                $data['source_title_image'] = $title_new;
            }
            $this->data['image_delete_flag'] = 1;
        }elseif(!is_uploaded_file($_FILES['title_image']['tmp_name']) && empty($_FILES['title_image']['tmp_name']) && !empty($_POST['title_image_hide']) && $_POST['title_image_hide']==$post_result->origin_title_image_name){
                $this->data['image_delete_flag'] = 0;
        }else{
            $this->data['image_delete_flag'] == 0;
            $this->error('请选择首页封面图片','/admin/posts/'.$id);
        }

        //保存新闻详情图片
        if(is_uploaded_file($_FILES['feature_image']['tmp_name']) && !empty($_FILES['feature_image']['tmp_name'])){  
            if(config('system_toggle')){//设为linux文件系统路径
                $path = ROOT_PATH . 'public' . DS . 'uploads/posts/';
            }else{//设为windows文件系统路径
                $path = ROOT_PATH . 'public' . DS . '/uploads\posts\/';
            }
            //重命名文件名
            $new_name = explode('.',$_FILES['feature_image']['name']);
            $new = time().'.'.$new_name[count($new_name)-1];
            if(!move_uploaded_file($_FILES['feature_image']['tmp_name'],$path.$new)){
                $this->error('上传新闻详情页图片失败','/admin/posts/'.$id);
            }else{
                $data['source_image'] = $new;
            }
            $this->data['image_delete_flag'] == 1;
        }elseif(!is_uploaded_file($_FILES['feature_image']['tmp_name']) && empty($_FILES['feature_image']['tmp_name']) && !empty($_POST['image_hide']) && $_POST['image_hide']==$post_result->origin_image_name){
            $this->data['image_delete_flag'] == 0;
        }else{
            $this->data['image_delete_flag'] == 0;
            $this->error('请选择新闻详情页图片','/admin/posts/'.$id);
        }

        //获取当前登录管理员ID
        $user = new AdministratorModel;
        if(session('uid')){
            $where_query = array(
                'username' => session('admin_username'),
                'password' => session('admin_password'),
            );
            $user = $user->where($where_query)->find();
        }
        $login_admin_id = $user->id;
        $data['update_admin'] = $login_admin_id;
        $data['update_time'] = time();
        $data['show_flag'] = 0;
        //清除数据库中没有的字段
        unset($data['posts_update']);
        unset($data['post_author_hide']);
        unset($data['update_time_hide']);
        unset($data['image_url']);
        unset($data['title_image_url']);
        unset($data['tags']);
        unset($data['status_hide']);
        unset($data['image_hide']);
        unset($data['title_image_hide']);
        unset($data['start_time_hide']);
        unset($data['end_time_hide']);

        //删除旧图片
        if($this->data['image_delete_flag']==1){
            $this->delete_image($id);
        }        

        if ($posts->update($data)) {
            $this->redirect($this->data['module_url'].'search/1');
        } else {
            return $posts->getError();
        }
    }

    //删除新闻内容(非物理删除)
    public function delete($id){
        //获取当前登录管理员ID
        $user = new AdministratorModel;
        if(session('uid')){
            $where_query = array(
                'username' => session('admin_username'),
                'password' => session('admin_password'),
            );
            $user = $user->where($where_query)->find();
        }
        $login_admin_id = $user->id;
        $posts = new Posts;
        $data['id'] = $id;
        $data['status'] = -1;
        $data['update_admin'] = $login_admin_id;
        //删除图片文件
        $this->delete_image($id);
        $data['source_image'] = '';
        $data['source_title_image'] = '';
        $data['origin_image_name'] = '';
        $data['origin_title_image_name'] = '';

        if ($posts->update($data)) {
        	$data['error'] = 0;
        	$data['msg'] = '删除成功';
        } else {
        	$data['error'] = 1;
        	$data['msg'] = '删除失败';
        }
        return $data;
    }
    
    //删除图片功能
    public function delete_image($id){
    	$posts = Posts::get($id);
        //删除首页新闻图片
        $source_title_image = $posts->source_title_image;
        if (file_exists($this->data['upload_path'] .'/posts/title_image/'. $source_title_image)) {
            @unlink($this->data['upload_path'] .'/posts/title_image/'. $source_title_image);
        }
        $source_image = $posts->source_image;
        if (file_exists($this->data['upload_path'] .'/posts/'. $source_image)) {
            @unlink($this->data['upload_path'] .'/posts/'. $source_image);
        }
    }
}