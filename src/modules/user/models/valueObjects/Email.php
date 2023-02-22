<?php declare(strict_types=1);

namespace app\modules\user\models\valueObjects;

use app;

class Email extends app\common\valueObjects\ValueObject
{
  public function __construct(string $email)
  {
    parent::__construct($email);
  }

  public static function build(array $args): Email | app\common\errors\Domain
  {
    if (!isset($args['email'])) {
      return new app\common\errors\Domain('Invalid argument');
    }

    if (!filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
      return new app\common\errors\Domain('Invalid email address');
    }

    return new Email($args['email']);
  }
}
