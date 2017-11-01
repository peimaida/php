<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_QR控制器
 * @Created by MD.Pei
*/

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Model;
use app\admin\model\Maintenance;
use think\Loader;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel;
use think\Log;

class CodeController extends Controller{
	//模块基本信息
	private $data = array(
        'logo' => 'qr_logo.png',
        'module_url'  => '/qr',
		'module_name' => 'QR生成｜MISUMI-VONA',
        'module_description' => 'QR生成',
        'module_keywords' => 'QR生成',
		'module_css'  => '<link rel="stylesheet" href="__FRONT_PUBLIC__/css/base.css">
<link rel="stylesheet" href="__FRONT_PUBLIC__/css/contact.css">',//选择每个模块需要加载的css文件
        'module_js'  => '<script type="text/javascript" src="__FRONT_PUBLIC__/js/jquery.js"></script>',//选择每个模块需要加载的js文件
	);

	public function index(){
        //设置PHP脚本超时时间为10分钟
        ini_set('max_execution_time','600');
		$m_num = maintenance();
		if($m_num==1){
            //设置读取文件格式
            $allowedExts = array("xlsx");

			if(isset($_POST['qr_submit'])){
                //获取文件后缀名
				$temp = explode(".", $_FILES["qr_file"]["name"]);
				$extension = end($temp);
                //设置logo文件路径
                $logo_path = ROOT_PATH.'public'.DS.'static'.DS.'images'.DS;
                $logo = $logo_path.$this->data['logo'];
                //检查文件格式
				if(!in_array($extension, $allowedExts)){
                    $this->assign('download',0);
                    $this->assign('total_num',config('total_num'));
					$this->assign('flag',1);
					$this->assign('msg','请上传xlsx格式文件');
					$this->assign('data',$this->data);
        			return $this->fetch();
				}
                //检查文件大小(不能超过8MB)
				if($_FILES["qr_file"]["size"] >= 8388608){
                    $this->assign('download',0);
                    $this->assign('total_num',config('total_num'));
					$this->assign('flag',1);
					$this->assign('msg','请上传小于8MB的文件');
					$this->assign('data',$this->data);
        			return $this->fetch();
				}
                //检查上传文件是否错误
				if ($_FILES["qr_file"]["error"] > 0){
                    $this->assign('download',0);
                    $this->assign('total_num',config('total_num'));
					$msg = "错误：" . $_FILES["qr_file"]["error"];
					$this->assign('flag',1);
					$this->assign('msg',$msg);
					$this->assign('data',$this->data);
        			return $this->fetch();
				}
                //检查是否存在logo文件
                if (!is_readable($logo)){
                    $this->assign('download',0);
                    $this->assign('total_num',config('total_num'));
                    $msg = "logo文件出错";
                    $this->assign('flag',1);
                    $this->assign('msg',$msg);
                    $this->assign('data',$this->data);
                    return $this->fetch();
                }
                //检查完毕，开始上传
    			if(is_uploaded_file($_FILES['qr_file']['tmp_name'])){
                    //设置保存路径
                    $folder_name = time().'_'.$_FILES['qr_file']['size'];
                    $path = ROOT_PATH.'public'.DS.'uploads'.DS.'qr'.DS.$folder_name.DS;
                    /*
                    if(config('system_toggle')){//设为linux文件系统路径
            			//$logo_path = ROOT_PATH . 'public' . DS . 'static/images/';
            			//$logo = $logo_path . $this->data['logo'];
        			}else{//设为windows文件系统路径
            			//$logo_path = ROOT_PATH.'public'.DS.'static\images\/';
            			//$logo_path = rtrim($logo_path,'/');
            			//$logo = $logo_path . $this->data['logo'];
        			}*/
                    //开始计算处理时间
                    $time_start = microtime(true);
                    //创建目录
                    $dir_res = mkdir($path,0777,true);
                    if(!$dir_res){
                        $this->assign('download',0);
                        $msg = "创建上传目录失败";
                        $this->assign('total_num',config('total_num'));
                        $this->assign('flag',1);
                        $this->assign('msg',$msg);
                        $this->assign('data',$this->data);
                        return $this->fetch();
                    }
                    /*
                    //删除旧文件
                    $old_files = glob($path.'*');
                    foreach($old_files as $file){
                        if(is_file($file)){
                            unlink($file);
                        }
                    }*/
                    //上传新文件
        			$title = time().'.'.$extension;
        			if(!move_uploaded_file($_FILES['qr_file']['tmp_name'],$path.$title)){
                        $this->assign('download',0);
                        $this->assign('total_num',config('total_num'));
                        $msg = "上传文件失败";
                        $this->assign('flag',1);
                        $this->assign('msg',$msg);
                        $this->assign('data',$this->data);
                        return $this->fetch();
            		}

                    //读取xlsx格式文件
                    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                    //指定文件路径
                    $objPHPExcel = $objReader->load($path.$title);
                    //读取第一个sheet的内容
                    $sheet = $objPHPExcel->getSheet(0);
                    //获取行数
                    $highestRow = $sheet->getHighestRow();
                    //判断是否超过数据量
                    if($highestRow>(config('total_num')+1)){
                        $this->assign('download',0);
                        $this->assign('total_num',config('total_num'));
                        $this->assign('flag',1);
                        $this->assign('msg','已超过'.config('total_num').'条数据，请重新上传文件');
                        $this->assign('data',$this->data);
                        return $this->fetch();
                    }
                    //获取列数
                    $highestColumn= PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
                    //读取excel内容，保存至数组
                    for ($row = 1; $row <= $highestRow; $row++) {
                        for ($column = 0; $column<$highestColumn; $column++) {
                            $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                            $list[$row][] = $val;
                        }   
                    }
                    //检查文件内容是否正确
                    for($row = 2; $row <= $highestRow; $row++){
                        $unique_arr[] = $list[$row][0];
                    }
                    if(count($unique_arr)!=count(array_unique($unique_arr))){
                        $this->assign('download',0);
                        $this->assign('total_num',config('total_num'));
                        $this->assign('flag',1);
                        $this->assign('msg','存在重复编号或没有编号的项目，请重新上传文件');
                        $this->assign('data',$this->data);
                        return $this->fetch();
                    }
                    for($row = 2; $row <= $highestRow; $row++){
                        $unique_arr_code[] = $list[$row][2];
                    }
                    if(count($unique_arr_code)!=count(array_unique($unique_arr_code))){
                        $this->assign('download',0);
                        $this->assign('total_num',config('total_num'));
                        $this->assign('flag',1);
                        $this->assign('msg','存在重复Code，请重新上传文件');
                        $this->assign('data',$this->data);
                        return $this->fetch();
                    }
                    for($row = 2; $row <= $highestRow; $row++){
                        if(empty($list[$row][0]) || !is_numeric($list[$row][0])){
                            $this->assign('download',0);
                            $this->assign('total_num',config('total_num'));
                            $this->assign('flag',1);
                            $this->assign('msg','存在编号错误的项目，请编号后重新上传文件');
                            $this->assign('data',$this->data);
                            return $this->fetch();
                        }elseif(empty($list[$row][1]) || ($list[$row][1]!=config('template_type')[1] && $list[$row][1]!=config('template_type')[2] && $list[$row][1]!=config('template_type')[3])){
                            $this->assign('download',0);
                            $this->assign('total_num',config('total_num'));
                            $this->assign('flag',1);
                            $this->assign('msg','存在种类错误的项目，请重新上传文件');
                            $this->assign('data',$this->data);
                            return $this->fetch();
                        }elseif(empty($list[$row][2])){
                            $this->assign('download',0);
                            $this->assign('total_num',config('total_num'));
                            $this->assign('flag',1);
                            $this->assign('msg','存在Code错误的项目，请重新上传文件');
                            $this->assign('data',$this->data);
                            return $this->fetch();
                        }elseif($list[$row][1]==config('template_type')[3] && !empty($list[$row][2]) && empty($list[$row][3])){
                            $this->assign('download',0);
                            $this->assign('total_num',config('total_num'));
                            $this->assign('flag',1);
                            $this->assign('msg','存在型号错误的项目，请重新上传文件');
                            $this->assign('data',$this->data);
                            return $this->fetch();
                        }elseif(preg_match('/[\x{4e00}-\x{9fa5}]/u', $list[$row][2])>0)){
                            $this->assign('download',0);
                            $this->assign('total_num',config('total_num'));
                            $this->assign('flag',1);
                            $this->assign('msg','Code不能存在中文');
                            $this->assign('data',$this->data);
                            return $this->fetch();
                        }             
                    }
                    //生成URL
                    for($row = 2; $row <= $highestRow; $row++){
                        //文件名开头补完0
                        //$name_ahead = str_pad(($row-1),4,'0',STR_PAD_LEFT);
                        //seriesCode
                        if($list[$row][1]==config('template_type')[1]){
                            $url[$row] = config('code_url').'scode='.$list[$row][2];
                            //$file_name[$row] = $name_ahead.'_QR'.$list[$row][2];
                            $file_name[$row] = 'QR'.$list[$row][2];
                        }elseif($list[$row][1]==config('template_type')[2]){//categoryCode
                        	if(strlen($list[$row][2])<10){
                        		$url[$row] = config('code_url').'ccode='.$list[$row][2].'&';
                        		$add = 11 - strlen($list[$row][2]);
                        		if($add>1){
                        			for($i=0;$i<$add;$i++){
                        			    $url[$row] .= $i;
                        		    }
                        		}                       		
                            }else{
                            	$url[$row] = config('code_url').'ccode='.$list[$row][2];
                            }                            
                            //$file_name[$row] = $name_ahead.'_QR'.$list[$row][2];
                            $file_name[$row] = 'QR'.$list[$row][2];
                        }elseif($list[$row][1]==config('template_type')[3]){//partNumber
                            $url[$row] = config('code_url').'scode='.$list[$row][2].'&pcode='.$list[$row][3];
                            //$file_name[$row] = $name_ahead.'_QR_pcode_'.$list[$row][3];
                            $file_name[$row] = 'QR_pcode_'.$list[$row][3];
                        }              
                    }
                    $result_arr1 = array();
                    $result_arr2 = array();
                    $result_arr3 = array();
                    //生成二维码图片
                    for($row = 2; $row <= $highestRow; $row++){
                        $img_name = $file_name[$row].'.png';
                        Loader::import('phpqrcode.qrlib',EXTEND_PATH);
                        $QRcode = new \QRcode();
                        $QRcode::png($url[$row],$path.$img_name);
                        $result_arr1[] = $path.$img_name;
                        if(!is_readable($path.$img_name)){
                            $this->assign('download',0);
                            $this->assign('total_num',config('total_num'));
                            $msg = "生成二维码失败";
                            $this->assign('flag',1);
                            $this->assign('msg',$msg);
                            $this->assign('data',$this->data);
                            return $this->fetch();
                        }
                        $QR = imagecreatefrompng($path.$img_name);
                        $logo_tmp = imagecreatefrompng($logo);
                        $QR_width = imagesx($QR);
                        $QR_height = imagesy($QR);
                        $logo_width = imagesx($logo_tmp);
                        $logo_height = imagesy($logo_tmp);
                        $logo_qr_width = $QR_width/6;
                        $scale = $logo_width/$logo_qr_width;
                        $logo_qr_height = $logo_height/$scale;
                        $from_width = ($QR_width - $logo_qr_width)/2;
                        imagecopyresampled($QR, $logo_tmp, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
                        imagepng($QR, $path.$img_name);
                    }
                    //将png图片转为tif图片
                    $system_exec = 'cd '.$path.';sh '.config('shell_path');
                    $shell_result = system($system_exec);
                    if($shell_result===FALSE){
                        $this->assign('download',0);
                        $this->assign('total_num',config('total_num'));
                        $msg = "生成二维码失败，请重新上传文件";
                        $this->assign('flag',1);
                        $this->assign('msg',$msg);
                        $this->assign('data',$this->data);
                        return $this->fetch();
                    }
                    for($row = 2; $row <= $highestRow; $row++){
                        $tif_name = $file_name[$row].'.tif';
                        $result_arr3[] = $tif_name;
                    }
                    //生成压缩包
                    $zip = new \ZipArchive;
                    $zip_name = $path.'QR_'.time().'.zip';
                    $zip_tmp = '../../../public/uploads/qr/'.$folder_name.'/QR_'.time().'.zip';
                    if($zip->open($zip_name, \ZipArchive::CREATE) === TRUE){
                        $zip->addEmptyDir('QRcode');
                        foreach($result_arr3 as $val2){
                            $zip->addFile($path.$val2,'QRcode'.DS.$val2);
                        }
                        $zip->close();
                        //删除生成的png图片
                        foreach($result_arr1 as $val1){
                            unlink($val1);
                        }
                        foreach($result_arr3 as $val3){
                            unlink($path.$val3);
                        }
                        //结束计算处理时间
                        $time_end = microtime(true);
                        $all_time = round($time_end-$time_start,1);
                        $this->assign('all_time',$all_time);
                        $this->assign('download',1);
                        $this->assign('flag',0);
                        $this->assign('link',$zip_tmp);
                        $this->assign('data',$this->data);
                        return $this->fetch();
                    }else{
                        $this->error('生成压缩文件出错','/qr');
                    }
        		}
        		$this->error('上传文件失败','/qr');
			}
            $this->assign('download',0);
            $this->assign('total_num',config('total_num'));
			$this->assign('flag',0);
			$this->assign('data',$this->data);
        	return $this->fetch();
        }
        $this->redirect('/maintenance');
	}

