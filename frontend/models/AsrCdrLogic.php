<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-7-11
 * Time: 下午1:46
 */

namespace frontend\models;

use common\models\AsrcallConfig;
use common\models\AsrcallRecordDetail;
use common\models\AsrcallSendwxsmsQueue;
use common\models\AsrCdr;
use common\models\AsrInline;
use yii\base\Model;
use yii\db\Query;

class AsrCdrLogic extends Model
{
    const PAGE_SIZE = 1000;
    private $A_Eclass = [
        1=>["min"=>80,"max"=>100],
        2=>["min"=>70,"max"=>80],
        3=>["min"=>60,"max"=>70],
        4=>["min"=>50,"max"=>60],
        5=>["min"=>0 ,"max"=>50]
    ];

    /*
     * 自动评分
     * */
    public function automaticScoring()
    {
        return self::getAsrCdrList();
    }

    /*
     * 获取总表数据
     */
    private function getAsrCdrList()
    {
        $list = AsrCdr::find()
            ->select('id,unique_id,clid,dst,billsec_int')
            ->where(['scores' => 0])
            ->orderBy('call_date desc')
            ->limit(self::PAGE_SIZE)
            ->asArray()
            ->all();

        foreach ($list as $value) {
            self::getAsrRecordDetailList($value);
        }
    }

    /*
     * 获取匹配到的关键词，记录最终评分，随后发短信
     */
    private function getAsrRecordDetailList($param)
    {
        $sun_record = AsrcallRecordDetail::find()
            ->where(['uniqueid' => $param['unique_id']])
            ->asArray()
            ->all();

        $ary_keyword = [];
        $ary_id = [];

        foreach ($sun_record as $value) {
            preg_match("/.+\（成功匹配：([^）]+)\）/u", $value['msg'], $match);
            reset($match) && $ary_keyword[] = $match[1];
            $value['process_id'] && $ary_id[] = $value['process_id'];
        }

        //对关键词进行处理
        if($ary_keyword || $ary_id){
            $ary_avg_score = self::getReqAnsData($ary_keyword, $param['clid'], $param['dst'], $ary_id);

            $avg_score = self::standardForEva($ary_avg_score,$param);

            $avg = intval($avg_score)?:1;
        }else{
            $avg = 1;
        }

        // 评分
        $asr_cdr = AsrCdr::findOne($param['id']);
        $asr_cdr->scores = $avg;
        $asr_cdr->save();

        // 短信微信记录
        $send = false; // TODO 暂无发送短信微信功能
        $send_sms = false;
        $send_wx = false;

        if ($send) {
            //按照机器人设置发送短信
            $config_info = AsrcallConfig::find()
                ->where(['asr_number' => $param['clid']])
                ->asArray()
                ->one();

            $smstype = unserialize($config_info["sms_type"]);
            $key = $smstype["key"];
            $arr_val = explode(",",$smstype["val"]);

            if (1 == $key) {//按照时间范围发送
                if($param['billsec_int'] > $arr_val[0] && $param['billsec_int'] < $arr_val[1]){
                    $send_sms = true;
                }else{
                    $send_sms = true;
                }

            }elseif(2 == $key){//按照
                foreach($arr_val as $v){
                    $arrScore = $this->A_Eclass[$v];
                    if($avg>$arrScore["min"] && $avg<=$arrScore["max"]){
                        $send_sms = true;
                        break;
                    }
                }
            }

            if ($avg > 70) {
                $send_wx = true;

                $name = AsrCdr::find()
                    ->select('name')
                    ->where(['unique_id' => $param['unique_id']])
                    ->scalar();

                $ary_wx = [
                    'callee_num' => $param['callee_num'],
                    'k_name' => $name
                ];

                $json_wx = json_encode($ary_wx);
            }

            if ($send_sms || $send_wx) {
                $data = [
                    'unique_id' => $param['unique_id'],
                    'caller_id' => $param['clid'],
                    'callee_id' => $param['dst'],
                    'wx_data' => $send_wx ? $json_wx : '',
                    'sms_sign' => $send_sms ? 1 : 0,
                    'wx_sign' => $send_wx ? 1 : 0,
                    'create_time' => date('Y-m-d H:i:s')
                ];

                $obj = new AsrcallSendwxsmsQueue();
                $obj->setAttributes($data);
                $obj->save();
            }
        }
    }

