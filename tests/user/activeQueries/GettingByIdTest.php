<?php declare(strict_types=1);

namespace tests\user\activeQueries;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use yii;
use app;
use tests;

class GettingByIdTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\user\activeQueries\GettingById $getting_adapter;

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
    self::$getting_adapter = new app\modules\user\activeQueries\GettingById(self::$db);
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

  public function testGet(): void
  {
    $maybe_user = self::$getting_adapter->get(
      self::$user->getId()
    );

    $this->assertInstanceOf(
      app\modules\user\models\Entity::class,
      $maybe_user
    );
  }

  public function testNotFound(): void
  {
    $maybe_user = self::$getting_adapter->get(
      Uuid::uuid4()->toString()
    );

    $this->assertInstanceOf(
      app\common\errors\Infrastructure::class,
      $maybe_user
    );
  }
}
