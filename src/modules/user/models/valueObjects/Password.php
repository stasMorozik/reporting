<?php declare(strict_types=1);

namespace app\modules\user\models\valueObjects;

use app;

class Password extends app\common\valueObjects\ValueObject
{
  protected function __construct(string $password)
  {
    parent::__construct($password);
  }

  public static function build(array $args): Password | app\common\errors\Domain
  {
    if (!isset($args['password']) || !isset($args['salt'])) {
      return new app\common\errors\Domain('Invalid arguments');
    }

    if(preg_match("/[А-я,Ё,ё]/", $args['password'])) {
      return new app\common\errors\Domain('Invalid password');
    }

    if (mb_strlen($args['password'], 'UTF-8') < 5 || mb_strlen($args['password'], 'UTF-8') > 10) {
      return new app\common\errors\Domain('Invalid password');
    }

    return new Password(crypt($args['password'], $args['salt']));
  }

  public function validate(array $args): app\common\errors\Domain | bool
  {
    if (!isset($args['password']) || !isset($args['salt'])) {
      return new app\common\errors\Domain('Invalid arguments');
    }

    if (!hash_equals($this->getValue(), crypt($args['password'], $args['salt']))) {
      return new app\common\errors\Domain('Wrong password');
    }

    return true;
  }
}
