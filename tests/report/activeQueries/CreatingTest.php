<?php declare(strict_types=1);

namespace tests\report\activeQueries;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;
use DateTime;

class CreatingTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\report\activeQueries\Creating $creating_adapter;

  protected static $name = 'Name';
  protected static $surname = 'Surname';
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $password_salt = 'some_secret';
  protected static $role = 'User';
  protected static $department = 'It';

  protected static app\modules\user\models\Entity $user;
  protected static app\modules\report\models\Entity $report;

  protected function tearDown(): void
  {
    self::$db->createCommand('DELETE FROM users')->execute();
  }

  public static function setUpBeforeClass(): void
  {
    self::$db = tests\Bootstrap::factory();
    self::$creating_adapter = new app\modules\report\activeQueries\Creating(self::$db);
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

    self::$report = app\modules\report\models\Entity::build([
      'title' => 'Some title',
      'description' => 'Some description',
      'owner' => self::$user
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

  public function testCreate(): void
  {
    $maybe_true = self::$creating_adapter->create(self::$report);

    $this->assertSame(
      true,
      $maybe_true
    );
  }
}
