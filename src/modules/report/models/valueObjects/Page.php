<?php declare(strict_types=1);

namespace app\modules\report\models\valueObjects;

use app;

class Page extends app\common\valueObjects\ValueObject
{
  public function __construct(string $email)
  {
    parent::__construct($email);
  }

  public static function build(array $args): Page | app\common\errors\Domain
  {
    if (!isset($args['page'])) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (gettype($args['page']) != 'integer') {
      return new app\common\errors\Domain('Invalid page');
    }

    if ($args['page'] < 1) {
      return new app\common\errors\Domain('Invalid page');
    }

    return new Page($args['page']);
  }
}
