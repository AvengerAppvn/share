<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category_ads".
 *
 * @property integer $id
 * @property integer $cat_id
 * @property integer $ads_id
 */
class CategoryAds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_ads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'ads_id'], 'required'],
            [['cat_id', 'ads_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cat_id' => Yii::t('app', 'Cat ID'),
            'ads_id' => Yii::t('app', 'Ads ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return CategoryAdsQuery the active query used by this AR class.
     */
}
