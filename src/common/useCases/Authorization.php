<?php declare(strict_types=1);

namespace app\common\useCases;

use app;

class Authorization extends app\modules\user\useCases\Authorization
{
  public function __construct(
    app\modules\user\activeQueries\interfaces\GettingById $getting_adapter
  )
  {
    parent::__construct(' ', $getting_adapter);
  }

  public function auth(
    array $args
  ): app\common\errors\Infrastructure | app\common\errors\Domain | app\modules\user\models\Entity
  {
    if (!isset($args['id'])) {
      return new app\common\errors\Domain('Invalid argument');
    }

    return $this->getting_adapter->get($args['id']);
  }
}
