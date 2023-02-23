<?php declare(strict_types=1);

namespace app\modules\user\activeQueries\interfaces;

use app;

interface GettingById
{
  public function get(
    string $id
  ): app\modules\user\models\Entity | app\common\errors\Infrastructure;
}
