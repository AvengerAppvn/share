<?php

use yii\helpers\Html;

?>

<div class="dashboard-index">
    <!--start user -->
    <div class="row">
        <div class="col-md-12">
            <h3>Thống Kê Người Dùng</h3>
        </div>
        <div class="col-md-3">
            <?= Html::a('
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-users "></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">TỔNG SỐ NGƯỜI DÙNG</span>
                    <span class="info-box-number">' . $total_user . '</span>
                </div>
            </div>', ['/user/index'], ['target' => '_blank']) ?>
        </div>
        <div class="col-md-3">
            <?= Html::a('
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="fa fa-user-o "></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">KHÁCH HÀNG: ' . $customer . '</span>
                     <span class="info-box-text">NGƯỜI QUẢNG CÁO' . $advertiser . '</span>
                </div>
            </div>', ['/dashboard/customer'], ['target' => '_blank']) ?>
        </div>
        <div class="col-md-3">
            <?= Html::a('
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-star-o "></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">YÊU CẦU XÁC MINH</span>
                    <span class="info-box-number">' . $count_request_user . '</span>
                </div>
            </div>', ['/user/request'], ['target' => '_blank']) ?>
        </div>
        <div class="col-md-3">
            <?= Html::a('
            <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="fa fa-thumbs-o-up "></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">KHÁCH HÀNG</span>
                    <span class="info-box-text">+ NGƯỜI QUẢNG CÁO</span>
                    <span class="info-box-number">' . $user . '</span>
                </div>
            </div>', ['/dashboard/list-user'], ['target' => '_blank']) ?>
        </div>
    </div> <!--end user -->

    <!--    start advertise-->
    <div class="row">
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h2 class="box-title">Quảng Cáo mới</h2>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php echo $this->render('advertise', ['model' => $advertise]); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h2 class="box-title">Danh mục Quảng cáo</h2>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <div class="progress-group">
                        <span class="progress-text">Thời trang</span>
                        <span class="progress-number"><b>160</b>/500</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Công nghệ</span>
                        <span class="progress-number"><b>310</b>/500</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-red" style="width: 70%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Ẩm thực</span>
                        <span class="progress-number"><b>480</b>/500</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-green" style="width: 60%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Du lịch</span>
                        <span class="progress-number"><b>250</b>/500</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-yellow" style="width: 50%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Xuất Nhập khẩu</span>
                        <span class="progress-number"><b>250</b>/500</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-primary" style="width: 40%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                    <div class="progress-group">
                        <span class="progress-text">Di trú</span>
                        <span class="progress-number"><b>250</b>/500</span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-info" style="width: 30%"></div>
                        </div>
                    </div>
                    <!-- /.progress-group -->
                </div>
            </div>
        </div>
        <!--    end advertiser-->
    </div>

    <div class="row">
        <!--    new request-->
        <div class="col-md-8">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h2 class="box-title">Yêu cầu mới</h2>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?php echo $this->render('request', ['model' => $request]); ?>
            </div>
        </div>

        <!--    new user -->
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Tài khoản mới</h2>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?php echo $this->render('new_user', ['model' => $new_user]); ?>
            </div>
        </div>
    </div>

