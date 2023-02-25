<?php declare(strict_types=1);

namespace app\modules\report\services;

use yii;
use app;
use Exception;

class IsAuthorized
{
  private app\modules\user\services\Authorization $authorization_service;

  public function __construct(
    app\modules\user\services\Authorization $authorization_service,
  )
  {
    $this->authorization_service = $authorization_service;
  }

  public function is()
  {
    $maybe_user = $this->authorization_service->auth();
    if (($maybe_user instanceof app\modules\user\models\Entity) == false) {
      yii::$app->response->redirect([
        '/users/auth/'
      ]);
    }
  }
}
