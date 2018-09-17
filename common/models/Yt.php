<?php

namespace common\models;

use Yii;
use common\models\SendSms;
use common\models\AsrcallSmsLog;
use common\models\CrmCompanyinfo;

class Yt
{

    final public static function checkUserType()
    {
        return true;
    }

    final public static function setSession($name, $value)
    {
        Yii::$app->session[$name] = $value;
        return true;
    }

    final public static function removeSession($name)
    {
        unset(Yii::$app->session[$name]);
        return true;
    }

    final public static function removeAllSession()
    {
        foreach (array_keys($_SESSION) as $key) {
            unset($_SESSION[$key]);
        }
        return true;
    }

    final public static function clearSession()
    {
        session_unset();
        session_destroy();
        return true;
    }

    final public static function getSession($name)
    {
        if ($name == 'loginInfo') {
            return Yii::$app->session[$name];
        } else {
            return Yii::$app->session['loginInfo'][$name];
        }
    }

    /**
     * 检测用户状态
     * @param $data
     * $data session数据
     * @return bool
     */
    final public static function checkLoginStatus($data)
    {
        //检测session数据是否为空
        if ($data !== null) {
            return true;
        }
        return false;
    }

    /**
     * 查询用户信息
     * @param $data
     * $data session数据
     * @return bool
     */
    final public static function getUserInfo($data)
    {
        //检测session数据是否为空
        if ($data !== null) {
            return $data;
        }
        return false;
    }

