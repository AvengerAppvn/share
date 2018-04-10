<?php
namespace frontend\models;

use common\models\AdsAdvertiseImage;
use common\models\Advertise;
use common\models\Bank;
use common\models\CriteriaProvince;
use common\models\UserBank;
use trntv\filekit\Storage;
use Yii;
use yii\base\Model;
use yii\di\Instance;

/**
 * Ads form
 */
class BankForm extends Model
{
    public $user_id;
    public $account_name;
    public $account_number;
    public $bank_id;
    public $province_id;
    public $branch_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['account_name', 'trim'],
            ['account_name', 'required', 'message' => Yii::t('frontend', 'Missing account_name')],
            ['account_number', 'required', 'message' => Yii::t('frontend', 'Missing account_number')],
            ['bank_id', 'required', 'message' => Yii::t('frontend', 'Missing bank_id')],
            ['province_id', 'required', 'message' => Yii::t('frontend', 'Missing province_id')],
            ['branch_name', 'string'],
            ['branch_name', 'trim'],
            ['user_id', 'safe'],
        ];
    }

    /**
     *
     * @return boolean the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $model = new UserBank();
            $model->account_name = $this->account_name;
            $model->account_number = $this->account_number;
            $model->bank_id = $this->bank_id;
            $model->province_id = $this->province_id;
            $model->branch_name = $this->branch_name;
            $model->user_id = $this->user_id;

            $bank = Bank::findOne($this->bank_id);
            if ($bank) {
                $model->bank_name = $bank->name;
            }

            $province = CriteriaProvince::findOne($this->province_id);
            if ($province) {
                $model->province_name = $province->name;
            }

            if ($model->save(false)) {
                return $model;
            } else {
                Yii::trace("Model validation error => " . print_r($model->getErrors(), true));
                $this->addError('generic', Yii::t('app', 'The system could not update the information.'));
            }
        }
        return false;
    }
}