<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 15:26
 */

namespace common\models;

use Yii;

//use PHPExcel;

class PHPExcel
{
    /**
     * @param $data
     * @param $title
     * @param $filename
     * @param string $suffix
     * @throws \PHPExcel_Exception
     * $data 数据
     * $title 标题
     * $filename 文件名
     * $suffix 文件类型默认 xlsx
     */
    final public static function toExcel($data, $title, $filename, $suffix = 'xlsx')
    {


        header("Content-Type: text/html; charset=UTF-8");
        // 创建一个处理对象实例
        $excel = new \PHPExcel();
        // 缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0
        $excel->setActiveSheetIndex(0);
        $objActSheet = $excel->getActiveSheet();
//        // 设置当前活动sheet的名称
//        $objActSheet->setTitle($filename);
        // 设置单元格高度
        // 所有单元格默认高度
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);

        // 第一行的默认高度
        $excel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
        // 垂直居中
        $excel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // 设置水平居中
        $excel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //Excel表格式列
        $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        for ($i = 0; $i < count($title); $i++) {
            $excel->getActiveSheet()->getColumnDimension($letter[$i])->setWidth(12);
        }
        //表头数组
        $tableheader = $title;
        //填充表头信息
        for ($i = 0; $i < count($tableheader); $i++) {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1", "$tableheader[$i]");
        }
        //填充表格信息
        for ($i = 2; $i <= count($data) + 1; $i++) {
            $j = 0;
            foreach ($data[$i - 2] as $key => $value) {
                $excel->getActiveSheet()->setCellValue("$letter[$j]$i", "$value");
                $j++;
            }
        }
        //创建Excel输入对象
        switch ($suffix) {
            case 'csv':
                $tree = '.csv';
                $write = new \PHPExcel_Writer_CSV($excel);
                $write->setUseBOM(true);
                break;
            case 'xlsx':
                $tree = '.xlsx';
                $write = new \PHPExcel_Writer_Excel2007($excel);
                break;
            case 'xls':
                $tree = '.xls';
                $write = new \PHPExcel_Writer_Excel5($excel);
                break;
            default:
                $write = new \PHPExcel_Writer_Excel5($excel);
                break;
        }
        $filename = $filename . $tree;

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $filename . '"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
    }


    /**
     * @param $savePath
     * @param string $filename
     * @return array
     * $savePath 文件路径
     * $filename 文件名 默认为空
     */
    public final static function importExcel($savePath, $title = 0, $filename = '')
    {
        //设定缓存模式为经gzip压缩后存入cache（还有多种方式请百度）
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        $cacheSettings = array();
        //中文读取不出转下格式
//        $savePath = iconv('UTF-8', 'gb2312', $savePath);
        $endfile = substr($savePath, strrpos($savePath, '.') + 1);
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        switch ($endfile) {
            case 'csv':
                $objExcel = new \PHPExcel_Reader_CSV();
                {
                    //检测文件编码
                    $objExcel->setInputEncoding("UTF-8");
                    $file = escapeshellarg($savePath); // 对命令行参数进行安全转义
                    $line = `head -n 5 $file`;  //去前5行
                    $encoding = mb_detect_encoding($line);
                    if ($encoding === false) {
                        $objExcel->setInputEncoding("UTF-8");
                    } else {
                        $objExcel->setInputEncoding($encoding);
                    }
                }
                break;
            case 'xlsx':
                $objExcel = new \PHPExcel_Reader_Excel2007();
                break;
            case 'xls':
                $objExcel = new \PHPExcel_Reader_Excel5();
                break;
            default:
                Message::jsonEncode(0, '文件格式错误！');
                break;
        }
        if (!$objExcel->canRead($savePath . $filename))
            Message::jsonEncode(0, '文件读取失败！');

        $excel = $objExcel->load($savePath . $filename);
        $sheetData = $excel->getActiveSheet()->toArray(null, false, true, true);
        //删除标题行
        if ($title == 0) {
            unset($sheetData[1]);
        }
        return $sheetData;

    }

    /**
     * 导出csv格式
     * @param array $data //导出的数据
     * @param array $headlist //表头
     * @param $fileName //不带扩展名的文件名
     */
    public final static function csv_export($data = array(), $headlist = array(), $fileName)
    {

        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
        header('Cache-Control: max-age=0');

        //打开PHP文件句柄,php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        //输出Excel列名信息
//        foreach ($headlist as $key => $value) {
//            //CSV的Excel支持GBK编码，一定要转换，否则乱码
//            $headlist[$key] = iconv('utf-8', 'gbk', $value);
//        }

        //将数据通过fputcsv写到文件句柄

        fwrite($fp, '"' . implode('","', $headlist) . '"' . "\r\n");
        //fputcsv($fp, $headlist);

        //计数器
        $num = 0;

        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;

        //逐行取出数据，不浪费内存
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {

            $num++;

            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                if (ob_get_level() > 0)
                    ob_flush();
                flush();
                $num = 0;
            }
            $row = $data[$i];

            //fputcsv($fp, $row);
            //fwrite($fp,implode(',',$row)."\r\n");
            $line = '"' . implode('","', $row) . '"';
            $line = str_replace(array("\r\n", "\r", "\n"), " ", $line);
            fwrite($fp, $line . "\r\n");
        }
    }

}
