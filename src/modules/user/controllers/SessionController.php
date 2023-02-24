<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;
use app;

class SessionController extends yii\web\Controller
{
  protected app\modules\user\services\Authentication $authentication_service;

  public function __construct(
    $id,
    $module,
    app\modules\user\services\Authentication $authentication_service,
    $config = []
  )
  {
    $this->authentication_service = $authentication_service;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    if (yii::$app->request->isPost) {
      $this->authentication_service->auth(yii::$app->request->post());
    }

    if (!yii::$app->request->isPost) {
      return $this->redirect(yii\helpers\Url::toRoute(['/users/auth'], true));
    }
  }
}
