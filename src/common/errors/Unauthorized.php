<?php declare(strict_types=1);

namespace app\common\errors;

use app;

class Unauthorized extends app\common\errors\Error
{
  public function __construct(string $message)
  {
    parent::__construct($message);
  }
}
