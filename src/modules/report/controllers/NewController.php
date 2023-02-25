<?php declare(strict_types=1);

namespace app\modules\report\controllers;

use yii;
use app;

class NewController extends yii\web\Controller
{
  protected app\modules\report\services\IsAuthorized $is_authorized_service;

  public function __construct(
    $id,
    $module,
    app\modules\report\services\IsAuthorized $is_authorized_service,
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
      $authorization_use_case = yii::$container->get('app\modules\user\useCases\Authorization');

      return $this->render('index', ['result' => array_merge(
        yii::$app->request->get(),
        ['user' => $authorization_use_case->getUser()]
      )]);
    }
  }
}
