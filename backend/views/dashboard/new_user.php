<?php
use yii\helpers\Html;

?>

<div class="table-responsive">
    <table class="table no-margin table-bordered table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Số điện thoại</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model as $item) { ?>
            <tr>
                <td><?= $item->id; ?></td>
                <td><?= $item->username ?></td>
                <td><?= $item->email ?> </td>
                <td><?= $item->phone?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>