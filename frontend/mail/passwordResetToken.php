<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $token string */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/sign-in/reset-password', 'token' => $token]);
?>

<div style="text-align: left; word-wrap: break-word;">Xin kính chào <b><?= Html::encode($user->username) ?></b>,<br />
    <br />
    Bạn hoặc ai đó xác nhận rằng bạn (Tài khoản: <b><?= Html::encode($user->username) ?></b>) quên mật khẩu tại Shareme.
    <br />
    - Nếu không phải từ bạn, xin vui lòng bỏ qua email này.
    <br />
    - Nếu đúng là từ bạn, nhấn vào <a href="<?=Html::encode($resetLink);?>">đây</a> để tạo lại mật khẩu. Bạn cũng có thể copy/paste <?=$resetLink;?> vào trình duyệt web.
    <br />
    <br />Trân trọng,
    <br />Hỗ trợ Shareme
    <div class="footer" style="font-size: 0.7em; padding: 0px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: right; color: #777777; line-height: 14px; margin-top: 36px;">© <?=date("Y");?> Shareme
        <br>
    </div>
</div>