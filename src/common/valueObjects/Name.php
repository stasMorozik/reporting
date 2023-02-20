<?php declare(strict_types=1);

namespace app\common\valueObjects;

use app;

class Name extends app\common\valueObjects\ValueObject
{
  protected function __construct(string $name)
  {
    parent::__construct($name);
  }

  public static function build(array $args): Name | app\common\errors\Domain
  {
    if (!isset($args['name'])) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (mb_strlen($args['name'], 'UTF-8') < 2 || mb_strlen($args['name'], 'UTF-8') > 30) {
      return new app\common\errors\Domain('Invalid name');
    }

    if (!preg_match("/^[a-zA-Z\s]+$/i", $args['name'])) {
      return new app\common\errors\Domain('Invalid name');
    }

    return new Name($args['name']);
  }
}
