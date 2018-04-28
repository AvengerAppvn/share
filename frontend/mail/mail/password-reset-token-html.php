<?php
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $user app\models\User */
    /* @var $appName string */
    /* @var $resetURL string */

?>
<table border="0" cellpadding="18" cellspacing="0" class="mcnTextContentContainer" width="100%" style="background-color: #FFFFFF;">
    <tbody>
    <tr>
        <td valign="top" class="mcnTextContent" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: left; padding: 36px; word-break: break-word;">
            <div style="text-align: center; margin-bottom: 36px">
                <?=$appName;?>
            </div>
            <div style="text-align: left; word-wrap: break-word;">Xin kính chào <b><?= Html::encode($user->username) ?></b>,<br />
                <br />
                Bạn hoặc ai đó xác nhận rằng bạn (Tài khoản: <b><?= Html::encode($user->username) ?></b>) quên mật khẩu tại Shareme.
                <br />
                . Nếu không phải từ bạn, xin vui lòng bỏ qua email này.
                <br /><br />
                . Nếu đúng là từ bạn, nhấn vào <a href="<?=Html::encode($resetURL);?>">đây</a> để tạo lại mật khẩu. Bạn cũng có thể copy/paste <?=$resetURL;?> vào trình duyệt web.
                <br />
                <br />Trân trọng,
                <br />Hỗ trợ Shareme
                <div class="footer" style="font-size: 0.7em; padding: 0px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: right; color: #777777; line-height: 14px; margin-top: 36px;">© <?=date("Y");?> Shareme
                    <br>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>
