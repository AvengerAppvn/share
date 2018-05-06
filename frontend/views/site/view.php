<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\Page
 */
$this->title = $model->title;
?>
<div class="container">
    <div class="block-border page-news page-introduce">
        <div class="row">
            <div class="col-xs-12">
                <div class="block-type-2">
                    <div class="content news-detail">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="news-name">
                                    <h3><?= $model->title; ?></h3>
                                </div>
                                <div class="news-des">
                                    <strong><?= $model->body; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- END block type 2-->
            </div> <!-- END col-sm-12-->
        </div>
    </div>
</div>