    //APP下载统计
    public function download($type){
        if(!in_array($type, config('qr_scene'))){
            $this->assign('flag_title_text','页面没有找到');
            $this->assign('flag_content',0);
            return $this->fetch();
        }
        //创建目录
        $path = LOG_PATH.'download'.DS.$type;
        if(!is_dir($path)){
            if(!mkdir($path,0777,true)){
                $this->assign('flag_title_text','创建目录失败');
                $this->assign('flag_content',1);
                return $this->fetch();
            }
        }
        Loader::import('log4php.Logger',EXTEND_PATH);
        $Logger = new \Logger('APP_DOWNLOAD');
        $Logger::configure(
            array(
                'appenders' => array(
                    'default' => array(
                        'class' => 'LoggerAppenderDailyFile',
                        'layout' => array(
                            'class' => 'LoggerLayoutSimple',
                        ),
                        'params' => array(
                            'datePattern' => 'Y-m-d',
                            'file' => LOG_PATH.'download'.DS.$type.DS.'%s.log',
                        ),
                    ),
                ),
                'rootLogger' => array(
                    'appenders' => array('default'),
                ),
            )
        );
        $log_context = '时间:'.date('Y-m-d H:i:s',time()).',IP:'.$_SERVER['REMOTE_ADDR'].',USER_AGENT:'.$_SERVER['HTTP_USER_AGENT'];
        $logger_res = $Logger::getRootLogger();
        $logger_res->info($log_context);
        $this->redirect('http://a.app.qq.com/o/simple.jsp?pkgname=com.misumi_ec.cn.misumi_ec');
    }

