<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wallet".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $amount
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Wallet extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'wallet';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[ [ 'user_id' ], 'required' ],
			[ [ 'user_id' ], 'unique' ],
			[ [ 'user_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by' ], 'integer' ],
			[ [ 'amount' ], 'number' ],
		];
	}

	public function behaviors() {
		return [
			TimestampBehavior::className(),
			BlameableBehavior::className(),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'         => 'ID',
			'user_id'    => Yii::t( 'common', 'Người dùng' ),
			'amount'     => Yii::t( 'common', 'Số dư' ),
			'status'     => Yii::t( 'common', 'Trạng thái' ),
			'created_at' => Yii::t( 'common', 'Ngày tạo' ),
			'updated_at' => Yii::t( 'common', 'Ngày cập nhật' ),
			'created_by' => Yii::t( 'common', 'Người tạo' ),
			'updated_by' => Yii::t( 'common', 'Người cập nhật' ),
		];
	}

	public function getAuthor() {
		return $this->hasOne( User::className(), [ 'id' => 'created_by' ] );
	}

	public function getUpdater() {
		return $this->hasOne( User::className(), [ 'id' => 'updated_by' ] );
	}

	public function getUser() {
		return $this->hasOne( User::className(), [ 'id' => 'user_id' ] );
	}

	public static function findOrCreate( $user_id ) {
		$wallet = Wallet::find()->where( [ 'user_id' => $user_id ] )->one();
		if ( ! $wallet ) {
			$wallet          = new Wallet();
			$wallet->user_id = $user_id;
			$wallet->amount  = 0;
			$wallet->status  = 1;
			$wallet->save();
		}

		return $wallet;
	}

	/**
	 * @inheritdoc
	 * @return \common\models\query\WalletQuery the active query used by this AR class.
	 */
	public static function find() {
		return new \common\models\query\WalletQuery( get_called_class() );
	}
}