    /*
     * 获取关键词对应的分数
     */
    private function getReqAnsData($param, $caller_num, $callee_num, $ary_id=[])
    {
        $list = [];

        $database = AsrInline::find()
            ->select('database')
            ->where(['in_arc' => $caller_num])
            ->scalar();

        !$database && $database = 'test';
        $db_name = "ytcc_{$database}_db";

        $query = new Query();
        $query->select('score,answer,condition,nextname')
            ->from("$db_name.req_ans_data")
            ->where(['<>', 'score', '0']);

        if ($ary_id) {
            $query->andWhere(['in', 'id', $ary_id]);
        } else if ($param) {
            $query->andWhere(['in', 'condition', $param]);
        }

        if (1 != implode($ary_id)) {
            $list = $query->all();
        }

        return $list;
    }

    /*
     * 生成最终分数关键方法
     */
    public function standardForEva($arrAvgScore,$param){
        //强调分到某一类
        $eMark = 0;
        $aMark = 0;//大于90会强调放到A类,无此标记怎不会放到A类会减10分

        $chatOver = 0;//会话结束标记

        //强行分到某一类
        $eForce = 0;
        $aForce = 0;
        $dForce = 0;

        $HoldSec = $param["billsec_int"];

        if($arrAvgScore){
            $allscore   =   0;
            $i          =   0;//匹配到关键词的个数
            foreach($arrAvgScore as $k=>$v){
                $i++;
                $score    =     $v['score'] ? $v['score'] : 0;
                $nextname =     $v['nextname'];
                $allscore +=    $score;
                $score == 101?$aForce = 1:null;
                $score == -1 ?$eForce = 1:null;
                $score == -2 ?$dForce = 1:null;
                $score >= 90 ?$aMark  = 1:null;

                if(trim($nextname) == '#'){
                    $chatOver = 1;
                }
            }
            $avgScore = round($allscore/$i);

            if(!$aForce && $avgScore > 80){
                if($HoldSec <= 20){//小于20秒，进c类
                    $avgScore = 65;
                }elseif($HoldSec <= 30){//小于30秒，匹配的关键词小于3，进b,c
                    $i<=2?$avgScore = 65:null;
                    $i>2 ?$avgScore = 75:null;
                }elseif(!$aMark || $i<=2){
                    $avgScore-=10;
                }
            }elseif (!$aForce && $avgScore <= 80){
                if($HoldSec <= 25){
                    $avgScore = 55;
                }
            }elseif($aForce){
                $avgScore = 90;
            }elseif($eForce){
                $avgScore = 40;
            }elseif($dForce){
                $avgScore = 59;
            }

            if(!$chatOver && !$aForce){
                $avgScore>70 && $HoldSec<40? $avgScore -= 7:null;
                $avgScore>55 && $avgScore<70 && $HoldSec<40? $avgScore -= 3:null;
            }
        }else{
            $avgScore = 1;//没有匹配到关键词
        }

        return $avgScore;
    }

    /*
     * 短信/微信发送
     * */
    public function asrcdrAutomaticPush()
    {
        $list = AsrcallSendwxsmsQueue::find()
            ->select('*')
            ->orderBy('create_time desc')
            ->limit(1000)
            ->asArray()
            ->all();

        $daytime = strtotime(date("Y-m-d 00:00:00"));
        foreach ($list as $val) {
            $addtime = strtotime($val["AddTime"]);
            if($addtime < $daytime){//昨天的短信队列直接删除
                AsrcallSendwxsmsQueue::deleteAll(['unique_id' => $val['unique_id']]);
                continue;
            }

            if ($val['sms_sign']) {
                // TODO 发短信
            }

            if ($val['wx_sign'] && $val['wx_data']) {
                // TODO 发微信
            }

            AsrcallSendwxsmsQueue::deleteAll(['unique_id' => $val['unique_id']]);
        }
    }

}