    //表单统计
    public function form($type){
        if(!in_array($type, config('form_scene'))){
            $this->assign('flag_title_text','页面没有找到');
            $this->assign('flag_content',0);
            return $this->fetch();
        }
        //创建目录
        $path = LOG_PATH.'form'.DS.$type;
        if(!is_dir($path)){
            if(!mkdir($path,0777,true)){
                $this->assign('flag_title_text','创建目录失败');
                $this->assign('flag_content',1);
                return $this->fetch();
            }
        }
        Loader::import('log4php.Logger',EXTEND_PATH);
        $Logger = new \Logger('FORM');
        $Logger::configure(
            array(
                'appenders' => array(
                    'default' => array(
                        'class' => 'LoggerAppenderDailyFile',
                        'layout' => array(
                            'class' => 'LoggerLayoutSimple',
                        ),
                        'params' => array(
                            'datePattern' => 'Y-m-d',
                            'file' => LOG_PATH.'form'.DS.$type.DS.'%s.log',
                        ),
                    ),
                ),
                'rootLogger' => array(
                    'appenders' => array('default'),
                ),
            )
        );
        $log_context = '时间:'.date('Y-m-d H:i:s',time()).',IP:'.$_SERVER['REMOTE_ADDR'].',USER_AGENT:'.$_SERVER['HTTP_USER_AGENT'];
        $logger_res = $Logger::getRootLogger();
        $logger_res->info($log_context);
        $this->redirect('https://www.baidu.com/');
    }
}