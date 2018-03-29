<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "advertise".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $cat_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $description
 * @property string $message
 * @property integer $share
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Advertise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advertise';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'cat_id', 'title'], 'required'],
            [['user_id', 'cat_id', 'share', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['title', 'slug', 'description', 'message'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('common', 'User Id'),
            'cat_id' => Yii::t('common', 'Danh mục'),
            'title' => Yii::t('common', 'Tiêu đề'),
            'slug' => 'Slug',
            'content' => Yii::t('common', 'Nội dung'),
            'description' => Yii::t('common', 'Mô tả'),
            'message' => Yii::t('common', 'Thông điệp'),
            'share' => Yii::t('common', 'Share'),
            'status' => Yii::t('common', 'Trạng thái'),
            'created_at' => Yii::t('common', 'Ngày tạo'),
            'updated_at' => Yii::t('common', 'Ngày cập nhật'),
            'created_by' => Yii::t('common', 'Người tạo'),
            'updated_by' => Yii::t('common', 'Người cập nhật'),
        ];
    }

    /**
     * @inheritdoc
     * @return AdvertiseQuery the active query used by this AR class.
     */

}