    /**
     * 检测用户类型
     * @param $data
     * $data session数据
     * @return bool|string
     */
    final public static function checkLoginType($data)
    {
        //判断用户类型123
        switch ($data['usertype']) {
            case 0:
                return '话务员';
                break;
            case 1:
                return '超级管理员';
                break;
            case 2:
                return '公司管理员';
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * 清空session
     * @return bool
     */
    final public static function Logout()
    {
        //清空session
        Yii::$app->session->removeAll();
        //判断是否为空
        if (!isset(Yii::$app->session['loginInfo'])) {
            return true;
        }
        return false;
    }

    /**
     * 当前时间
     */
    final public static function Gtime()
    {
        date_default_timezone_set('PRC');
        return time();
    }

    /**
     * @param $formart string 格式
     * @return false|string 返回值
     */
    final public static function Formarttime($formart = "Y-m-d H:i:s", $time = '')
    {
        if (empty($time)) {
            $time = self::Gtime();
        }
        return date($formart, $time);
    }

    final public static function checkData($model, $data)
    {
        if ($model->load($data) && $model->validate()) {
            return true;
        } else {
            return false;
        }
    }

    final public static function datetime($format = null)
    {
        if ($format) {
            return date($format, time());
        }

        return date('Y-m-d H:i:s', time());
    }

    /**
     * 发送短信接口
     *
     * @param array $data ['mobile'],$data['content']
     * @return array sendResult
     * @example Yt::sendSms(['mobile' => '110', 'content' => 'hello world'])
     */
    final public static function sendSms($data = ['mobile' => '', 'content' => ''])
    {
        $sendSms = new SendSms();
        return $sendSms->send($data);
    }

    /**
     * 发送短信并写日志表
     *
     * @param array $data
     * @return mixed 如果成功返回success,如果失败返回错误信息
     * @example Yt::sendSmsandLog(['asr_number' => '79000001', 'mobile' => '110', 'content' => 'hello world'])
     */
    final public static function sendSmsandLog($data)
    {
        $sees = Yt::getSession('loginInfo');
        !isset($data['user_id']) && $data['user_id'] = $sees['user_id'];
        !isset($data['company_id']) && $data['company_id'] = $sees['company_id'];
        !isset($data['sms_id']) && $data['sms_id'] = '';

        $sms_count = self::getSmsCount($data['company_id']);
        if ($sms_count == 0) return '您的短信条数已用完,暂时无法发送';

        $mobile_count = self::getMobileCount($data['mobile'], $data['company_id']);
        if ($mobile_count >= 1) return '今日次数已达上限';

        $response = Yt::sendSms($data);

        $data['response'] = implode($response);
        $data['create_time'] = Yt::datetime();
        if ($response['returnstatus'] == 'Success') {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        $smsLog = new AsrcallSmsLog();
        $smsLog->setAttributes($data);
        if ($smsLog->save() && $data['status']) {
            self::desSmsCount($data['company_id'], $sms_count, 1);
            return 'success';
        } else {
            if (!$data['status']) return $response;
            return $smsLog->getErrors();
        }
    }

    final public static function getMobileCount($mobile, $company_id)
    {
        $start_time = date('Y-m-d', time()) . ' 00:00:00';
        $end_time = date('Y-m-d', time()) . ' 23:59:59';
        $mobile_count = AsrcallSmsLog::find()
            ->where(['mobile' => $mobile])
            ->andWhere(['company_id' => $company_id])
            ->andWhere(['between', 'create_time', $start_time, $end_time])
            ->count();
        return $mobile_count;
    }


    /**
     * 生成一个包含 大写英文字母, 小写英文字母, 数字 的随机字符串
     * @param  [int]    $length [字符串长度]
     * @return [string]         [返回生成的字符串]
     */
    public static function randomSipPwd()
    {
        //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
        $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $str = '';
        $arr_len = count($arr);
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $arr_len - 1);
            $str .= $arr[$rand];
        }
        return $str;
    }


    /**
     * 数组转换成字串
     * @param $array
     * @param bool $format
     * @param int $level
     * @return string
     */
    public static function arrayEval($array, $format = false, $level = 0)
    {
        $space = $line = '';
        if (!$format) {
            for ($i = 0; $i <= $level; $i++) {
                $space .= "\t";
            }
            $line = "\n";
        }
        $evaluate = 'Array' . $line . $space . '(' . $line;
        $comma = $space;
        foreach ($array as $key => $val) {
            $key = is_string($key) ? '\'' . addcslashes($key, '\'\\') . '\'' : $key;
            $val = !is_array($val) && (!preg_match('/^\-?\d+$/', $val) || strlen($val) > 12) ? '\'' . addcslashes($val, '\'\\') . '\'' : $val;
            if (is_array($val)) {
                $evaluate .= $comma . $key . '=>' . self::arrayEval($val, $format, $level + 1);
            } else {
                $evaluate .= $comma . $key . '=>' . $val;
            }
            $comma = ',' . $line . $space;
        }
        $evaluate .= $line . $space . ')';
        return $evaluate;
    }

    //连接数据库操作
    final public static function dataBaseConnection($dbname = null)
    {
        $dbs = (array)Yii::$app->db;
        $host = explode(';', explode('=', $dbs['dsn'])[1])[0];
        $db = new yii\db\Connection([
            'dsn' => "mysql:host={$host};dbname=" . $dbname,
            'username' => $dbs['username'],
            'password' => $dbs['password'],
            'charset' => 'utf8',
        ]);
        return $db;
    }

    /*
    * 短信发送
    * 鼎汉的单独发送
    */
//    public function sendSmsDh($mobile,$msgContent){
//        $url = 'http://112.74.179.106:8080/Message.sv?method=sendMsg';
//
//        $postdata = array(
//            'userCode' => 'ytwljr',//帐号
//            'userPwd' => 'ytwl888',//密码
//            'numbers' => $mobile,//号码
//            'msgContent' => $msgContent,
//            'charset' => 'UTF-8', //请按平台实际编码填写，UTF-8或gbk
//        );
//
//        $ch = curl_init();
//        $postdata = http_build_query($postdata,'&');
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        $resultdata = curl_exec($ch);
//        curl_close($ch);
//
//        $resultarr = explode(",",$resultdata);
//        if($resultarr[0]==1){
//            return true;
//        }else{
//            return false;
//        }
//
//
//    }

    /**
     * 获取短信条数
     * @param $company_id
     * @return mixed
     */
    final public static function getSmsCount($company_id)
    {
        $num = CrmCompanyinfo::find()->select('sms_count')->where(['id' => $company_id])->asArray()->scalar();
        return $num ? $num : 0;
    }

    /**
     * 减少短信条数 推送表添加数据
     * @param $company_id 公司编号
     * @param $sms_count //短信剩余条数
     * @param $des //减少的数量
     * @return int
     */
    final public static function desSmsCount($company_id, $sms_count, $des)
    {
        //发送成功后 crm_companyinfode sms_count -1
        $des_count = CrmCompanyinfo::updateAll(['sms_count' => $sms_count - $des], ['id' => $company_id]);
        if ($des_count) {
            $new_count = self::getSmsCount($company_id);
            if ($new_count == 1) {
                //推送表插入数据
                $model = new MessageInform();
                $model->company_id = $company_id;
                $model->user_name = Yt::getSession('user_name');
                $model->content = '您的短信发送仅剩一条了哦，请及时充值~';
                $model->type = 'asr_remain_sms';
                $model->status = 0;
                $model->update_time = $model->create_time = Yt::datetime();
                $res = $model->save();
                if (!$res) return 3;
                else return 1;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    }

}
