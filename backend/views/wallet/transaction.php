<?php

use yii\grid\GridView;

?>
<div class="col-md-12">
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'amount',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->type == 1) {
                        return '<span style="color: #00CC00"> + ' . $model->amount . '</span>';
                    } else {
                        return '<span style="color: red"> - ' . $model->amount . '</span>';
                    }
                },
            ],
            'description',
            'logtime'
        ],
    ]);
    ?>
</div>