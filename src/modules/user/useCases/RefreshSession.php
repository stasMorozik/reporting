<?php declare(strict_types=1);

namespace app\modules\user\useCases;

use app;

class RefreshSession
{
  private string $access_token_salt;
  private string $refresh_token_salt;

  public function __construct(
    string $access_token_salt,
    string $refresh_token_salt
  )
  {
    $this->access_token_salt = $access_token_salt;
    $this->refresh_token_salt = $refresh_token_salt;
  }

  public function refresh(
    array $args
  ): app\common\errors\Domain | app\modules\user\models\entities\Session
  {
    return app\modules\user\models\entities\Session::refresh(array_merge(
      $args,
      [
        'access_token_salt' => $this->refresh_token_salt,
        'refresh_token_salt' => $this->refresh_token_salt
      ]
    ));
  }
}
