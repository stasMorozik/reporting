<?php declare(strict_types=1);

namespace app\common\valueObjects;

use app;

abstract class ValueObject
{
  private $value;

  public function __construct($value)
  {
    $this->value = $value;
  }

  public function getValue()
  {
    return $this->value;
  }

  abstract public static function build(array $args): ValueObject | app\common\errors\Domain;
}
