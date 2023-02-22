<?php declare(strict_types=1);

namespace tests\user\useCases;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;

class RefreshSessionTest extends TestCase
{
  protected static app\modules\user\useCases\RefreshSession $refresh_session_use_case;

  protected static $name = 'Name';
  protected static $surname = 'Surname';
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $role = 'User';
  protected static $department = 'It';

  protected static $password_salt = 'some_salt';
  protected static $access_token_salt = 'some_salt?';
  protected static $refresh_token_salt = 'some_salt!';

  protected static app\modules\user\models\Entity $user;
  protected static app\modules\user\models\entities\Session $session;

  public static function setUpBeforeClass(): void
  {
    self::$refresh_session_use_case = new app\modules\user\useCases\RefreshSession(
      self::$access_token_salt,
      self::$refresh_token_salt
    );
    self::$user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department,
      'salt' => self::$password_salt
    ]);
    self::$session = app\modules\user\models\entities\Session::build([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => self::$user->getId()
    ]);
  }

  public function testRefresh(): void
  {
    $maybe_session = self::$refresh_session_use_case->refresh([
      'refresh_token' => self::$session->getRefreshToken(),
    ]);

    $this->assertInstanceOf(
      app\modules\user\models\entities\Session::class,
      $maybe_session
    );
  }

  public function testInvalidToken(): void
  {
    $maybe_session = self::$refresh_session_use_case->refresh([
      'refresh_token' => 'Invalid token',
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_session
    );
  }
}
