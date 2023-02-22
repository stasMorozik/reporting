<?php declare(strict_types=1);

namespace app\modules\user\useCases;

use app;

class Authorization
{
  private string $access_token_salt;

  private app\modules\user\activeQueries\GettingById $getting_adapter;

  public function __construct(
    string $password_salt,
    string $access_token_salt,
    string $refresh_token_salt,
    app\modules\user\activeQueries\GettingById $getting_adapter
  )
  {
    $this->access_token_salt = $access_token_salt;
    $this->getting_adapter = $getting_adapter;
  }
}
