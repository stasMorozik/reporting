<?php declare(strict_types=1);

namespace app\modules\user\useCases;

use app;

class Authentication
{
  private string $password_salt;
  private string $access_token_salt;
  private string $refresh_token_salt;
  private app\modules\user\activeQueries\Getting $getting_adapter;

  public function __construct(
    string $password_salt,
    string $access_token_salt,
    string $refresh_token_salt,
    app\modules\user\activeQueries\Getting $getting_adapter
  )
  {
    $this->password_salt = $password_salt;
    $this->access_token_salt = $access_token_salt;
    $this->refresh_token_salt = $refresh_token_salt;
    $this->getting_adapter = $getting_adapter;
  }

  public function auth(
    array $args
  ): app\common\errors\Infrastructure | app\common\errors\Domain | app\modules\user\models\entities\Session
  {
    $maybe_email = app\modules\user\models\valueObjects\Email::build($args);
    if ($maybe_email instanceof app\common\errors\Domain) {
      return $maybe_email;
    }

    $maybe_user = $this->getting_adapter->get($maybe_email);
    if ($maybe_user instanceof app\common\errors\Infrastructure) {
      return $maybe_user;
    }

    $maybe_true = $maybe_user->validatePassword(
      array_merge($args, ['salt' => $this->password_salt])
    );
    if ($maybe_true instanceof app\common\errors\Domain) {
      return $maybe_true;
    }

    return app\modules\user\models\entities\Session::build([
      'id' => $maybe_user->getId(),
      'access_token_salt' => $this->access_token_salt,
      'refresh_token_salt' => $this->refresh_token_salt
    ]);
  }
}
