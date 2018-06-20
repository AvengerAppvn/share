<?php

namespace frontend\models;

use common\models\AdsAdvertiseImage;
use common\models\AdsCategory;
use common\models\Advertise;
use common\models\CategoryAds;
use common\models\Transaction;
use common\models\Wallet;
use trntv\filekit\Storage;
use Yii;
use yii\base\Model;
use yii\di\Instance;

/**
 * Ads form
 */
class AdsDepositForm extends Model
{
    public $budget;
    public $user_id;
    public $ads_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['budget', 'required', 'message' => Yii::t('frontend', 'Missing budget')],
            [['ads_id'], 'safe']
        ];
    }

    /**
     *
     * @return boolean the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $model = Advertise::findOne($this->ads_id);

            $price = $this->getPriceUnit($model);
            $share = intval($this->budget / $price);
            $realMoney = $this->getRealMoney($share,$price);

            $wallet = Wallet::find()->where(['user_id' => $this->user_id])->one();
            if ($wallet && $wallet->amount >= $realMoney) {
                $wallet->amount = $wallet->amount - $realMoney;
                $wallet->save();
            } else {
                return false; // Out of money
            }
            // TODO fix share
            $model->share += $share;
            $model->budget += $realMoney;
            $model->save();


            if ($model->save(false)) {
                $primaryKey = $model->getPrimaryKey();

                $transaction = new Transaction();
                $transaction->description = "Nạp tiền cho quảng cáo " . $this->title;
                $transaction->user_id = $this->user_id;
                $transaction->amount = $model->realMoney;
                $transaction->type = Transaction::TYPE_WITHDRAW; // Chi
                $transaction->save();

                return $model;
            } else {
                Yii::trace("Model validation error => " . print_r($model->getErrors(), true));
                $this->addError('generic', Yii::t('app', 'The system could not update the information.'));
            }
        }
        return false;
    }

    public function calculateShare($ads)
    {
        if ($this->budget) {
            $price_unit = $this->getPriceUnit($ads);
            return intval($this->budget / $price_unit);
        }

        return 0;

    }

    // Lấy tiền mà đã trừ phần trăm của hệ thống
    private function getRealMoney($share,$price)
    {
        return $share * $price;
    }

    private function getPriceUnit($model)
    {
        $percent = \Yii::$app->keyStorage->get('config.service', 20);
        $price_base = (int)\Yii::$app->keyStorage->get('config.price-basic', 5000);
        $option = (int)\Yii::$app->keyStorage->get('config.option', 10);
        $price_unit = $price_base;
        if ($model->location && $model->location > 0) {
            $price_unit += $price_base * $option/100;
        }
        if ($model->age && $model->age > 0) {
            $price_unit += $price_base * $option/100;
        }

        if ($model->category && $model->category > 0) {
            $price_unit += $price_base * $option/100;
        }
        $price_unit += $price_base * $percent/100;
        return $price_unit;
    }
}