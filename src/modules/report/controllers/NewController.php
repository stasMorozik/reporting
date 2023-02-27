<?php declare(strict_types=1);

namespace app\modules\report\controllers;

use yii;
use app;

class NewController extends yii\web\Controller
{
  private app\modules\user\services\Authorization $authorization_service;

  public function __construct(
    $id,
    $module,
    app\modules\user\services\Authorization $authorization_service,
    $config = []
  )
  {
    $this->authorization_service = $authorization_service;
    parent::__construct($id, $module, $config);
  }

  public function actionIndex()
  {
    if (yii::$app->request->isGet) {
      $maybe_user = $this->authorization_service->auth();

      if ($maybe_user instanceof app\modules\user\models\Entity) {
        return $this->render('index', ['result' => array_merge(
          yii::$app->request->get(),
          ['user' => $maybe_user]
        )]);
      }

      if (($maybe_user instanceof app\modules\user\models\Entity) == false) {
        yii::$app->response->redirect([
          '/users/new/'
        ]);
      }
    }
  }
}
