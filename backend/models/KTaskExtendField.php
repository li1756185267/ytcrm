<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "k_task_extend_field".
 *
 * @property integer $id
 * @property integer $type
 * @property string $field_name
 * @property string $display_name
 * @property integer $task_id
 * @property integer $sort
 * @property integer $length
 * @property integer $is_index
 * @property integer $is_unique
 * @property integer $is_notNull
 * @property integer $is_hidden
 * @property integer $enabled
 * @property integer $is_read_only
 * @property string $creater
 * @property string $create_time
 */
class KTaskExtendField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'k_task_extend_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'task_id', 'sort', 'length', 'is_index', 'is_unique', 'is_notNull', 'is_hidden', 'enabled', 'is_read_only'], 'integer'],
            [['task_id', 'is_read_only', 'create_time'], 'required'],
            [['create_time'], 'safe'],
            [['field_name', 'display_name', 'creater'], 'string', 'max' => 45],
            [['display_name', 'task_id'], 'unique', 'targetAttribute' => ['display_name', 'task_id'], 'message' => 'The combination of 显示名字 and 归属项目类型Id has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键Id',
            'type' => '备用字段',
            'field_name' => '字段名（英文字母）',
            'display_name' => '显示名字',
            'task_id' => '归属项目类型Id',
            'sort' => '字段显示顺序',
            'length' => '数据长度',
            'is_index' => '是否索引 0 否；1 是',
            'is_unique' => '是否唯一：0否；1 是；',
            'is_notNull' => '是否可为空：0可以；1 不可；',
            'is_hidden' => '是否在列表中显示0 否；1 是',
            'enabled' => '是否启用：0否；1 是；',
            'is_read_only' => '是否为只读:0 否；1 是',
            'creater' => '创建者',
            'create_time' => '创建时间',
        ];
    }
}
