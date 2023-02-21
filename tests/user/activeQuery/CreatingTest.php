<?php declare(strict_types=1);

namespace tests\user\activeQuery;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;

class CreatingTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\user\activeQuery\Creating $creating_adapter;

  protected static $name = 'Name';
  protected static $surname = 'Surname';
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $salt = 'some_secret';
  protected static $role = 'User';
  protected static $department = 'It';

  protected static app\modules\user\models\Entity $user;

  protected function tearDown(): void
  {
    self::$db->createCommand('DELETE FROM users')->execute();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = tests\Bootstrap::factory();
    self::$creating_adapter = new app\modules\user\activeQuery\Creating(self::$db);
    self::$user = app\modules\user\models\Entity::build([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department,
      'salt' => self::$salt
    ]);
  }

  public function testCreate(): void
  {
    $maybe_true = self::$creating_adapter->create(self::$user);

    $this->assertSame(
      true,
      $maybe_true
    );
  }

  public function testAlreadyExist(): void
  {
    self::$creating_adapter->create(self::$user);

    $maybe_true = self::$creating_adapter->create(self::$user);

    $this->assertInstanceOf(
      app\common\errors\Infrastructure::class,
      $maybe_true
    );
  }
}
