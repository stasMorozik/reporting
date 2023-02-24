<?php declare(strict_types=1);

namespace app\common\errors;

use app;

class HaveNotRight extends app\common\errors\Error
{
  public function __construct(string $message = 'You have not right')
  {
    parent::__construct($message);
  }
}
