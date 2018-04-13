<?php
use yii\helpers\Html;

$i = 1;
?>

<div class="table-responsive">
    <table class="table no-margin table-bordered table-hover">
        <thead>
        <tr>
            <th>STT</th>
            <th>Người yêu cầu</th>
            <th>Số tiền</th>
            <th>Mô tả</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($model as $item) { ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $item->user_id ? $item->user->username : ''; ?></td>
                <td>
                    <?php  if ($item->type == 1) {
                        echo '<span style="color: #00CC00"> + ' . $item->amount . '</span>';
                    } else {
                        echo '<span style="color: red"> - ' . $item->amount . '</span>';
                    } ?>
                </td>
                <td><?= Html::a( $item->description, ['/request/view?id=' . $item->id], ['target' => '_blank']) ; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>