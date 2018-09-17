<?php
namespace backend\controllers;

use backend\controllers\CommonController;
use common\models\Yt;
use Yii;
class IndexController extends CommonController
{
    public $layout='main';
    public function actionIndex()
    {
        return $this->render('index');
    }
}
