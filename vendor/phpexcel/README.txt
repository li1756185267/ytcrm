���÷��� �����������common\models\PHPExcel
���� common\models\PHPExcel

ʵ��:
  //��ȡ����������
  $data = $asrCdrs->Search($app->request->queryParams);
	//����Ϊ�ɴ���Excel��������
   	foreach ($data as $v){
                $excel[] = array($v['id'],$v['src'],$v['dst'],$v['name'],$v['uniqueid'],$v['Address'],$v['calldate'],$v['calltype']);
            }
//excel�ļ�����
  $name = 'ͨ����¼'.time();
//excel��������1������������һ��
  $title = array('���','���к���','���к���','�ͻ�����','ͨ��Ψһ��ʶ','�û���ַ','����ʱ��','��������');
  //������ 
  PHPExcel::toExcel($excel,$title,$name);
  exit();
            