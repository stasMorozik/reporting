<?php declare(strict_types=1);

namespace tests\user\models\entities;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;

class SessionTest extends TestCase
{
  protected static $access_token_salt = 'some_secret';
  protected static $refresh_token_salt = 'some_secret?';

  public function testEncodeSession(): void
  {
    $session = app\modules\user\models\entities\Session::build([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => 'id'
    ]);

    $maybe_id = app\modules\user\models\entities\Session::decode([
      'access_token_salt' => self::$access_token_salt,
      'access_token' => $session->getAccessToken()
    ]);

    $this->assertSame(
      'id',
      $maybe_id
    );
  }
}
