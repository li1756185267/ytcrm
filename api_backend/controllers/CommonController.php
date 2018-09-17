<?php
namespace api_backend\controllers;

use common\models\Message;
use Yii;
use common\models\Yt;
use common\controllers\BaseController;

class CommonController extends BaseController
{
    protected $inline;
    protected $company_id;
    protected $post;
    public $menu;

    public function beforeaction($action)
    {
        return true;

    }

}
