<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-7-11
 * Time: 下午1:28
 */

namespace frontend\controllers;

use frontend\models\AsrCdrLogic;
use yii\base\Controller;

class BatchController extends Controller
{

    /*
     * 通话评分
     * */
    public function actionAsrcdrAutomaticScoring()
    {
        return AsrCdrLogic::automaticScoring();
    }

    /*
     * 短信/微信发送
     * */
    public function actionAsrcdrAutomaticPush()
    {
        return AsrCdrLogic::asrcdrAutomaticPush();
    }

}