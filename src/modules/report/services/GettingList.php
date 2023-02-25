<?php declare(strict_types=1);

namespace app\modules\report\services;

use app;
use yii;
use DateDateTime;

class GettingList
{
  private app\modules\report\services\IsAuthorized $is_authorized_service;
  private app\modules\report\useCases\GettingList $use_case;

  public function __construct(
    app\modules\report\services\IsAuthorized $is_authorized_service,
    app\modules\report\useCases\GettingList $use_case
  )
  {
    $this->is_authorized_service = $is_authorized_service;
    $this->use_case = $use_case;
  }

  public function get(array $args)
  {
    $this->is_authorized_service->is();

    $authorization_use_case = yii::$container->get('app\modules\user\useCases\Authorization');

    try {
      $session = yii::$app->session;

      $maybe_rows = $this->use_case->get(array_merge($args, ['access_token' => $session->get('access_token')]));

      if (is_array($maybe_rows)) {
        return [
          'user' => $authorization_use_case->getUser(),
          'rows' =>  $maybe_rows
        ];
      }

      if (!is_array($maybe_rows)) {
        if ($maybe_rows instanceof app\common\errors\HaveNotRight) {
          yii::$app->response->redirect([
            '/reports/new',
            'message' => $maybe_rows->getMessage()
          ]);
        }

        if ($maybe_rows instanceof app\common\errors\Domain) {
          return [
            'user' => $authorization_use_case->getUser(),
            'rows' =>  [],
            'message' => $maybe_rows->getMessage()
          ];
        }

        if ($maybe_rows instanceof app\common\errors\Infrastructure) {
          return [
            'user' => $authorization_use_case->getUser(),
            'rows' =>  [],
            'message' => $maybe_rows->getMessage()
          ];
        }
      }
    } catch(Exception $e) {
      return [
        'user' => $authorization_use_case->getUser(),
        'rows' =>  [],
        'message' => 'Something went wrong'
      ];
    }
  }
}

