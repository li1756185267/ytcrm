<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asrcall_number_detection".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $file_path
 * @property string $file_name
 * @property string $create_time
 */
class AsrcallNumberDetection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asrcall_number_detection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'file_path', 'file_name', 'create_time'], 'required'],
            [['company_id'], 'integer'],
            [['create_time'], 'safe'],
            [['file_path', 'file_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'file_path' => 'File Path',
            'file_name' => 'File Name',
            'create_time' => 'Create Time',
        ];
    }
}
