<?php
namespace backend\controllers;

use common\models\CrmCompanyinfo;
use common\models\Yt;
use common\models\CrmMenu;
use common\controllers\BaseController;

class CommonController extends BaseController
{
    public $menu;
    public $ytuser;

    public function beforeaction($action)
    {
        parent::beforeAction($action);
        if($this->show() != 1){
            if ($this->checkLogin()) {
                $this->menu = $this->loadMenu();
                $this->ytuser = Yt::getSession('loginInfo');
                return true;
            } else {
                $this->redirect(['/login/index'])->send();
                return false;
            }
        }else{
            echo '维护中....';
        }
    }


//读取
    public function show(){
        $dir  = str_replace("controllers","config",dirname(__FILE__));
        $dir  = str_replace("backend","sysadmin",$dir).'/system.php';
        if(!file_exists($dir)){
           return 0;
        }
        return  file_get_contents($dir);
    }

    private function loadMenu()
    {

        $_loginInfo = Yt::getSession('loginInfo');
        $companyInfo = \common\models\CrmCompanyinfo::findOne((int)$_loginInfo['company_id']);

        $rules = '';
        if($_loginInfo['user_name'] != $companyInfo->admin_name){
            $_roleInfo = \common\models\CrmRole::findOne((int)$_loginInfo['role_id']);
            $rules = $_roleInfo->rules;
            $rules = trim($rules);
            if(!empty($rules)){
                $strPosIndex = strpos($rules,":");
                if(!empty($strPosIndex)){
                    $rules = explode(':',$rules);
                    $rules = $rules[0];
                }
            }
        }

       header("Content-Type: text/html; charset=UTF-8");
          $query=  CrmMenu::find()
            ->andWhere(['enabled' => 1,'syscard'=>0])
              ->andWhere(['<>', 'identity', '4'])
            ->andWhere('type=1 or type=0');

        if($_loginInfo['user_name'] != $companyInfo->admin_name){
            $rulesArr = explode(',',$rules);
            foreach ($rulesArr as $_k=>$_v){
                $rulesArr[$_k] = (int)$_v;
            }
            $query->andFilterWhere(['IN','id',$rulesArr]);
        }
        $menus = $query
            ->orderBy('order_id asc')
            ->asarray()
            ->all();

        $arr = array();
        $index = 0;
        foreach ($menus as $menu) {
            if ($menu['parent_id'] == 0) {
                $newMenu = ['label' => $menu['menu_name'], 'icon' => $menu['icon'], 'url' => $menu['url']];
               // $newMenu = ['label' => $menu['menu_name']]; 
                array_push($arr, $newMenu);
                foreach ($menus as $child) {
                    if ($child['parent_id'] == $menu['id']) {
                        $childMenu = ['label' => $child['menu_name'], 'url' => [$child['url']]];
                        $arr[$index]['items'][] = $childMenu;
                    }
                }
                $index++;
            }
        }

        return $arr;
    }

    private function checkLogin()
    {
        $loginInfo = Yt::getSession('loginInfo');
        if (!isset($loginInfo['user_id'])) {
            return false;
        } else {
            return true;
        }
    }

}
