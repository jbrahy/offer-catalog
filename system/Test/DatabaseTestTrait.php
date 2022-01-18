<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Test;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Test\Constraints\SeeInDatabase;
use Config\Database;
use Config\Migrations;
use Config\Services;

/**
 * DatabaseTestTrait
 *
 * Provides functionality for refreshing/seeding
 * the database during testing.
 *
 * @mixin CIUnitTestCase
 */
trait DatabaseTestTrait {
	/**
	 * Is db migration done once or more than once?
	 *
	 * @var boolean
	 */
	private static $doneMigration = FALSE;

	/**
	 * Is seeding done once or more than once?
	 *
	 * @var boolean
	 */
	private static $doneSeed = FALSE;

	//--------------------------------------------------------------------
	// Staging
	//--------------------------------------------------------------------

	/**
	 * Reset $doneMigration and $doneSeed
	 *
	 * @afterClass
	 */
	public static function resetMigrationSeedCount()
	{
		self::$doneMigration = FALSE;
		self::$doneSeed = FALSE;
	}

	/**
	 * Loads the Builder class appropriate for the current database.
	 *
	 * @param string $tableName
	 *
	 * @return BaseBuilder
	 */
	public function loadBuilder(string $tableName)
	{
		$builderClass = str_replace('Connection', 'Builder', get_class($this->db));

		return new $builderClass($tableName, $this->db);
	}

	/**
	 * Fetches a single column from a database row with criteria
	 * matching $where.
	 *
	 * @param string $table
	 * @param string $column
	 * @param array $where
	 *
	 * @return boolean
	 * @throws DatabaseException
	 */
	public function grabFromDatabase(string $table, string $column, array $where)
	{
		$query = $this->db->table($table)->select($column)->where($where)->get();

		$query = $query->getRow();

		return $query->$column ?? FALSE;
	}

	//--------------------------------------------------------------------
	// Migrations
	//--------------------------------------------------------------------

	/**
	 * Asserts that records that match the conditions in $where DO
	 * exist in the database.
	 *
	 * @param string $table
	 * @param array $where
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public function seeInDatabase(string $table, array $where)
	{
		$constraint = new SeeInDatabase($this->db, $where);
		static::assertThat($table, $constraint);
	}

	/**
	 * Asserts that records that match the conditions in $where do
	 * not exist in the database.
	 *
	 * @param string $table
	 * @param array $where
	 *
	 * @return void
	 */
	public function dontSeeInDatabase(string $table, array $where)
	{
		$count = $this->db->table($table)->where($where)->countAllResults();

		$this->assertTrue($count === 0, 'Row was found in database');
	}

	/**
	 * Inserts a row into to the database. This row will be removed
	 * after the test has run.
	 *
	 * @param string $table
	 * @param array $data
	 *
	 * @return boolean
	 */
	public function hasInDatabase(string $table, array $data)
	{
		$this->insertCache[] = [
			$table, $data,
		];

		return $this->db->table($table)->insert($data);
	}

	//--------------------------------------------------------------------
	// Seeds
	//--------------------------------------------------------------------

	/**
	 * Asserts that the number of rows in the database that match $where
	 * is equal to $expected.
	 *
	 * @param integer $expected
	 * @param string $table
	 * @param array $where
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public function seeNumRecords(int $expected, string $table, array $where)
	{
		$count = $this->db->table($table)->where($where)->countAllResults();

		$this->assertEquals($expected, $count, 'Wrong number of matching rows in database.');
	}

	/**
	 * Load any database test dependencies.
	 */
	public function loadDependencies()
	{
		if ($this->db === NULL)
		{
			$this->db = Database::connect($this->DBGroup);
			$this->db->initialize();
		}

		if ($this->migrations === NULL)
		{
			// Ensure that we can run migrations
			$config = new Migrations();
			$config->enabled = TRUE;

			$this->migrations = Services::migrations($config, $this->db);
			$this->migrations->setSilent(FALSE);
		}

		if ($this->seeder === NULL)
		{
			$this->seeder = Database::seeder($this->DBGroup);
			$this->seeder->setSilent(TRUE);
		}
	}

	/**
	 * Seeds that database with a specific seeder.
	 *
	 * @param string $name
	 *
	 * @return void
	 */
	public function seed(string $name)
	{
		$this->seeder->call($name);
	}

	//--------------------------------------------------------------------
	// Utility
	//--------------------------------------------------------------------

	/**
	 * Runs the trait set up methods.
	 */
	protected function setUpDatabase()
	{
		$this->loadDependencies();
		$this->setUpMigrate();
		$this->setUpSeed();
	}

	/**
	 * Migrate on setUp
	 */
	protected function setUpMigrate()
	{
		if ($this->migrateOnce === FALSE || self::$doneMigration === FALSE)
		{
			if ($this->refresh === TRUE)
			{
				$this->regressDatabase();

				// Reset counts on faked items
				Fabricator::resetCounts();
			}

			$this->migrateDatabase();
		}
	}

	/**
	 * Regress migrations as defined by the class
	 */
	protected function regressDatabase()
	{
		if ($this->migrate === FALSE)
		{
			return;
		}

		// If no namespace was specified then rollback all
		if (empty($this->namespace))
		{
			$this->migrations->setNamespace(NULL);
			$this->migrations->regress(0, 'tests');
		} // Regress each specified namespace
		else
		{
			$namespaces = is_array($this->namespace) ? $this->namespace : [$this->namespace];

			foreach ($namespaces as $namespace)
			{
				$this->migrations->setNamespace($namespace);
				$this->migrations->regress(0, 'tests');
			}
		}
	}

	/**
	 * Run migrations as defined by the class
	 */
	protected function migrateDatabase()
	{
		if ($this->migrate === FALSE)
		{
			return;
		}

		// If no namespace was specified then migrate all
		if (empty($this->namespace))
		{
			$this->migrations->setNamespace(NULL);
			$this->migrations->latest('tests');
			self::$doneMigration = TRUE;
		} // Run migrations for each specified namespace
		else
		{
			$namespaces = is_array($this->namespace) ? $this->namespace : [$this->namespace];

			foreach ($namespaces as $namespace)
			{
				$this->migrations->setNamespace($namespace);
				$this->migrations->latest('tests');
				self::$doneMigration = TRUE;
			}
		}
	}

	//--------------------------------------------------------------------
	// Assertions
	//--------------------------------------------------------------------

	/**
	 * Seed on setUp
	 */
	protected function setUpSeed()
	{
		if ($this->seedOnce === FALSE || self::$doneSeed === FALSE)
		{
			$this->runSeeds();
		}
	}

	/**
	 * Run seeds as defined by the class
	 */
	protected function runSeeds()
	{
		if ( ! empty($this->seed))
		{
			if ( ! empty($this->basePath))
			{
				$this->seeder->setPath(rtrim($this->basePath, '/') . '/Seeds');
			}

			$seeds = is_array($this->seed) ? $this->seed : [$this->seed];
			foreach ($seeds as $seed)
			{
				$this->seed($seed);
			}
		}

		self::$doneSeed = TRUE;
	}

	/**
	 * Runs the trait set up methods.
	 */
	protected function tearDownDatabase()
	{
		$this->clearInsertCache();
	}

	/**
	 * Removes any rows inserted via $this->hasInDatabase()
	 */
	protected function clearInsertCache()
	{
		if ( ! empty($this->insertCache))
		{
			foreach ($this->insertCache as $row)
			{
				$this->db->table($row[0])->where($row[1])->delete();
			}
		}
	}
}
