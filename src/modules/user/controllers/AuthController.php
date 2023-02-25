<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;
use app;

class AuthController extends yii\web\Controller
{
  private app\modules\user\services\IsAuthorized $is_authorized_service;

  public function __construct(
    $id,
    $module,
    app\modules\user\services\IsAuthorized $is_authorized_service,
    $config = []
  )
  {
    $this->is_authorized_service = $is_authorized_service;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    if (yii::$app->request->isGet) {
      $this->is_authorized_service->is();
      return $this->render('index', ['result' => yii::$app->request->get()]);
    }
  }
}
