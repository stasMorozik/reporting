<?php declare(strict_types=1);

namespace tests\user\useCases;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;

class RegistrationTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\user\activeQuery\Creating $creating_adapter;
  protected static app\modules\user\useCases\Registration $registration_use_case;

  protected static $name = 'Name';
  protected static $surname = 'Surname';
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $salt = 'some_secret';
  protected static $role = 'User';
  protected static $department = 'It';

  protected static $password_salt = 'some_salt';

  protected function tearDown(): void
  {
    self::$db->createCommand('DELETE FROM users')->execute();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = tests\Bootstrap::factory();
    self::$creating_adapter = new app\modules\user\activeQuery\Creating(self::$db);
    self::$registration_use_case = new app\modules\user\useCases\Registration(
      self::$password_salt,
      self::$creating_adapter
    );
  }

  public function testRegistration(): void
  {
    $maybe_true = self::$registration_use_case->registry([
      'email' => self::$email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$department
    ]);

    $this->assertSame(
      true,
      $maybe_true
    );
  }
}
