<?php declare(strict_types=1);

namespace tests\user\useCases;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;

class AuthenticationTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\user\activeQueries\Getting $getting_adapter;
  protected static app\modules\user\useCases\Authentication $authentication_use_case;

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

  protected function tearDown(): void
  {
    self::$db->createCommand('DELETE FROM users')->execute();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = tests\Bootstrap::factory();
    self::$getting_adapter = new app\modules\user\activeQueries\Getting(self::$db);
    self::$authentication_use_case = new app\modules\user\useCases\Authentication(
      self::$password_salt,
      self::$access_token_salt,
      self::$refresh_token_salt,
      self::$getting_adapter
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
  }

  protected function setUp(): void
  {
    $transaction = self::$db->beginTransaction();

    self::$db->createCommand()->insert('users', [
      'id' => self::$user->getId(),
      'created' => self::$user->getCreated()->format('Y-m-d h:m'),
      'name' => self::$user->getName()->getValue(),
      'surname' => self::$user->getSurname()->getValue(),
      'email' => self::$user->getEmail()->getValue(),
      'password' => self::$user->getPassword()->getValue(),
      'role' => self::$user->getRole()->getValue(),
      'department' => self::$user->getDepartment()->getValue()
    ])->execute();

    $transaction->commit();
  }

  public function testAuth(): void
  {
    $maybe_session = self::$authentication_use_case->auth([
      'email' => self::$email,
      'password' => self::$password
    ]);

    $this->assertInstanceOf(
      app\modules\user\models\entities\Session::class,
      $maybe_session
    );
  }

  public function testUserNotFound(): void
  {
    $maybe_session = self::$authentication_use_case->auth([
      'email' => 'test@gmail.com',
      'password' => self::$password
    ]);

    $this->assertInstanceOf(
      app\common\errors\Infrastructure::class,
      $maybe_session
    );
  }

  public function testInvalidEmail(): void
  {
    $maybe_session = self::$authentication_use_case->auth([
      'email' => 'test!mail.',
      'password' => self::$password
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_session
    );
  }

  public function testWrongPassword(): void
  {
    $maybe_session = self::$authentication_use_case->auth([
      'email' => self::$email,
      'password' => self::$password.'2'
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_session
    );
  }
}
