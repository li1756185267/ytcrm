<?php

namespace common\controllers;

use yii\web\Controller;
use Yii;

class BaseController extends Controller
{
    private $post;
    private $get;

    public function beforeaction($action)
    {
        // parent::beforeAction($action);
        self::initPost();
        self::initGet();
    }

    /**
     * 连接数据库操作
     *
     * @param string $dbname
     * @param string $host
     * @param string $username
     * @param string $password
     * @return mixed db_cnnection
     */
    protected function dataBaseConnection($dbname = null, $host = 'localhost', $username = 'root', $password = 'yt6533629@100')
    {
        if (!$dbname) throw new \Exception('$dbname参数必须为真值');
        $db = new yii\db\Connection([
            'dsn' => "mysql:host={$host};dbname=ytcc_{$dbname}_db",
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
        ]);
        return $db;
    }


    protected function dataBaseConnection2($dbname = null, $host = 'localhost', $username = 'root', $password = 'yt6533629@100')
    {
        if (!$dbname) throw new \Exception('$dbname参数必须为真值');
        $db = new yii\db\Connection([
            'dsn' => "mysql:host={$host};dbname={$dbname}",
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
        ]);
        return $db;
    }


    protected function dataBaseTemplate($dbname = null, $host = 'localhost', $username = 'root', $password = 'yt6533629@100'){
        if (!$dbname) throw new \Exception('$dbname参数必须为真值');
        $db = new yii\db\Connection([
            'dsn' => "mysql:host={$host};dbname={$dbname}",
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
        ]);
        return $db;
    }

    protected function dataBaseInfo(){
        $dbInfo = Yii::$app->db;
        $hosts = substr(substr($dbInfo->dsn,0,strpos($dbInfo->dsn, ';')),11);
        $username = $dbInfo->username;
        $password = $dbInfo->password;

        $res = [];
        $res['host'] = $hosts;
        $res['username'] = $username;
        $res['password'] = $password;

    }


    /**
     * 所有basecontroller的子类能 通过$this->post; 获得post数据
     *
     * @return void
     */
    private function initPost()
    {
        $this->post = Yii::$app->request->post();
    }

    private function initGet()
    {
        $this->get = Yii::$app->request->get();
    }

    /**
     * 封装获得post数据的方法
     *
     * @param string $param
     * @param string $empty
     * @return void
     * @example $user_name = $this->post('user_name', '');
     */
    protected function post($param = '', $notSet = '')
    {
        if (!$param) return $this->post;
        $post = $this->post;
        return isset($post[$param]) ? $post[$param] : $notSet;
    }


    /**
     * 封装获得get数据的方法
     *
     * @param string $param
     * @param string $empty
     * @return void
     * @example $user_name = $this->get('user_name', '');
     */
    protected function get($param = '', $notSet = '')
    {
        if (!$param) return $this->get;
        $get = $this->get;
        return isset($get[$param]) ? $get[$param] : $notSet;
    }

    /**
     * get post请求通用方法
     * @param string $param
     * @param string $notSet
     * @return string
     */
    protected function paramSet($param = '', $notSet = '')
    {
        if (Yii::$app->request->isPost) {
            $post = $this->post;
            return isset($post[$param]) ? $post[$param] : $notSet;
        }
        if (Yii::$app->request->isGet) {
            $get = $this->get;
            return isset($get[$param]) ? $get[$param] : $notSet;
        }
    }


    /**
     * 封装分页
     * @param $query 查询语句
     * @param int $page 页码
     * @param int $page_size 每页多少条
     * @return mixed
     * @example
     * $query = KnowledgeBase::find()
     * ->select('*')
     * ->where(['company_id' => $company_id]);
     * $map = $this->paginate($query, $page, $page_size);
     */
    protected function paginate($query, $page = 1, $page_size = 10)
    {
        $count = $query->count('*');
        $total_page = ceil($count / $page_size);
        if ($page > $total_page || $page < 1) {
            $page = 1;
        }
        $start = ($page - 1) * $page_size;
        $info = $query->offset($start)
            ->limit($page_size)
            ->all();
        $map['page'] = $page;
        $map['page_size'] = $page_size;
        $map['total_page'] = $total_page;
        $map['total_count'] = $count;
        $map['info'] = $info;
        return $map;
    }

    /**
     * 封装分页
     * @param $query 查询语句
     * @param int $page 页码
     * @param int $page_size 每页多少条
     * @return mixed
     * @example
     * $query = KnowledgeBase::find()
     * ->select('*')
     * ->where(['company_id' => $company_id]);
     * $map = $this->paginate($query, $page, $page_size);
     */
    protected function paginates($query, $page = 1, $page_size = 10)
    {
        $count = $query->count('*');
        $total_page = ceil($count / $page_size);
        if ($page > $total_page || $page < 1) {
            $page = 1;
        }
        $start = ($page - 1) * $page_size;
        $info = $query
            ->asArray()
            ->offset($start)
            ->limit($page_size)
            ->all();
        $map['page'] = $page;
        $map['page_size'] = $page_size;
        $map['total_page'] = $total_page;
        $map['total_count'] = $count;
        $map['info'] = $info;
        return $map;
    }

    //数字转时长
    protected function secToTime($times)
    {
        $result = '00:00:00';
        if ($times > 0) {
            $hour = floor($times / 3600);
            $minute = floor(($times - 3600 * $hour) / 60);
            $second = floor((($times - 3600 * $hour) - 60 * $minute) % 60);
            if (strlen($hour) <= 1) {
                $hour = '0' . $hour;
            }
            if (strlen($minute) <= 1) {
                $minute = '0' . $minute;
            }
            if (strlen($second) <= 1) {
                $second = '0' . $second;
            }
            $result = $hour . ':' . $minute . ':' . $second;
        }
        return $result;
    }
}
