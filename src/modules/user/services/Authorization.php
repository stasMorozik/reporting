<?php declare(strict_types=1);

namespace app\modules\user\services;

use yii;
use app;
use Exception;

class Authorization
{
  private app\modules\user\useCases\interfaces\Authorization $authorization_use_case;
  private app\modules\user\useCases\RefreshSession $refresh_session_use_case;

  public function __construct(
    app\modules\user\useCases\interfaces\Authorization $authorization_use_case,
    app\modules\user\useCases\RefreshSession $refresh_session_use_case
  )
  {
    $this->authorization_use_case = $authorization_use_case;
    $this->refresh_session_use_case = $refresh_session_use_case;
  }

  public function auth(bool $is_auth = false)
  {
    $maybe_user = $this->_auth();
    if (!$is_auth) {
      if ($maybe_user instanceof app\modules\user\models\Entity) {
        yii::$app->response->redirect([
          '/reports/'
        ]);
      }
    }

    if ($is_auth) {
      if (($maybe_user instanceof app\modules\user\models\Entity) == false) {
        yii::$app->response->redirect([
          '/users/auth',
          'success' => false,
          'message' => $maybe_user->getMessage()
        ]);
      }
    }
  }

  private function _auth()
  {
    try {
      $session = yii::$app->session;

      $maybe_user = $this->authorization_use_case->auth([
        'access_token' => $session->get('access_token')
      ]);

      if ($maybe_user instanceof app\common\errors\Domain) {
        $maybe_session = $this->refresh_session_use_case->refresh([
          'refresh_token' => $session->get('refresh_token')
        ]);

        if ($maybe_session instanceof app\common\errors\Domain) {
          return $maybe_session;
        }

        $session->set('access_token', $maybe_session->getAccessToken());
        $session->set('refresh_token', $maybe_session->getRefreshToken());

        return $this->authorization_use_case->auth([
          'access_token' => $maybe_session->getAccessToken()
        ]);
      }

      return $maybe_user;
    } catch(Exception $e) {
      return new app\common\errors\Infrastructure('Something went wrong');
    }
  }
}
