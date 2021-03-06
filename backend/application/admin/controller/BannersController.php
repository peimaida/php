<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_Banner图片控制器
 * @Created by MD.Pei
*/
namespace app\admin\controller;
use app\admin\model\Administrator as AdministratorModel;
use app\admin\model\Author;
use app\admin\model\Tags;
use app\admin\model\Banners;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
use think\Db;

class BannersController extends AdminAuth{
	//模块基本信息
	private $data = array(
		'module_name' => 'Banner管理',
        'module_title' => 'Banner',
		'module_url'  => '/admin/banners/',
		'module_slug' => 'banners',
		'upload_path' => UPLOAD_PATH,
		'upload_url'  => '/public/uploads/',
	);

    //读取Banner图片列表
    public function search($id=''){
        $this->data['permission'] = parent::admin_permission();
        $request = request();
        $param = $request->param();
        $sql ='select * from backend_banners where status >= -1';

        if(!empty($param)){
            $this->data['search'] = $param;
            //搜索标题
            if(!empty($param['title'])){
                $sql .= ' and banner_title like "%'.$param['title'].'%"';
            }
            //搜索Banner创建时间
            if(!empty($param['create_time'])){
                $sql .= ' and create_time between '.strtotime($param['create_time']).' and '.strtotime($param['create_time']." +1 day");
            }
            //搜索Banner开始和结束时间
            if(!empty($param['banner_start_time']) || !empty($param['banner_end_time'])){
                if(!empty($param['banner_start_time']) && !empty($param['banner_end_time'])){
                    $tmp_start_date = date("Y-m-d",strtotime($param['banner_start_time']." -1 day"));
                    $tmp_end_date = date("Y-m-d",strtotime($param['banner_end_time']." +1 day"));
                    $tmp1 = date("Y-m-d",strtotime($param['banner_start_time']));
                    $tmp2 = date("Y-m-d",strtotime($param['banner_end_time']));
                    $sql .= ' and (start_time > '.strtotime($tmp_start_date).' and end_time < '.strtotime($tmp_end_date).' or (start_time <= '.strtotime($tmp1).' and end_time between '.strtotime($tmp1).' and '.strtotime($tmp_end_date).') or (start_time between '.strtotime($tmp1).' and '.strtotime($tmp2).' and end_time >= '.strtotime($tmp2).') or (start_time <= '.strtotime($tmp1).' and end_time >= '.strtotime($tmp2).'))';
                }
                if(!empty($param['banner_start_time']) && empty($param['banner_end_time'])){
                    $tmp3 = strtotime(date("Y-m-d",strtotime($param['banner_start_time']." -1 day")));
                    $sql .= ' and end_time > '.$tmp3;
                }
                if(empty($param['banner_start_time']) && !empty($param['banner_end_time'])){
                    $tmp4 = strtotime(date("Y-m-d",strtotime($param['banner_end_time']." +1 day")));
                    $sql .= ' and start_time < '.$tmp4;
                }
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

        if(!empty($param['title']) || !empty($param['create_time']) || !empty($param['banner_start_time']) || !empty($param['banner_end_time'])){
            $query['title'] = $param['title'];
            $query['create_time'] = $param['create_time'];
            $query['banner_start_time'] = $param['banner_start_time'];
            $query['banner_end_time'] = $param['banner_end_time'];
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
        return $this->fetch();
    }

    //打开新增Banner页面
    public function create()
    {
        $this->data['permission'] = parent::admin_permission();
        //获取当前登录名
        $login_name = parent::admin_info();
       
        $this->data['edit_fields'] = array(
            'banner_title'     => array('type' => 'text', 'label' => '图片名称','notes'=>'请控制字数在16字以内','extra'=>array('wrapper'=>'col-sm-3')),
            'url'     => array('type' => 'text', 'label' => 'URL地址','extra'=>array('wrapper'=>'col-sm-3')),
            'feature_image'  => array('type' => 'file','label' => 'Banner图片', 'id'=>'feature_image'),
            'banner_author'   => array('type' => 'text', 'label' => '创建者', 'disabled'=>'true', 'extra'=>array('wrapper'=>'col-sm-3')),
            'start_time'    => array('type' => 'text', 'label' => 'Banner公开时间','class'=>'datepicker form_datetime','extra'=>array('data'=>array('format'=>'YYYY-MM-DD'),'data1'=>array('format1'=>"true"),'wrapper'=>'col-sm-3')),
            'end_time'    => array('type' => 'text', 'label' => 'Banner结束时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD'),'wrapper'=>'col-sm-3')),
            'status'         => array('type' => 'radio', 'label' => '状态','default'=> array(0 => '非公开', 1 => '公开')),
        );
        //默认值设置
        $item['status']  = '非公开';
        $item['banner_author']  =  $login_name;
        $item['banner_author_hide'] = $login_name;
        
        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();
    }

    //新增Banner操作
    public function add()
    {
        $banners = new Banners;
        $data = input('post.');
        $data['status'] = 1;
        //获取当前作者名对应ID
        $admin_id = AdministratorModel::where('username',$data['banner_author_hide'])->column('id');
        $data['banner_admin'] = $admin_id[0];
        $data['update_admin'] = $admin_id[0];

        if($_POST['status']){
            $data['start_time'] = strtotime($_POST['start_time']);
            $data['end_time'] = strtotime($_POST['end_time']);
            $rule = [
                'banner_admin|Banner图片创建者' => 'require',
                'image_url|Banner图片' => 'require',
                'status|状态' => 'require',
                'start_time|Banner公开时间' => 'require',
                'end_time|Banner结束时间' => 'require',
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
                'banner_admin|Banner图片添加者' => 'require',
                'image_url|Banner图片' => 'require',
                'status|状态' => 'require',
            ];
        }
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        //保存banner图片信息
        if(is_uploaded_file($_FILES['feature_image']['tmp_name'])){  
            if(config('system_toggle')){//设为linux文件系统路径
                $path = ROOT_PATH . 'public' . DS . 'uploads/banners/';
            }else{//设为windows文件系统路径
                $path = ROOT_PATH . 'public' . DS . '/uploads\banners\/';
            }
            //重命名文件名
            $title_new_name = explode('.',$_FILES['feature_image']['name']);
            $title_new = time().'.'.$title_new_name[1];
            if(!move_uploaded_file($_FILES['feature_image']['tmp_name'],$path.$title_new)){
                $this->error('上传Banner图片失败','/admin/banners/create');
            }else{
                $data['source_image'] = $title_new;
            }
        }else{
            $this->error('请选择Banner图片','/admin/banners/create');
        }

        //清除数据库中没有的字段
        unset($data['banners_create']);
        unset($data['banner_author_hide']);
        unset($data['image_url']);
        //转换创建时间和更新时间为时间戳格式
        $data['create_time'] = time();
        $data['update_time'] = time();
        if(!empty($_POST['url'])){
            $data['url'] = $_POST['url'];
        }

        if ($id = $banners->validate(true)->insertGetId($data)) {
            $this->redirect($this->data['module_url'].'search/1');
        } else {
            return $this->error($banners->getError());
        }
    }

    //删除Banner图片(非物理删除)
    public function delete($id)
    {
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
        $banners = new Banners;
        $data['id'] = $id;
        $data['status'] = -1;
        $data['update_admin'] = $login_admin_id;
        //删除图片文件
        $this->delete_image($id);
        $data['source_image'] = '';

        if ($banners->update($data)) {
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
        $banners = Banners::get($id);
        $source_image = $banners->source_image;
        if (file_exists($this->data['upload_path'] .'/banners/'. $source_image)) {
            @unlink($this->data['upload_path'] .'/banners/'. $source_image);
        }
    }
}