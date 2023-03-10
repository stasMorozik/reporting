<?php declare(strict_types=1);

namespace app\modules\user\useCases;

use app;

class Registration
{
  private app\modules\user\activeQueries\Creating $creating_adapter;
  private string $password_salt;

  public function __construct(
    string $password_salt,
    app\modules\user\activeQueries\Creating $creating_adapter
  )
  {
    $this->creating_adapter = $creating_adapter;
    $this->password_salt = $password_salt;
  }

  public function registry(array $args): app\common\errors\Infrastructure | app\common\errors\Domain | bool
  {
    $maybe_user = app\modules\user\models\Entity::build(array_merge($args, ['salt' => $this->password_salt]));

    if ($maybe_user instanceof app\common\errors\Domain) {
      return $maybe_user;
    }

    return $this->creating_adapter->create($maybe_user);
  }
}
