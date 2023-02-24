<?php declare(strict_types=1);

namespace app\modules\user\controllers;

use yii;
use app;

class DefaultController extends yii\web\Controller
{
  protected app\modules\user\services\Registration $registration_service;

  public function __construct(
    $id,
    $module,
    app\modules\user\services\Registration $registration_service,
    $config = []
  )
  {
    $this->registration_service = $registration_service;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    if (yii::$app->request->isPost) {
      $this->registration_service->registry(yii::$app->request->post());
    }

    if (!yii::$app->request->isPost) {
      return $this->redirect(yii\helpers\Url::toRoute(['/users/d'], true));
    }
  }
}
