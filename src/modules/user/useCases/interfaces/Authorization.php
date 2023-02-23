<?php declare(strict_types=1);

namespace app\modules\user\useCases\interfaces;

use app;

interface Authorization
{
  public function auth(
    array $args
  ): app\common\errors\Infrastructure | app\common\errors\Domain | app\modules\user\models\Entity;
}
