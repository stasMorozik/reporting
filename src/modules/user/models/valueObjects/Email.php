<?php declare(strict_types=1);

namespace app\modules\user\models\valueObjects;

use app;

class Email extends app\common\valueObjects\ValueObject
{
  public function __construct(string $email)
  {
    parent::__construct($email);
  }

  public static function build($email): Email | app\common\errors\Domain
  {
    return new app\common\errors\Domain('Not implemented');
  }
}
