<?php
/*
 * @基于TP5框架开发
 * @Misumi Mobile Site_报表统计控制器
 * @Created by MD.Pei
*/

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Model;
use think\Loader;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use think\Log;

class StatsController extends Controller{
	//模块基本信息
	private $data = array(
        'logo' => 'minovate-logo.png',
        'module_url'  => '/stats',
		'module_name' => '报表统计｜MISUMI-VONA',
        'module_description' => '报表统计',
        'module_keywords' => '报表统计',
	);

	public function index(){
        $except_accounts = '';
        //生成报表
        if(isset($_POST['stats_submit'])){
            $period = $_POST['stats_period'];
            //验证是否已选择时间段
            if(empty($period)){
                $this->assign('scan_flag',0);
                $this->assign('download',0);
                $this->assign('flag',1);
                $this->assign('msg','请选择报表统计时间段');
                $this->assign('data',$this->data);
                return $this->fetch();
            }
            foreach(config('except_accounts') as $value){
                $except_accounts = $except_accounts . "'" . $value . "',";
            }
            $except_accounts = rtrim($except_accounts,',');
            switch ($period){
                case '1':
                    // $query_sql = "select s.uuid, s.usercode, s.customercode, s.create_date, s.media, s.content, s.share_Type, o.open_date, group_concat(o.usercode) as open_usercode, group_concat(o.customercode) as open_customercode from app_share as s left join app_share_open as o on s.uuid = o.uuid where s.create_date>=date_sub(curdate(),interval 1 day) and s.create_date<current_date() and s.customercode not in ('W0000H','W009VV','W0000U','W03HMJ','0XJS43','W03H1I') group by s.uuid order by s.create_date desc";
                    $query_sql = "select s.uuid, s.usercode, s.customercode, s.create_date, s.media, s.content, s.share_Type, o.open_date, group_concat(o.usercode) as open_usercode, group_concat(o.customercode) as open_customercode from app_share as s left join app_share_open as o on s.uuid = o.uuid where s.create_date>=date_sub(curdate(),interval 1 day) and s.create_date<current_date() and s.customercode not in (".$except_accounts.") group by s.uuid order by s.create_date desc";
                    break;
                case '2':
                    // $query_sql = "select s.uuid, s.usercode, s.customercode, s.create_date, s.media, s.content, s.share_Type, o.open_date, group_concat(o.usercode) as open_usercode, group_concat(o.customercode) as open_customercode from app_share as s left join app_share_open as o on s.uuid = o.uuid where date_format(create_date,'%Y-%m')=date_format(now(),'%Y-%m') and s.customercode not in ('W0000H','W009VV','W0000U','W03HMJ','0XJS43','W03H1I') group by s.uuid order by s.create_date desc";
                    $query_sql = "select s.uuid, s.usercode, s.customercode, s.create_date, s.media, s.content, s.share_Type, o.open_date, group_concat(o.usercode) as open_usercode, group_concat(o.customercode) as open_customercode from app_share as s left join app_share_open as o on s.uuid = o.uuid where date_format(create_date,'%Y-%m')=date_format(now(),'%Y-%m') and s.customercode not in (".$except_accounts.") group by s.uuid order by s.create_date desc";
                    break;
                case '3':
                    // $query_sql = "select s.uuid, s.usercode, s.customercode, s.create_date, s.media, s.content, s.share_Type, o.open_date, group_concat(o.usercode) as open_usercode, group_concat(o.customercode) as open_customercode from app_share as s left join app_share_open as o on s.uuid = o.uuid where create_date >= date_sub(current_date(),interval 3 month) and s.customercode not in ('W0000H','W009VV','W0000U','W03HMJ','0XJS43','W03H1I') group by s.uuid order by s.create_date desc";
                    $query_sql = "select s.uuid, s.usercode, s.customercode, s.create_date, s.media, s.content, s.share_Type, o.open_date, group_concat(o.usercode) as open_usercode, group_concat(o.customercode) as open_customercode from app_share as s left join app_share_open as o on s.uuid = o.uuid where create_date >= date_sub(current_date(),interval 3 month) and s.customercode not in (".$except_accounts.") group by s.uuid order by s.create_date desc";
                    break;
                default:
                    $this->assign('scan_flag',0);
                    $this->assign('download',0);
                    $this->assign('flag',1);
                    $this->assign('msg','请选择报表统计时间段');
                    $this->assign('data',$this->data);
                    return $this->fetch();
            }
            //查询数据库
            $sql_result = Db::connect(config('db_config_stats'))->query($query_sql);
            if(empty(count($sql_result))){
                $this->assign('scan_flag',0);
                $this->assign('download',0);
                $this->assign('flag',1);
                $this->assign('msg','该时间段内无数据');
                $this->assign('data',$this->data);
                return $this->fetch();
            }
            foreach($sql_result as $key=>$value){
                //查找json内容的开头和结尾以作比较
                $content_start1 = substr(trim($value['content']),0,1);
                $content_end1 = substr(trim($value['content']),-1);
                //查找json内容的开头第二个字符和结尾第二个字符以作比较
                $content_start2 = substr(trim($value['content']),1,1);
                $content_end2 = substr(trim($value['content']),-2,1);
                //单件商品信息
                if(!strcmp($content_start1,'{') && !strcmp($content_end1,'}') && !strcmp($content_start2,'"') && !strcmp($content_end2,'"')){
                    //清空数组
                    unset($content_arr1);
                    foreach(json_decode($value['content']) as $key1=>$value1){
                        $content_arr1[$key1] = urldecode($value1);
                    }
                    $sql_result[$key]['pcode'][0] = !empty($content_arr1['pcode'])?$content_arr1['pcode']:'无数据';
                    $sql_result[$key]['scode'][0] = !empty($content_arr1['scode'])?$content_arr1['scode']:'无数据';
                    $sql_result[$key]['seriesName'][0] = !empty($content_arr1['seriesName'])?$content_arr1['seriesName']:'无数据';
                }elseif(!strcmp($content_start1,'[') && !strcmp($content_end1,']') && !strcmp($content_start2,'{') && !strcmp($content_end2,'}')){//多件商品信息，购物车分享
                    //清空数组
                    unset($exp_arr);
                    unset($content_arr2); 
                    //去除开头第一个字符
                    $cut_start = substr(trim($value['content']),1);
                    //去除最后一个字符
                    $cut_end = substr($cut_start,0,strlen($cut_start)-1);
                    //以"}为单位切分字符串
                    $exp_arr = explode('"}',$cut_end);
                    foreach($exp_arr as $key2=>$value2){
                        if(!empty($value2)){
                            $exp_arr[$key2] = ltrim($value2,',').'"}';
                            foreach(json_decode($exp_arr[$key2]) as $key3=>$value3){
                                $content_arr2[$key3] = urldecode($value3);
                            }
                        }else{
                            unset($exp_arr[$key2]);
                        }
                        $sql_result[$key]['pcode'][$key2] = !empty($content_arr2['pcode'])?$content_arr2['pcode']:'无数据';
                        $sql_result[$key]['scode'][$key2] = !empty($content_arr2['scode'])?$content_arr2['scode']:'无数据';
                        $sql_result[$key]['seriesName'][$key2] = !empty($content_arr2['seriesName'])?$content_arr2['seriesName']:'无数据';
                        if(empty($value2)){
                           array_pop($sql_result[$key]['pcode']);
                           array_pop($sql_result[$key]['scode']);
                           array_pop($sql_result[$key]['seriesName']); 
                        }
                    }
                }elseif(!strcmp($content_start1,'%')){//邮件分享
                    //清空数据
                    $mail_tmp2 = '';
                    $mail_tmp3 = '';
                    unset($mail_tmp5);
                    unset($content_arr3);
                    unset($mail_content);
                    unset($mail_exp_arr_raw);
                    unset($mail_exp_arr_raw1);
                    unset($mail_exp_arr_raw2);
                    //初始化计数
                    $i = 0;
                    //开始处理邮件内容
                    $mail_content = rawurldecode($value['content']);
                    //判断是否为多件商品信息分享
                    if(strpos($mail_content,'============================')!==false){
                        $mail_exp_arr_raw1 = explode('============================',$mail_content);
                        $mail_exp_arr_raw2 = explode('---------------------------------------------',$mail_exp_arr_raw1[1]);
                        foreach($mail_exp_arr_raw2 as $key5=>$value5){
                            if(empty($mail_exp_arr_raw2[$key5])){
                                unset($mail_exp_arr_raw2[$key5]);
                            }
                            //处理pcode和seriesName
                            if(strpos($mail_exp_arr_raw2[$key5],'【型　号】：')!==false){
                                $mail_tmp1 = explode('】：',$mail_exp_arr_raw2[$key5]);
                                $mail_tmp2 = explode('【',$mail_tmp1[1]);
                                $mail_tmp2 = trim($mail_tmp2[0]);
                                $mail_tmp3 = explode('【',$mail_tmp1[2]);
                                $mail_tmp3 = trim($mail_tmp3[0]);
                            }
                            //处理scode
                            if(strpos($mail_exp_arr_raw2[$key5],'【去网页版查看】：')!==false){
                                $mail_tmp1[7] = trim($mail_tmp1[7]);
                                $mail_tmp4 = explode('detail/',$mail_tmp1[7]);
                                $mail_tmp5 = explode('/?',$mail_tmp4[1]);
                            }
                            $sql_result[$key]['pcode'][$i] = !empty($mail_tmp2)?$mail_tmp2:'无数据';
                            $sql_result[$key]['seriesName'][$i] = !empty($mail_tmp3)?$mail_tmp3:'无数据';
                            $sql_result[$key]['scode'][$i] = !empty($mail_tmp5[0])?$mail_tmp5[0]:'无数据';
                            $i++;
                        }
                    }else{
                        //单件商品信息
                        $mail_exp_arr_raw = explode('---------------------------------------------',$mail_content);
                        foreach($mail_exp_arr_raw as $key4=>$value4){
                            if(empty($mail_exp_arr_raw[$key4])){
                                unset($mail_exp_arr_raw[$key4]);
                            }                            
                            //处理pcode和seriesName
                            if(strpos($mail_exp_arr_raw[$key4],'【型　号】：')!==false){
                                $mail_tmp1 = explode('】：',$mail_exp_arr_raw[$key4]);
                                $mail_tmp2 = explode('【',$mail_tmp1[1]);
                                $mail_tmp2 = trim($mail_tmp2[0]);
                                $mail_tmp3 = explode('【',$mail_tmp1[2]);
                                $mail_tmp3 = trim($mail_tmp3[0]);
                            }
                            //处理scode
                            if(strpos($mail_exp_arr_raw[$key4],'【去网页版查看】：')!==false){
                                $mail_tmp1[8] = trim($mail_tmp1[8]);
                                $mail_tmp4 = explode('detail/',$mail_tmp1[8]);
                                $mail_tmp5 = explode('/?',$mail_tmp4[1]);
                            }
                        }
                        $sql_result[$key]['pcode'][0] = !empty($mail_tmp2)?$mail_tmp2:'无数据';
                        $sql_result[$key]['seriesName'][0] = !empty($mail_tmp3)?$mail_tmp3:'无数据';
                        $sql_result[$key]['scode'][0] = !empty($mail_tmp5[0])?$mail_tmp5[0]:'无数据';
                    }
                }else{
                    $this->assign('scan_flag',0);
                    $this->assign('download',0);
                    $this->assign('flag',1);
                    $this->assign('msg','uuid:'.$value['uuid'].'的数据存在错误，请查询数据库确认数据');
                    $this->assign('data',$this->data);
                    return $this->fetch();
                }
            }
            //开始输出数据到excel文件里
            $objPHPExcel = new PHPExcel();
            //设置excel文件基本信息
            $objPHPExcel->getProperties()->setCreator('MISUMI_EC')
            ->setLastModifiedBy('MISUMI_EC')
            ->setTitle('APP分享统计报表')
            ->setSubject('')
            ->setDescription('')
            ->setKeywords('')
            ->setCategory('');
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('APP分享统计报表');
            //设置宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);  
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);

            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            //设置行高度    
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
            $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
            //设置字体和样式  
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
            $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->getFont()->setBold(true);  
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
            $objPHPExcel->getActiveSheet()->getStyle('A2:M2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            //设置水平居中    
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
            //标题合并  
            $objPHPExcel->getActiveSheet()->mergeCells('A1:M1');
            //设置标题  
            $objPHPExcel->setActiveSheetIndex(0)  
                    ->setCellValue('A1', 'APP分享统计报表')  
                    ->setCellValue('A2', '序号')  
                    ->setCellValue('B2', 'UUID')  
                    ->setCellValue('C2', 'CustomerCode(シェアした人)')  
                    ->setCellValue('D2', 'UserCode(シェアした人)')
                    ->setCellValue('E2', 'シェア日時')  
                    ->setCellValue('F2', 'シェア方式')  
                    ->setCellValue('G2', 'シェア内容')  
                    ->setCellValue('H2', '型番')
                    ->setCellValue('I2', 'シリーズコード')  
                    ->setCellValue('J2', 'シリーズ名')  
                    ->setCellValue('K2', 'CustomerCode(シェア受け取った人)')  
                    ->setCellValue('L2', 'UserCode(シェア受け取った人)')
                    ->setCellValue('M2', 'シェア受け取った日時');
            $total_rows = 0;
            //填充数据至excel文件
            foreach($sql_result as $key=>$value){
                $j = 0;
                if(!empty($value['open_date'])){
                    if(!empty($value['open_usercode'])){
                        $user_arr = $value['open_usercode'];
                    }else{
                        $user_arr = '未登录';
                    }
                    if(!empty($value['open_customercode'])){
                        $customer_arr = $value['open_customercode'];
                    }else{
                        $customer_arr = '未登录';
                    }
                }else{
                    $user_arr = 'N/A';
                    $customer_arr = 'N/A';
                }
                $num = count($value['pcode']);
                if($num==1){
                    $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($total_rows + 3), ($key+1));
                    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($total_rows + 3), $value['uuid']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($total_rows + 3), $value['customercode']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($total_rows + 3), $value['usercode']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($total_rows + 3), $value['create_date']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($total_rows + 3), $value['media']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($total_rows + 3), $value['share_Type']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('H'.($total_rows + 3), $value['pcode'][0]);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('I'.($total_rows + 3), $value['scode'][0]);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('J'.($total_rows + 3), $value['seriesName'][0]);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('K'.($total_rows + 3), $customer_arr);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('L'.($total_rows + 3), $user_arr);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('M'.($total_rows + 3), $value['open_date']);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.($total_rows + 3).':M'.($total_rows + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $total_rows++; 
                }else{
                    for($j=0;$j<$num;$j++){
                        $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($total_rows + 3), ($key+1));
                        $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($total_rows + 3), $value['uuid']);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($total_rows + 3), $value['customercode']);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($total_rows + 3), $value['usercode']);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($total_rows + 3), $value['create_date']);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($total_rows + 3), $value['media']);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($total_rows + 3), $value['share_Type']);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('H'.($total_rows + 3), $value['pcode'][$j]);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('I'.($total_rows + 3), $value['scode'][$j]);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('J'.($total_rows + 3), $value['seriesName'][$j]);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('K'.($total_rows + 3), $customer_arr);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('L'.($total_rows + 3), $user_arr);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('M'.($total_rows + 3), $value['open_date']);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.($total_rows + 3).':M'.($total_rows + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $total_rows++;
                    }
                }
            }
            ob_end_clean();
            $objPHPExcel->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="APP分享统计报表.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        }elseif(isset($_POST['scan_submit'])){
            $output_title = array();
            $output_value = array();
            $path = ROOT_PATH.'runtime'.DS.'log'.DS.'download'.DS;
            $path_download = ROOT_PATH.'runtime'.DS.'log'.DS.'scanlog'.DS;
            //$file_date = date('Y-m-d',strtotime('-1 day'));
            $file_date = $_POST['datepicker'];
            //$filename = date('Y-m-d',strtotime('-1 day')).'.txt';
            $filename = $file_date.'.txt';
            if(file_exists($path_download.$filename)){
                unlink($path_download.$filename);
            }
            $system_exec = 'cd '.$path.';sh '.config('scan_shell_path').' '.$file_date;
            $shell_result = system($system_exec);
            if($shell_result===FALSE){
                $this->assign('scan_flag',0);
                $this->assign('download',0);
                $msg = "生成统计扫描统计出错";
                $this->assign('flag',1);
                $this->assign('msg',$msg);
                $this->assign('data',$this->data);
                return $this->fetch();
            }
            if(file_exists($path_download.$filename)){
                $log_contents = fopen($path_download.$filename,'r') or die('无法打开log文件');
                $handle = fread($log_contents,filesize($path_download.$filename));
                $handle_arr = array_filter(explode('=========================================================',trim($handle)));
                foreach($handle_arr as $value){
                    $tmp = explode('：',$value);
                    $output_title[] = $tmp[0];
                    $output_value[] = $tmp[1];
                }
                fclose($log_contents);
                //开始输出数据到excel文件里
                $objPHPExcel = new PHPExcel();
                $excel_name = '扫码统计_'.$file_date.'.xlsx';
                //设置excel文件基本信息
                $objPHPExcel->getProperties()->setCreator('MISUMI_EC')
                ->setLastModifiedBy('MISUMI_EC')
                ->setTitle('扫码统计_'.$file_date)
                ->setSubject('')
                ->setDescription('')
                ->setKeywords('')
                ->setCategory('');
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setTitle('扫码统计_'.$file_date);
                //设置宽度
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
                //设置行高度
                $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
                $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
                //设置字体和样式
                $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
                $objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                //设置水平居中
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //标题合并
                $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
                //设置标题
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', '扫码统计_'.$file_date)
                        ->setCellValue('A2', '项目')
                        ->setCellValue('B2', '扫码数');
                $total_rows = count($output_title);
                //填充数据至excel文件
                for($i=0;$i<$total_rows;$i++){
                    $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i + 3), $output_title[$i]);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i + 3), $output_value[$i]);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.($i + 3).':B'.($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                }
                ob_end_clean();
                $objPHPExcel->setActiveSheetIndex(0);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$excel_name.'"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                exit;
            }else{
                $this->assign('scan_flag',0);
                $this->assign('download',0);
                $msg = "生成统计扫描统计出错";
                $this->assign('flag',1);
                $this->assign('msg',$msg);
                $this->assign('data',$this->data);
                return $this->fetch();
            }
        }
        //初始化页面
        $this->assign('scan_flag',0);
        $this->assign('download',0);
        $this->assign('flag',0);
        $this->assign('data',$this->data);
        return $this->fetch();
    }

}