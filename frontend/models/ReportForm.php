<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ReportForm is the model behind the contact form.
 */
class ReportForm extends Model
{
    public $email;
    public $user_id;
    public $ads_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email','user_id','ads_id'], 'required'],
            // We need to sanitize them
            ['email', 'email'],
            // verifyCode needs to be entered correctly

        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('frontend', 'Email'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function report()
    {
        if ($this->validate()) {
        	$body = 'Body';
	        $attach = 'test/report.xls';

	        return true;
            return Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom(Yii::$app->params['robotEmail'])
                ->setReplyTo([$this->email => $this->email])
                ->setSubject('Báo cáo ...')
                ->setTextBody($body)
	            ->attach($attach)
                ->send();
        } else {
            return false;
        }
    }
}
