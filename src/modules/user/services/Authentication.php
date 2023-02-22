<?php declare(strict_types=1);

namespace app\modules\user\services;

use yii;
use app;
use Exception;

class Authentication
{
  private app\modules\user\useCases\Authentication $use_case;

  public function __construct(
    app\modules\user\useCases\Authentication $use_case
  )
  {
    $this->use_case = $use_case;
  }

  public function auth(array $args)
  {
    try {
      $maybe_session = $this->use_case->auth($args);

      if (($maybe_session instanceof app\modules\user\models\entities\Session) == false) {
        return ['success' => false, 'message' => $maybe_session->getMessage()];
      }

      $session = yii::$app->session;
      $session->open();

      $session->set('access_token', $maybe_session->getAccessToken());
      $session->set('refresh_token', $maybe_session->getRefreshToken());

      return ['success' => true, 'message' => 'You have successfully authenticated'];
    } catch(Exception $e) {
      return ['success' => false, 'message' => 'Something went wrong'];
    }
  }
}
