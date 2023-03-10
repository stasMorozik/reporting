<?php declare(strict_types=1);

namespace tests\report\activeQueries;

use PHPUnit\Framework\TestCase;
use yii;
use app;
use tests;
use DateTime;

class GettingListTest extends TestCase
{
  protected static yii\db\Connection $db;
  protected static app\modules\report\activeQueries\GettingList $getting_adapter;

  protected static $name = 'Name';
  protected static $surname = 'Surname';
  protected static $email = 'name@gmail.com';
  protected static $other_email = 'name1@gmail.com';
  protected static $password = '12345';
  protected static $salt = 'some_secret';
  protected static $role = 'User';
  protected static $department = 'It';
  protected static $other_department = 'Store';

  protected static app\modules\user\models\Entity $user;
  protected static app\modules\user\models\Entity $other_user;
  protected static app\modules\report\models\Entity $report;
  protected static app\modules\report\models\Entity $other_report;

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

    self::$other_user = app\modules\user\models\Entity::build([
      'email' => self::$other_email,
      'name' => self::$name,
      'surname' => self::$surname,
      'password' => self::$password,
      'confirm_password' => self::$password,
      'role' => self::$role,
      'department' => self::$other_department,
      'salt' => self::$salt
    ]);

    self::$report = app\modules\report\models\Entity::build([
      'title' => 'Some title',
      'description' => 'Some description',
      'owner' => self::$user
    ]);

    self::$other_report = app\modules\report\models\Entity::build([
      'title' => 'Some title',
      'description' => 'Some description',
      'owner' => self::$other_user
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

    self::$db->createCommand()->insert('users', [
      'id' => self::$other_user->getId(),
      'created' => self::$other_user->getCreated()->format('Y-m-d h:m'),
      'name' => self::$other_user->getName()->getValue(),
      'surname' => self::$other_user->getSurname()->getValue(),
      'email' => self::$other_user->getEmail()->getValue(),
      'password' => self::$other_user->getPassword()->getValue(),
      'role' => self::$other_user->getRole()->getValue(),
      'department' => self::$other_user->getDepartment()->getValue()
    ])->execute();

    self::$db->createCommand()->insert('reports', [
      'id' => self::$report->getId(),
      'created' => self::$report->getCreated()->format('Y-m-d h:m'),
      'title' => self::$report->getTitle(),
      'description' => self::$report->getDescription(),
      'owner_id' => self::$user->getId()
    ])->execute();

    self::$db->createCommand()->insert('reports', [
      'id' => self::$other_report->getId(),
      'created' => self::$other_report->getCreated()->format('Y-m-d h:m'),
      'title' => self::$other_report->getTitle(),
      'description' => self::$other_report->getDescription(),
      'owner_id' => self::$other_user->getId()
    ])->execute();

    $transaction->commit();
  }

  public function testGetList(): void
  {
    $rows = self::$getting_adapter->get(
      new app\modules\report\models\valueObjects\Page(0),
      new app\modules\report\models\valueObjects\Limit(10),
      new app\modules\user\models\valueObjects\Email(self::$email),
      new app\common\valueObjects\Name(self::$surname),
      new app\modules\user\models\valueObjects\Department(
        app\modules\user\models\valueObjects\Department::IT
      ),
      new DateTime(),
      new DateTime()
    );

    $this->assertSame(
      true,
      is_array($rows)
    );

    $report = array_pop($rows);

    $this->assertSame(
      self::$email,
      $report->getOwner()->getEmail()->getValue()
    );
  }
}
