<?php
namespace backend\controllers;

use common\models\CrmUserinfo;
use common\models\CrmRole;
use common\models\CrmCompanyinfo;
use common\models\Yt;
use Yii;
use yii\web\Controller;

class LoginController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $loginInfo = Yt::getSession('loginInfo');
        if (isset($loginInfo['user_id'])) {
            $this->redirect(['/index/index'])->send();
        }
        $this->layout = 'login-layout';
        return $this->render('index');
    }

    public function actionCrmIndex()
    {
        Yt::Logout();
        $id = $_POST['companyid'];
        $user = CrmUserinfo::find()->select(['id','role_id','phone','true_name','pic','user_name','department_id','deptid_str','staff_id'])->asArray()->andWhere(['staff_id'=>0,'company_id'=>$id])->one();

        if($user){
            $role = CrmRole::find()->select(['type','is_hidden_num','company_id'])->where(['id' => $user['role_id']])->asArray()->one();
            $phone = !empty($user['phone']) ? substr_replace($user['phone'],'****',3,4) : null;
            $prefix = CrmCompanyinfo::find()->asArray()->select(['prefix'])
                ->where(['id'=>$role['company_id']])->one()['prefix'];
            $loginInfo = [
                'user_id' => $user['id'], #用户id
                'user_name' =>  $user['user_name'], #账号
                'true_name' =>  $user['true_name'], #昵称
                'phone' =>  $phone, #手机号
                'prefix'=>$prefix,//公司代号
                'staff_id' => $user['staff_id'], #员工工号
                'pic'=>$user['pic'],#头像
                'role_id' => $user['role_id'], #用户类型id
                'user_type' => $role['type'], #用户类型：0话务员;1超级管理员，2公司管理员；，3部门经理;4坐席
                'department_id' => $user['department_id'], #归属部门Id
                'company_id' => $role['company_id'], #归属机构Id
                'is_hidden_num' => $role['is_hidden_num'], #是否隐藏号码
                'deptid_str' => $user['deptid_str'], #部门ID列表
                'extension' => '', #扩展内容(例如坐席登录的时候需要输入坐席账号)
                'sys_login'=>1,//sysadmin 登录
            ];
            Yt::setSession('loginInfo',$loginInfo);
            echo json_encode(['status'=>1,'msg'=>'成功']);
        }else{
            echo json_encode(['status'=>0,'msg'=>'失败']);
        }


    }
}
