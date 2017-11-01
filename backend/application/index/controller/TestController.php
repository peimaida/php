<?php 
namespace app\index\controller;
use think\Controller;
use think\Loader;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel;
use think\Db;
use think\Model;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;

class TestController extends Controller{
	public function index(){
        /*
		//设置PHP脚本超时时间为10分钟
        ini_set('max_execution_time','600');
        $result = array();
		//读取xlsx格式文件
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        //指定文件路径
        $objPHPExcel = $objReader->load('C:\Users\sh2328\Desktop\test\new.xlsx');
        //读取第2个sheet的内容
        $sheet = $objPHPExcel->getSheet(1);
        //获取行数
        $highestRow = $sheet->getHighestRow();
        //获取列数
        $highestColumn= PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
        //读取excel内容，保存至数组
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($column = 0; $column<$highestColumn; $column++) {
                $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                $list[$row][] = $val;
            }   
        }
        //读取第3个sheet内容
        $sheet2 = $objPHPExcel->getSheet(2);
        //获取行数
        $highestRow2 = $sheet2->getHighestRow();
        //获取列数
        $highestColumn2= PHPExcel_Cell::columnIndexFromString($sheet2->getHighestColumn());
        //读取excel内容，保存至数组
        for ($row2 = 2; $row2 <= $highestRow2; $row2++) {
            for ($column2 = 0; $column2<$highestColumn2; $column2++) {
                $val2 = $sheet2->getCellByColumnAndRow($column2, $row2)->getValue();
                $list2[$row2][] = $val2;
            }   
        }
        for($row=2;$row<=$highestRow;$row++){
            if(empty($list2[$row][1])){
                $result[$row]['proPDF'] = '';
            }else{
                $result[$row]['proPDF'] = $list2[$row][1];
            }            
        }
        for($row=2;$row<=$highestRow;$row++){
        	$imgURL[$row] = '';
            $picURL[$row] = '';
            $proCodeList[$row] = '';
            $i = 0;
            $result[$row]['seriesCode'] = (string)$list[$row][0];
            $result[$row]['proName'] = $list[$row][1];
            $result[$row]['proBrand'] = $list[$row][2];
            $result[$row]['prodes'] = $list[$row][4];
            $result[$row]['viewlist'][] = '';
            //picURL
            //读取第4个sheet内容
            $sheet3 = $objPHPExcel->getSheet(3);
            //获取行数
            $highestRow3 = $sheet3->getHighestRow();
            //获取列数
            $highestColumn3= PHPExcel_Cell::columnIndexFromString($sheet3->getHighestColumn());
            //读取excel内容，保存至数组
            for ($row3 = 2; $row3 <= $highestRow3; $row3++) {
                for ($column3 = 0; $column3<$highestColumn3; $column3++) {
                    $val3 = $sheet3->getCellByColumnAndRow($column3, $row3)->getValue();
                    $list3[$row3][] = $val3;
                }   
            }
            for($row3=2;$row3<=$highestRow3;$row3++){
                if($list[$row][0]==$list3[$row3][3]){
                    $result[$row]['picURL'][] = $list3[$row3][2];
                }
            }
	        //读取xls格式文件
	        $objReader_tmp = PHPExcel_IOFactory::createReader('Excel5');
	        $filename = 'C:'.DS.'Users'.DS.'sh2328'.DS.'Desktop'.DS.'test'.DS.'series'.DS.$list[$row][0].'.xls';
	        //指定文件路径
	        $objPHPExcel_tmp = $objReader_tmp->load($filename);
	        //读取第1个sheet的内容
	        $sheet1 = $objPHPExcel_tmp->getSheet(0);
	        //获取行数
	        $highestRow1 = $sheet1->getHighestRow();
	        //获取列数
	        $highestColumn1= PHPExcel_Cell::columnIndexFromString($sheet1->getHighestColumn());
	        //读取excel内容，保存至数组
	        for ($row1 = 2; $row1 <= $highestRow1; $row1++) {
	            for ($column1 = 0; $column1<$highestColumn1; $column1++) {
	                $val1 = $sheet1->getCellByColumnAndRow($column1, $row1)->getValue();
	                $list1[$row1][] = $val1;
	            }   
	        }
            for ($row1 = 2; $row1 <= $highestRow1; $row1++) {
                if($row1==2){
                    $result[$row]['categoryName'] = $list1[$row1][5];
                }
                if($row1==3){
                    $result[$row]['brandCode'] = $list1[$row1][3];
                }
            }
            unset($list1);
        }
        //保存为csv格式
        $file_csv = fopen('C:\Users\sh2328\Desktop\json.csv','w');
        foreach($result as $line){
            fputcsv($file_csv,$line);
        }
        fclose($file_csv);
        die;

        $tmp = json_encode($result);
        $tmp_file = fopen('C:\Users\sh2328\Desktop\product_json.js','w') or die("Unable to open file!");
        fwrite($tmp_file,$tmp);
        fclose($tmp_file);
        die;*/



        
        $query_unique = "select category_code from `unique`";
        $unique_result = Db::connect(config('db_config_test'))->query($query_unique);
        foreach($unique_result as $value){
            $query_pv = "select category_code,series_code,PV from pv where category_code = '".$value['category_code']."' order by PV desc limit 0,3";
            $pv_result[] = Db::connect(config('db_config_test'))->query($query_pv);
        }
        //开始输出数据到excel文件里
        $objPHPExcel = new PHPExcel();
        //设置excel文件基本信息
        $objPHPExcel->getProperties()->setCreator('MISUMI_EC')
        ->setLastModifiedBy('MISUMI_EC')
        ->setTitle('PV')
        ->setSubject('')
        ->setDescription('')
        ->setKeywords('')
        ->setCategory('');
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('PV');
        //设置宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);  
        //设置行高度    
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        //设置字体和样式  
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);  
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //设置水平居中    
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        //标题合并  
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        //设置标题  
        $objPHPExcel->setActiveSheetIndex(0)  
                ->setCellValue('A1', 'PV')  
                ->setCellValue('A2', 'categoryCode')  
                ->setCellValue('B2', 'seriesCode1')  
                ->setCellValue('C2', 'PV1')  
                ->setCellValue('D2', 'seriesCode2')
                ->setCellValue('E2', 'PV2')  
                ->setCellValue('F2', 'seriesCode3')  
                ->setCellValue('G2', 'PV3');  
        //填充数据至excel文件
        $row_num = count($pv_result);
        $total_rows = 0;
        foreach($unique_result as $value1){
            $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($total_rows + 3), $value1['category_code']);
            $total_rows++;
        }
        $total_rows = 0;
        $j = 0;
        for($i=0;$i<$row_num;$i++){
            foreach($pv_result[$i] as $key2=>$value2){
                $tmp_count = count($pv_result[$i]);
                if($tmp_count==1){
                    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($total_rows + 3), $pv_result[$i][0]['series_code']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($total_rows + 3), $pv_result[$i][0]['PV']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($total_rows + 3), '-');
                    $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($total_rows + 3), '-');
                    $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($total_rows + 3), '-');
                    $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($total_rows + 3), '-');
                }elseif($tmp_count==2){
                    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($total_rows + 3), $pv_result[$i][0]['series_code']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($total_rows + 3), $pv_result[$i][0]['PV']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($total_rows + 3), $pv_result[$i][1]['series_code']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($total_rows + 3), $pv_result[$i][1]['PV']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($total_rows + 3), '-');
                    $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($total_rows + 3), '-');
                }else{
                    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($total_rows + 3), $pv_result[$i][0]['series_code']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($total_rows + 3), $pv_result[$i][0]['PV']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($total_rows + 3), $pv_result[$i][1]['series_code']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($total_rows + 3), $pv_result[$i][1]['PV']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($total_rows + 3), $pv_result[$i][2]['series_code']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($total_rows + 3), $pv_result[$i][2]['PV']);
                }                  
            }
            $total_rows++;
        }
        ob_end_clean();
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PV.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        

        /*
        $query_unique = "select categorycode from `unique1`";
        $unique_result = Db::connect(config('db_config_test'))->query($query_unique);
        foreach($unique_result as $value){
            $query_profit = "select categorycode,seriescode,profit from profit1 where categorycode = '".$value['categorycode']."' order by profit desc limit 0,3";
            $profit_result[] = Db::connect(config('db_config_test'))->query($query_profit);
        }
        //开始输出数据到excel文件里
        $objPHPExcel = new PHPExcel();
        //设置excel文件基本信息
        $objPHPExcel->getProperties()->setCreator('MISUMI_EC')
        ->setLastModifiedBy('MISUMI_EC')
        ->setTitle('profit')
        ->setSubject('')
        ->setDescription('')
        ->setKeywords('')
        ->setCategory('');
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('profit');
        //设置宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);  
        //设置行高度    
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        //设置字体和样式  
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);  
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //设置水平居中    
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        //标题合并  
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        //设置标题  
        $objPHPExcel->setActiveSheetIndex(0)  
                ->setCellValue('A1', 'profit')  
                ->setCellValue('A2', 'categoryCode')  
                ->setCellValue('B2', 'seriesCode1')  
                ->setCellValue('C2', 'profit1')  
                ->setCellValue('D2', 'seriesCode2')
                ->setCellValue('E2', 'profit2')  
                ->setCellValue('F2', 'seriesCode3')  
                ->setCellValue('G2', 'profit3');  
        //填充数据至excel文件
        $row_num = count($profit_result);
        $total_rows = 0;
        foreach($unique_result as $value1){
            $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($total_rows + 3), $value1['categorycode']);
            $total_rows++;
        }
        $total_rows = 0;
        for($i=0;$i<$row_num;$i++){
            foreach($profit_result[$i] as $key2=>$value2){
                //echo '<pre>';print_r($profit_result[$i]);die;
                $tmp_count = count($profit_result[$i]);
                if($tmp_count==1){
                    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($total_rows + 3), $profit_result[$i][0]['seriescode']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($total_rows + 3), $profit_result[$i][0]['profit']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($total_rows + 3), '-');
                    $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($total_rows + 3), '-');
                    $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($total_rows + 3), '-');
                    $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($total_rows + 3), '-');
                }elseif($tmp_count==2){
                    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($total_rows + 3), $profit_result[$i][0]['seriescode']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($total_rows + 3), $profit_result[$i][0]['profit']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($total_rows + 3), $profit_result[$i][1]['seriescode']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($total_rows + 3), $profit_result[$i][1]['profit']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($total_rows + 3), '-');
                    $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($total_rows + 3), '-');
                }else{
                    $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($total_rows + 3), $profit_result[$i][0]['seriescode']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($total_rows + 3), $profit_result[$i][0]['profit']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($total_rows + 3), $profit_result[$i][1]['seriescode']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($total_rows + 3), $profit_result[$i][1]['profit']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($total_rows + 3), $profit_result[$i][2]['seriescode']);
                    $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($total_rows + 3), $profit_result[$i][2]['profit']);
                }                  
            }
            $total_rows++;
        }
        ob_end_clean();
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="profit.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
        */

	}
}