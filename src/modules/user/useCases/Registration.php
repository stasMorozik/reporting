<?php declare(strict_types=1);

namespace app\modules\user\useCases;

use app;

class Registration
{
  private app\modules\user\activeQuery\Creating $creating_adapter;
  private string $password_salt;

  public function __construct(
    string $password_salt,
    app\modules\user\activeQuery\Creating $creating_adapter
  )
  {
    $this->creating_adapter = $creating_adapter;
    $this->password_salt = $password_salt;
  }

  public function registry(array $args)
  {
    $maybe_user = app\modules\user\models\Entity::build(array_merge($args, ['salt' => $this->password_salt]));

    if ($maybe_user instanceof app\common\errors\Domain) {
      return $maybe_user;
    }

    $this->creating_adapter->create($maybe_user);
  }
}
