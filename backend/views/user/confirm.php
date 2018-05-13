<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
    <div class="row confirm-user">

        <div class="col-md-12">
            <h2> Xác thực tài khoản</h2>
        </div>

        <div class="col-md-12 image">
            <?php echo Html::img($model->image_id_1, ['class' => 'img-comfirm', 'style' => 'padding: 10px; width:500px; height: 350px; ']); ?>
        </div>
        <div class="col-md-12 image">
            <?php echo Html::img($model->image_id_2, ['class' => 'img-comfirm', 'style' => 'padding: 10px; width:500px; height: 350px']); ?>
        </div>
        <div class="col-md-12 image">
            <h3> Danh sách bạn bè:</h3>
            <?php echo Html::img($model->image_friend_list, ['class' => 'img-comfirm', 'style' => 'padding: 10px; width:500px; height: 350px']); ?>
        </div>
        <div class="col-md-12">
            <?php
            if ($model->user->status_confirmed == 1) { ?>

                <div class="col-md-12">
                    <h3>Số CMT: <?php echo $model->cmt; ?></h3>
                </div>

                <div class="col-md-12">
                    <?php echo Html::a("Đã xác thực", ['confirm', 'id' => $model->user_id], ['class' => 'btn btn-success']); ?>
                </div>

            <?php } else { ?>

                <div class="col-md-6 identity-card">
                    <h4>Số chứng minh thư:</h4>
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'cmt')->textInput()->label(false) ?>

                    <?php ActiveForm::end(); ?>
                </div>

                <div class="col-md-12">
                    <a href="" id="confirm" class="btn btn-success"> Xác thực tài khoản </a>
                </div>

            <?php } ?>
        </div>
        <input type="hidden" id="user_id" value="<?php echo $model->user_id; ?>"/>
    </div>

<?php
$this->registerJs('
    $(document).ready(function(){
        $("#confirm").click(function(){
            var cmt = $("#userprofile-cmt").val();
            var user_id = $("#user_id").val();
                $.ajax({
                    url : "' . Url::toRoute('/user/confirmed') . '",
                    type : "get",
                    dataType : "text",
                    data : {
                        cmt : cmt,
                        id : user_id,
                    },
                    success : function(data){
                    alert(data);
                    location.reload();
                    }
                    
                });
        });
    });
');
?>