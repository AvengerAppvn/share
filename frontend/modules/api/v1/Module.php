<?php

namespace frontend\modules\api\v1;

use common\models\SystemLogEndpoint;
use Yii;

class Module extends \frontend\modules\api\Module
{
    public $controllerNamespace = 'frontend\modules\api\v1\controllers';
    private $log;
    private $milliseconds;

    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        Yii::$app->user->loginUrl = null;
    }

    public function beforeAction($action)
    {
        $log_endpoint = Yii::$app->keyStorage->get('log_endpoint', true);
        if ($log_endpoint) {
            $requests = \yii::$app->getRequest();
            $this->log = new SystemLogEndpoint();
            $this->log->action = $requests->url;
            $this->log->method = $requests->method;
            $this->log->header = json_encode($requests->headers);
            if ($requests->isGet) {
                $this->log->param = json_encode($requests->queryParams);
            }else{
                $this->log->param = $requests->getRawBody();
            }
            $this->milliseconds = round(microtime(true) * 1000);
            $this->log->save();
        }
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        $log_endpoint = Yii::$app->keyStorage->get('log_endpoint', true);
        if ($log_endpoint && $this->log) {
            if(is_array($result)){
                $this->log->result = json_encode($result);
            }else{
                $this->log->result = $result;
            }
            $milliseconds = round(microtime(true) * 1000);
            $this->log->count_time = $milliseconds - $this->milliseconds;
            $this->log->save();
        }
        return parent::afterAction($action, $result);
    }
}
