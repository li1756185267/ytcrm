调用方法 
```
PHPExcel 导出:

调用属性详见common\models\PHPExcel 下toExcel()
引入 common\models\PHPExcel

实例:

  //获取的数组数据
  $data = $asrCdrs->Search($app->request->queryParams);
	//遍历为可传输Excel数据类型
   	foreach ($data as $v){
                $excel[] = array($v['id'],$v['src'],$v['dst'],$v['name'],$v['uniqueid'],$v['Address'],$v['calldate'],$v['calltype']);
            }
//excel文件名称
  $name = '通话记录'.time();
//excel标题名称1请与数据类型一致
  $title = array('编号','主叫号码','被叫号码','客户名字','通道唯一标识','用户地址','呼叫时间','呼叫类型');
  //输出表格 
  PHPExcel::toExcel($excel,$title,$name);
  exit();
  
  PHPExcel 导出
  
  调用属性详见common\models\PHPExcel importExcel()
  引入 common\models\PHPExcel
  
  实例: 
  $files 文件路径,实际操作获取路径即可
  $data 返回的数据
   $files = 'D:\phpStudy\PHPTutorial\WWW\callcenter\话务统计报表1528793399.xls';
   $data = PHPExcel::importExcel($files);

            