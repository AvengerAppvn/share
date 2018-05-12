<?php

namespace frontend\models;

use common\models\Transaction;
use trntv\filekit\Storage;
use Yii;
use yii\base\Model;
use yii\di\Instance;
/**
 * Deposit Form
 */
class DepositForm extends Model
{
    public $user_id;
    public $description;
    public $image;
    public $amount;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description',], 'string'],
            ['amount', 'number'],
            [['image'], 'safe']
        ];
    }

    /**
     *
     * @return boolean the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $transaction = new Transaction();
            $transaction->user_id = $this->user_id;
            $transaction->amount = $this->amount;
            $transaction->type = Transaction::TYPE_PENDING;

            if ($this->image) {
                // requires php5
                define('UPLOAD_DIR', \Yii::getAlias('@storage') . '/web/source/capture/');
                $fileStorage = Instance::ensure('fileStorage', Storage::className());

                $img = $this->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $filename = uniqid() . '.png';
                $file = UPLOAD_DIR . $filename;
                $success = file_put_contents($file, $data);

                $transaction->image_base_url = $success ? $fileStorage->baseUrl : '';
                $transaction->image_path = $success ? 'capture/' . $filename : '';
            }

            $transaction->description = $this->description;
            $transaction->status = 1;
            $transaction->save();
            return true;
        } else {
            $this->addError('generic', Yii::t('app', 'The system could not update the information.'));
        }

        return false;
    }
}