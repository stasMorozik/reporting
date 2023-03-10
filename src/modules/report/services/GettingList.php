<?php declare(strict_types=1);

namespace app\modules\report\services;

use app;
use yii;
use DateDateTime;

class GettingList
{
  private app\modules\report\useCases\GettingList $getting_list_use_case;
  private app\modules\user\useCases\RefreshSession $refresh_session_use_case;

  public function __construct(
    app\modules\user\useCases\RefreshSession $refresh_session_use_case,
    app\modules\report\useCases\GettingList $getting_list_use_case
  )
  {
    $this->getting_list_use_case = $getting_list_use_case;
    $this->refresh_session_use_case = $refresh_session_use_case;
  }

  public function get(array $args)
  {
    try {
      $session = yii::$app->session;

      $maybe_rows = $this->getting_list_use_case->get(array_merge($args, ['access_token' => $session->get('access_token')]));

      if ($maybe_rows instanceof app\common\errors\Unauthorized) {
        $maybe_session = $this->refresh_session_use_case->refresh([
          'refresh_token' => $session->get('refresh_token')
        ]);

        if ($maybe_session instanceof app\common\errors\Domain) {
          return new app\common\errors\Unauthorized($maybe_session->getMessage());
        }

        $session->set('access_token', $maybe_session->getAccessToken());
        $session->set('refresh_token', $maybe_session->getRefreshToken());

        return $this->getting_list_use_case->get(array_merge($args, ['access_token' => $maybe_session->getAccessToken()]));
      }

      return $maybe_rows;
    } catch(Exception $e) {
      return new app\common\errors\Infrastructure('Something went wrong');
    }
  }
}

