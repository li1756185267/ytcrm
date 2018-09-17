<?php
/**
 * 短信接口 采用 北京创世华信科技有限公司
 * www.ipyy.com  010-57135000
 * 同一个号码3分钟之内只能发送3条短信
 * 同一个号码一天之内只能发送10条短信
 * 2018-7-18 16:37:21 王雷
 */
namespace common\models;

use Yii;

class Sms
{
    public $url = 'https://sh2.ipyy.com/smsJson.aspx';

    public $params = [
        'action' => 'send',
        'userid' => '',
        'account' => 'jksc737',
        'password' => '321E04B6103451EE2A572BE0F4638888', // strtoupper(md5('263189'))
        'mobile' => '',
        'content' => '',
        'sendTime' => '',
        'extno' => '',
    ];

    public function send()
    {
        $response = self::request($this->url, $this->params);
        $response = json_decode($response, true);
        //返回短信发送信息
        return $response;
    }


    public static function request($url, $params)
    {
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}