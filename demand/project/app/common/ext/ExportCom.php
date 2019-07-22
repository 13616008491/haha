<?php
/**
 * @功能：接口类型
 * @文件：IsDelete.class.php
 * @作者：zc
 * @时间：15-4-2 下午4:16
 */
namespace app\common\ext;

class ExportCom{
    // 默认权限
    protected static $letter_list = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

    protected static $total_merge_cell = null;
    protected static $style_horizontal = null;
    protected static $col_width = [];
    protected static $head_background_color = null;
    protected static $border_color = null;

    public static function setTotalMergeCell($num = 1){
        self::$total_merge_cell = $num;
    }

    public static function setStyleHorizontal($value = null){
        if(empty($value)){
            $value = 'center';
        }
        self::$style_horizontal = $value;
    }

    public static function setColWidth($width_str){
        self::$col_width = explode(',',$width_str);
    }

    public static function setBorderColor($color = '004F4F4F'){
        self::$border_color = $color;
    }

    public static function setHeadBackgroundColor($color = '00F2B087'){
        self::$head_background_color = $color;
    }
    /**
     * @功能：生成表格
     * @开发者：wdd
     */
    public static function export($title,$first_list,$list = null){

        //加载PHPExcle类库
        vendor('PHPExcel.PHPExcel');

        //实例化PHPExcel类
        $objPHPExcel = new \PHPExcel();

//        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setRGB(\PHPExcel_Style_Color::COLOR_GREEN);
//        $objPHPExcel->getActiveSheet()->getComment( 'A1')->getFillColor()->setRGB('EEEEEE' );

        $col_num = count($first_list);

        for($i=0;$i<$col_num;$i++){
            $letter = self::$letter_list[$i];

            //设置表格头（即excel表格的第一行）
            $objPHPExcel->getActiveSheet()->setCellValue($letter.'1', $first_list[$i])
                ->getStyle($letter.'1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            if(isset(self::$col_width[$i])){
                $objPHPExcel->getActiveSheet()->getColumnDimension($letter)->setWidth(self::$col_width[$i]);
            }
            if(!empty(self::$head_background_color)){
                $objPHPExcel->getActiveSheet()->getStyle($letter.'1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle($letter.'1')->getFill()->getStartColor()->setARGB(self::$head_background_color);
            }
        }

        $data_count = count($list);
        if (!empty($list)){
            for($i=0;$i<$data_count;$i++){
                $num = $i+2;

                for($j=0;$j<count($list[$i]);$j++){
                    $letter = self::$letter_list[$j];
                    $objPHPExcel->getActiveSheet()->setCellValue($letter.$num,$list[$i][$j]);
                    if(!empty(self::$style_horizontal)){
                        $objPHPExcel->getActiveSheet()->getStyle($letter.$num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    }
                }
            }
        }

        $first_index = 2;
        $line_end = $data_count + 1;
        if(!empty(self::$total_merge_cell)){
            $num = $i+2;

            if(self::$total_merge_cell > 1){
                $end_col = self::$letter_list[self::$total_merge_cell - 1];
                $objPHPExcel->getActiveSheet()->mergeCells("A{$num}:{$end_col}{$num}");//合计
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$num,'合计')
                ->getStyle('A'.$num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            if(!empty(self::$head_background_color)){
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getFill()->getStartColor()->setARGB(self::$head_background_color);
            }

            for($j=self::$total_merge_cell; $j<count($list[0]); $j++){
                $letter = self::$letter_list[$j];
                $objPHPExcel->getActiveSheet()->setCellValue($letter.$num,"=SUM({$letter}{$first_index}:{$letter}{$line_end})");
                if(!empty(self::$style_horizontal)){
                    $objPHPExcel->getActiveSheet()->getStyle($letter.$num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
                if(!empty(self::$head_background_color)){
                    $objPHPExcel->getActiveSheet()->getStyle($letter.$num)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                    $objPHPExcel->getActiveSheet()->getStyle($letter.$num)->getFill()->getStartColor()->setARGB(self::$head_background_color);
                }
            }
        }

        if(!empty(self::$border_color)){
            $styleThinBlackBorderOutline = array(
                'borders' => array(
                    'allborders' => array( //设置全部边框
                        'style' => \PHPExcel_Style_Border::BORDER_THIN, //粗的是thick
                        'color' => array('argb' => '4F4F4F'),
                    ),

                ),
            );
            $objPHPExcel->getActiveSheet()->getStyle('A1:'.self::$letter_list[$col_num-1].($line_end+1))->applyFromArray($styleThinBlackBorderOutline);
        }

        //设置保存的Excel表格名称
        $filename = $title;
        //设置当前激活的sheet表格名称；
        $objPHPExcel->getActiveSheet()->setTitle($title);
        //设置浏览器窗口下载表格
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        //生成excel文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        //下载文件在浏览器窗口
        $objWriter->save('php://output');
        exit;
    }
}