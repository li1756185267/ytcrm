调用方法 调用属性详见common\models\PHPExcel
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
            