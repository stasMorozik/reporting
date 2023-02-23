<?php declare(strict_types=1);

namespace tests\report\activeQueries;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;

class GettingListTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\report\activeQueries\GettingList $getting_adapter;

  protected static $name = 'Name';
  protected static $surname = 'Surname';
  protected static $email = 'name@gmail.com';
  protected static $password = '12345';
  protected static $salt = 'some_secret';
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
    self::$getting_adapter = new app\modules\report\activeQueries\GettingList(self::$db);
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

    self::$db->createCommand()->insert('reports', [
      'id' => self::$report->getId(),
      'created' => self::$report->getCreated()->format('Y-m-d h:m'),
      'title' => self::$report->getTitle(),
      'description' => self::$report->getDescription(),
      'owner_id' => self::$user->getId()
    ])->execute();

    $transaction->commit();
  }

  public function testGetList(): void
  {
    $rows = self::$getting_adapter->get();

    $this->assertSame(
      true,
      1
    );
  }
}
