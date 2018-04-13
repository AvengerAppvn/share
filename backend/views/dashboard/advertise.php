<?php
use yii\helpers\Html;

$i = 1;
?>

<div class="table-responsive">
    <table class="table no-margin table-bordered table-hover">
        <thead>
        <tr>
            <th>STT</th>
            <th>Người tạo</th>
            <th>Danh mục</th>
            <th>Tiêu đề</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model as $item) { ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $item->created_by ? $item->author->username : ''; ?></td>
                <td><?= $item->cat_id ? $item->category->name : '' ?></td>
                <td><?= Html::a( $item->title, ['/advertise/view?id=' . $item->id], ['target' => '_blank']) ; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>