<?php
/* @var $this yii\web\View */
/* @var $model common\models\Advertise */
// The Regular Expression filter
$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

// The Text you want to filter for urls
$text = $model->message;

// Check if there is a url in the text
if(preg_match($reg_exUrl, $text, $url)) {
    // make the urls hyper links
    $text = preg_replace($reg_exUrl, "<a href=\"{$url[0]}\" target=\"_blank\">{$url[0]}</a> ", $text);
} else {
    // if no urls in the text just return the text
    //echo $text;
}

$this->title = $model->title;
?>
<div class="content">
    <div class="row share-item">
        <div class="col-md-12">
            <h1><?php echo $model->title ?></h1>
            <p>Đăng lúc: <?php echo date('H:i A - d/m/y', $model->created_at); ?></p>
        </div>
        <div class="col-md-12">
            <h5>
                <?php echo $text ?>
            </h5>

                <?php if ($model->advertiseImages): ?>
                    <div class="row">
                        <?php foreach ($model->advertiseImages as $image): ?>
                        <div class="col-md-6" style="padding-bottom: 20px">
                                <?php echo \yii\helpers\Html::img($image->image,['class' => 'img-responsive']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
        </div>
    </div>