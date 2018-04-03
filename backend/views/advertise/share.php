<?php

use yii\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
?>
<div class="row">
    <div class="col-md-12">
        <h2>Tổng số lượt share: <?php echo $count ?></h2>
        <table id="list-share" class="table table-bordered table-hover dataTable" role="grid">
            <thead>
            <tr role="row">

                <th class="col-md-2">
                    ID
                </th>

                <th class="col-md-5">
                    Người Share
                </th>

                <th class="col-md-5">
                    Quảng Cáo
                </th>

            </tr>
            </thead>
            <?php
            foreach ($model as $item) {
                ?>
                <tr role="row">
                    <td class="col-md-1">
                        <?= $item->id ?>
                    </td>

                    <td class="col-md-5">
                        <?= $item->user_id ? $item->user->username : '' ?>
                    </td>
                    <td class="col-md-5">
                        <?= $item->ads_id ? $item->advertise->title : '' ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
