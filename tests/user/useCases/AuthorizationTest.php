<?php declare(strict_types=1);

namespace tests\user\useCases;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;

class AuthorizationTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\user\activeQueries\GettingById $getting_adapter;
  protected static app\modules\user\useCases\Authorization $authorization_use_case;

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

  protected function tearDown(): void
  {
    self::$db->createCommand('DELETE FROM users')->execute();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = tests\Bootstrap::factory();
    self::$getting_adapter = new app\modules\user\activeQueries\GettingById(self::$db);
    self::$authorization_use_case = new app\modules\user\useCases\Authorization(
      self::$access_token_salt,
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
    self::$session = app\modules\user\models\entities\Session::build([
      'access_token_salt' => self::$access_token_salt,
      'refresh_token_salt' => self::$refresh_token_salt,
      'id' => self::$user->getId()
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
    $maybe_session = self::$authorization_use_case->auth([
      'access_token' => self::$session->getAccessToken(),
    ]);

    $this->assertInstanceOf(
      app\modules\user\models\Entity::class,
      $maybe_session
    );
  }

  public function testInvalidToken(): void
  {
    $maybe_session = self::$authorization_use_case->auth([
      'access_token' => 'Invalid token',
    ]);

    $this->assertInstanceOf(
      app\common\errors\Domain::class,
      $maybe_session
    );
  }
}
