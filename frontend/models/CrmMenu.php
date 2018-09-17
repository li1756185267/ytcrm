<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "crm_menu".
 *
 * @property integer $Id
 * @property integer $Type
 * @property string $MenuName
 * @property integer $ParentId
 * @property integer $OrderId
 * @property integer $Enabled
 * @property integer $Identity
 * @property string $Url
 * @property string $CreateTime
 */
class CrmMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Type', 'ParentId', 'OrderId', 'Enabled', 'Identity'], 'integer'],
            [['MenuName'], 'required'],
            [['CreateTime'], 'safe'],
            [['MenuName'], 'string', 'max' => 45],
            [['Url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => '主键Id',
            'Type' => '类型：0 菜单；1 功能选项',
            'MenuName' => '菜单名称',
            'ParentId' => '归属父菜单Id',
            'OrderId' => '排列顺序',
            'Enabled' => '是否可用：0 不可用 ；1 可用',
            'Identity' => '所属身份：0共享，1超级管理员，2管理员，3部门经理，4坐席',
            'Url' => '网页地址',
            'CreateTime' => '创建时间',
        ];
    }
}
