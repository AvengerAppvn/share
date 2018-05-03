<?php

use yii\helpers\Html;

?>

<div class="list-user">
    <h2>Danh Sách Người Dùng</h2>
    <div class="col-md-12">
        <table class="table no-margin table-bordered table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Email</th>
                <th>Số điện thoại</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?= $user->id; ?></td>
                    <td><?= Html::a($user->username, ['user/view', 'id' => $user->id]) ?></td>
                    <td><?= Html::a($user->email, ['user/view', 'id' => $user->id]) ?></td>
                    <td><?= $user->phone ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
