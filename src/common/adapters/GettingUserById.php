<?php declare(strict_types=1);

namespace app\common\adapters;

use app;

class GettingUserById implements app\modules\user\activeQueries\interfaces\GettingById
{
  private array $users;

  public function __construct(
    &$users
  )
  {
    $this->users = &$users;
  }

  public function get(
    string $id
  ): app\modules\user\models\Entity | app\common\errors\Infrastructure
  {
    if (!isset($this->users[$id])) {
      return new app\common\errors\Infrastructure('User not found');
    }

    return $this->users[$id];
  }
}

