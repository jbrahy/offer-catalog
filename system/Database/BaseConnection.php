<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Database;

use Closure;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Events\Events;
use Throwable;

/**
 * Class BaseConnection
 */
abstract class BaseConnection implements ConnectionInterface {
	/**
	 * Connection ID
	 *
	 * @var object|resource|boolean
	 */
	public $connID = FALSE;
	/**
	 * Result ID
	 *
	 * @var object|resource|boolean
	 */
	public $resultID = FALSE;
	/**
	 * Protect identifiers flag
	 *
	 * @var boolean
	 */
	public $protectIdentifiers = TRUE;
	/**
	 * Identifier escape character
	 *
	 * @var string|array
	 */
	public $escapeChar = '"';
	/**
	 * ESCAPE statement string
	 *
	 * @var string
	 */
	public $likeEscapeStr = " ESCAPE '%s' ";
	/**
	 * ESCAPE character
	 *
	 * @var string
	 */
	public $likeEscapeChar = '!';
	/**
	 * Holds previously looked up data
	 * for performance reasons.
	 *
	 * @var array
	 */
	public $dataCache = [];
	/**
	 * Transaction enabled flag
	 *
	 * @var boolean
	 */
	public $transEnabled = TRUE;
	/**
	 * Strict transaction mode flag
	 *
	 * @var boolean
	 */
	public $transStrict = TRUE;
	/**
	 * Data Source Name / Connect string
	 *
	 * @var string
	 */
	protected $DSN;
	/**
	 * Database port
	 *
	 * @var integer|string
	 */
	protected $port = '';
	/**
	 * Hostname
	 *
	 * @var string
	 */
	protected $hostname;
	/**
	 * Username
	 *
	 * @var string
	 */
	protected $username;
	/**
	 * Password
	 *
	 * @var string
	 */
	protected $password;
	/**
	 * Database name
	 *
	 * @var string
	 */
	protected $database;
	/**
	 * Database driver
	 *
	 * @var string
	 */
	protected $DBDriver = 'MySQLi';
	/**
	 * Sub-driver
	 *
	 * @used-by CI_DB_pdo_driver
	 * @var     string
	 */
	protected $subdriver;
	/**
	 * Table prefix
	 *
	 * @var string
	 */
	protected $DBPrefix = '';

	//--------------------------------------------------------------------
	/**
	 * Persistent connection flag
	 *
	 * @var boolean
	 */
	protected $pConnect = FALSE;
	/**
	 * Debug flag
	 *
	 * Whether to display error messages.
	 *
	 * @var boolean
	 */
	protected $DBDebug = FALSE;
	/**
	 * Character set
	 *
	 * @var string
	 */
	protected $charset = 'utf8';
	/**
	 * Collation
	 *
	 * @var string
	 */
	protected $DBCollat = 'utf8_general_ci';
	/**
	 * Swap Prefix
	 *
	 * @var string
	 */
	protected $swapPre = '';
	/**
	 * Encryption flag/data
	 *
	 * @var mixed
	 */
	protected $encrypt = FALSE;
	/**
	 * Compression flag
	 *
	 * @var boolean
	 */
	protected $compress = FALSE;
	/**
	 * Strict ON flag
	 *
	 * Whether we're running in strict SQL mode.
	 *
	 * @var boolean
	 */
	protected $strictOn;
	/**
	 * Settings for a failover connection.
	 *
	 * @var array
	 */
	protected $failover = [];
	/**
	 * The last query object that was executed
	 * on this connection.
	 *
	 * @var mixed
	 */
	protected $lastQuery;
	/**
	 * List of reserved identifiers
	 *
	 * Identifiers that must NOT be escaped.
	 *
	 * @var array
	 */
	protected $reservedIdentifiers = ['*'];
	/**
	 * Microtime when connection was made
	 *
	 * @var float
	 */
	protected $connectTime;
	/**
	 * How long it took to establish connection.
	 *
	 * @var float
	 */
	protected $connectDuration;
	/**
	 * If true, no queries will actually be
	 * ran against the database.
	 *
	 * @var boolean
	 */
	protected $pretend = FALSE;
	/**
	 * Transaction depth level
	 *
	 * @var integer
	 */
	protected $transDepth = 0;

	/**
	 * Transaction status flag
	 *
	 * Used with transactions to determine if a rollback should occur.
	 *
	 * @var boolean
	 */
	protected $transStatus = TRUE;

	/**
	 * Transaction failure flag
	 *
	 * Used with transactions to determine if a transaction has failed.
	 *
	 * @var boolean
	 */
	protected $transFailure = FALSE;

	/**
	 * Array of table aliases.
	 *
	 * @var array
	 */
	protected $aliasedTables = [];

	/**
	 * Query Class
	 *
	 * @var string
	 */
	protected $queryClass = 'CodeIgniter\\Database\\Query';

	//--------------------------------------------------------------------

	/**
	 * Saves our connection settings.
	 *
	 * @param array $params
	 */
	public function __construct(array $params)
	{
		foreach ($params as $key => $value)
		{
			$this->$key = $value;
		}

		$queryClass = str_replace('Connection', 'Query', static::class);

		if (class_exists($queryClass))
		{
			$this->queryClass = $queryClass;
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Close the database connection.
	 *
	 * @return void
	 */
	public function close()
	{
		if ($this->connID)
		{
			$this->_close();
			$this->connID = FALSE;
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Connect to the database.
	 *
	 * @param boolean $persistent
	 *
	 * @return mixed
	 */
	abstract public function connect(bool $persistent = FALSE);

	/**
	 * Set DB Prefix
	 *
	 * Set's the DB Prefix to something new without needing to reconnect
	 *
	 * @param string $prefix The prefix
	 *
	 * @return string
	 */
	public function setPrefix(string $prefix = ''): string
	{
		return $this->DBPrefix = $prefix;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the database prefix.
	 *
	 * @return string
	 */
	public function getPrefix(): string
	{
		return $this->DBPrefix;
	}

	//--------------------------------------------------------------------

	/**
	 * Sets the Table Aliases to use. These are typically
	 * collected during use of the Builder, and set here
	 * so queries are built correctly.
	 *
	 * @param array $aliases
	 *
	 * @return $this
	 */
	public function setAliasedTables(array $aliases)
	{
		$this->aliasedTables = $aliases;

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Create a persistent database connection.
	 *
	 * @return mixed
	 */
	public function persistentConnect()
	{
		return $this->connect(TRUE);
	}

	/**
	 * Add a table alias to our list.
	 *
	 * @param string $table
	 *
	 * @return $this
	 */
	public function addTableAlias(string $table)
	{
		if ( ! in_array($table, $this->aliasedTables, TRUE))
		{
			$this->aliasedTables[] = $table;
		}

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Keep or establish the connection if no queries have been sent for
	 * a length of time exceeding the server's idle timeout.
	 *
	 * @return mixed
	 */
	abstract public function reconnect();

	/**
	 * Disable Transactions
	 *
	 * This permits transactions to be disabled at run-time.
	 *
	 * @return void
	 */
	public function transOff()
	{
		$this->transEnabled = FALSE;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the actual connection object. If both a 'read' and 'write'
	 * connection has been specified, you can pass either term in to
	 * get that connection. If you pass either alias in and only a single
	 * connection is present, it must return the sole connection.
	 *
	 * @param string|null $alias
	 *
	 * @return mixed
	 */
	public function getConnection(string $alias = NULL)
	{
		//@todo work with read/write connections
		return $this->connID;
	}

	/**
	 * Enable/disable Transaction Strict Mode
	 *
	 * When strict mode is enabled, if you are running multiple groups of
	 * transactions, if one group fails all subsequent groups will be
	 * rolled back.
	 *
	 * If strict mode is disabled, each group is treated autonomously,
	 * meaning a failure of one group will not affect any others
	 *
	 * @param boolean $mode = true
	 *
	 * @return $this
	 */
	public function transStrict(bool $mode = TRUE)
	{
		$this->transStrict = $mode;

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Start Transaction
	 *
	 * @param boolean $testMode = FALSE
	 *
	 * @return boolean
	 */
	public function transStart(bool $testMode = FALSE): bool
	{
		if ( ! $this->transEnabled)
		{
			return FALSE;
		}

		return $this->transBegin($testMode);
	}

	/**
	 * Returns the name of the current database being used.
	 *
	 * @return string
	 */
	public function getDatabase(): string
	{
		return empty($this->database) ? '' : $this->database;
	}

	//--------------------------------------------------------------------

	/**
	 * Select a specific database table to use.
	 *
	 * @param string $databaseName
	 *
	 * @return mixed
	 */
	abstract public function setDatabase(string $databaseName);

	/**
	 * Begin Transaction
	 *
	 * @param boolean $testMode
	 *
	 * @return boolean
	 */
	public function transBegin(bool $testMode = FALSE): bool
	{
		if ( ! $this->transEnabled)
		{
			return FALSE;
		}

		// When transactions are nested we only begin/commit/rollback the outermost ones
		if ($this->transDepth > 0)
		{
			$this->transDepth++;
			return TRUE;
		}

		if (empty($this->connID))
		{
			$this->initialize();
		}

		// Reset the transaction failure flag.
		// If the $test_mode flag is set to TRUE transactions will be rolled back
		// even if the queries produce a successful result.
		$this->transFailure = ($testMode === TRUE);

		if ($this->_transBegin())
		{
			$this->transDepth++;
			return TRUE;
		}

		return FALSE;
	}

	//--------------------------------------------------------------------

	/**
	 * Initializes the database connection/settings.
	 *
	 * @return mixed|void
	 * @throws DatabaseException
	 */
	public function initialize()
	{
		/* If an established connection is available, then there's
		 * no need to connect and select the database.
		 *
		 * Depending on the database driver, conn_id can be either
		 * boolean TRUE, a resource or an object.
		 */
		if ($this->connID)
		{
			return;
		}

		//--------------------------------------------------------------------

		$this->connectTime = microtime(TRUE);
		$connectionErrors = [];

		try
		{
			// Connect to the database and set the connection ID
			$this->connID = $this->connect($this->pConnect);
		} catch (Throwable $e)
		{
			$connectionErrors[] = sprintf('Main connection [%s]: %s', $this->DBDriver, $e->getMessage());
			log_message('error', 'Error connecting to the database: ' . $e->getMessage());
		}

		// No connection resource? Check if there is a failover else throw an error
		if ( ! $this->connID)
		{
			// Check if there is a failover set
			if ( ! empty($this->failover) && is_array($this->failover))
			{
				// Go over all the failovers
				foreach ($this->failover as $index => $failover)
				{
					// Replace the current settings with those of the failover
					foreach ($failover as $key => $val)
					{
						if (property_exists($this, $key))
						{
							$this->$key = $val;
						}
					}

					try
					{
						// Try to connect
						$this->connID = $this->connect($this->pConnect);
					} catch (Throwable $e)
					{
						$connectionErrors[] = sprintf('Failover #%d [%s]: %s', ++$index, $this->DBDriver, $e->getMessage());
						log_message('error', 'Error connecting to the database: ' . $e->getMessage());
					}

					// If a connection is made break the foreach loop
					if ($this->connID)
					{
						break;
					}
				}
			}

			// We still don't have a connection?
			if ( ! $this->connID)
			{
				throw new DatabaseException(sprintf('Unable to connect to the database.%s%s', PHP_EOL, implode(PHP_EOL, $connectionErrors)));
			}
		}

		$this->connectDuration = microtime(TRUE) - $this->connectTime;
	}

	//--------------------------------------------------------------------

	/**
	 * The name of the platform in use (MySQLi, mssql, etc)
	 *
	 * @return string
	 */
	public function getPlatform(): string
	{
		return $this->DBDriver;
	}

	//--------------------------------------------------------------------

	/**
	 * Lets you retrieve the transaction flag to determine if it has failed
	 *
	 * @return boolean
	 */
	public function transStatus(): bool
	{
		return $this->transStatus;
	}

	/**
	 * Returns a string containing the version of the database being used.
	 *
	 * @return string
	 */
	abstract public function getVersion(): string;

	//--------------------------------------------------------------------

	/**
	 * Creates a prepared statement with the database that can then
	 * be used to execute multiple statements against. Within the
	 * closure, you would build the query in any normal way, though
	 * the Query Builder is the expected manner.
	 *
	 * Example:
	 *    $stmt = $db->prepare(function($db)
	 *           {
	 *             return $db->table('users')
	 *                   ->where('id', 1)
	 *                     ->get();
	 *           })
	 *
	 * @param Closure $func
	 * @param array $options Passed to the prepare() method
	 *
	 * @return BasePreparedQuery|null
	 */
	public function prepare(Closure $func, array $options = [])
	{
		if (empty($this->connID))
		{
			$this->initialize();
		}

		$this->pretend();

		$sql = $func($this);

		$this->pretend(FALSE);

		if ($sql instanceof QueryInterface)
		{
			// @phpstan-ignore-next-line
			$sql = $sql->getOriginalQuery();
		}

		$class = str_ireplace('Connection', 'PreparedQuery', get_class($this));
		/**
		 * @var BasePreparedQuery $class
		 */
		$class = new $class($this);

		return $class->prepare($sql, $options);
	}

	/**
	 * Allows the engine to be set into a mode where queries are not
	 * actually executed, but they are still generated, timed, etc.
	 *
	 * This is primarily used by the prepared query functionality.
	 *
	 * @param boolean $pretend
	 *
	 * @return $this
	 */
	public function pretend(bool $pretend = TRUE)
	{
		$this->pretend = $pretend;

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a string representation of the last query's statement object.
	 *
	 * @return string
	 */
	public function showLastQuery(): string
	{
		return (string)$this->lastQuery;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the time we started to connect to this database in
	 * seconds with microseconds.
	 *
	 * Used by the Debug Toolbar's timeline.
	 *
	 * @return float|null
	 */
	public function getConnectStart(): ?float
	{
		return $this->connectTime;
	}

	/**
	 * Returns the number of seconds with microseconds that it took
	 * to connect to the database.
	 *
	 * Used by the Debug Toolbar's timeline.
	 *
	 * @param integer $decimals
	 *
	 * @return string
	 */
	public function getConnectDuration(int $decimals = 6): string
	{
		return number_format($this->connectDuration, $decimals);
	}

	/**
	 * Prepends a database prefix if one exists in configuration
	 *
	 * @param string $table the table
	 *
	 * @return string
	 *
	 * @throws DatabaseException
	 */
	public function prefixTable(string $table = ''): string
	{
		if ($table === '')
		{
			throw new DatabaseException('A table name is required for that operation.');
		}

		return $this->DBPrefix . $table;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the total number of rows affected by this query.
	 *
	 * @return integer
	 */
	abstract public function affectedRows(): int;

	//--------------------------------------------------------------------

	/**
	 * Escape LIKE String
	 *
	 * Calls the individual driver for platform
	 * specific escaping for LIKE conditions
	 *
	 * @param string|string[] $str
	 *
	 * @return string|string[]
	 */
	public function escapeLikeString($str)
	{
		return $this->escapeString($str, TRUE);
	}

	//--------------------------------------------------------------------

	/**
	 * Determine if a particular table exists
	 *
	 * @param string $tableName
	 *
	 * @return boolean
	 */
	public function tableExists(string $tableName): bool
	{
		return in_array($this->protectIdentifiers($tableName, TRUE, FALSE, FALSE), $this->listTables(), TRUE);
	}

	//--------------------------------------------------------------------

	/**
	 * Protect Identifiers
	 *
	 * This function is used extensively by the Query Builder class, and by
	 * a couple functions in this class.
	 * It takes a column or table name (optionally with an alias) and inserts
	 * the table prefix onto it. Some logic is necessary in order to deal with
	 * column names that include the path. Consider a query like this:
	 *
	 * SELECT hostname.database.table.column AS c FROM hostname.database.table
	 *
	 * Or a query with aliasing:
	 *
	 * SELECT m.member_id, m.member_name FROM members AS m
	 *
	 * Since the column name can include up to four segments (host, DB, table, column)
	 * or also have an alias prefix, we need to do a bit of work to figure this out and
	 * insert the table prefix (if it exists) in the proper position, and escape only
	 * the correct identifiers.
	 *
	 * @param string|array $item
	 * @param boolean $prefixSingle
	 * @param boolean $protectIdentifiers
	 * @param boolean $fieldExists
	 *
	 * @return string|array
	 */
	public function protectIdentifiers($item, bool $prefixSingle = FALSE, bool $protectIdentifiers = NULL, bool $fieldExists = TRUE)
	{
		if ( ! is_bool($protectIdentifiers))
		{
			$protectIdentifiers = $this->protectIdentifiers;
		}

		if (is_array($item))
		{
			$escapedArray = [];
			foreach ($item as $k => $v)
			{
				$escapedArray[$this->protectIdentifiers($k)] = $this->protectIdentifiers($v, $prefixSingle, $protectIdentifiers, $fieldExists);
			}

			return $escapedArray;
		}

		// This is basically a bug fix for queries that use MAX, MIN, etc.
		// If a parenthesis is found we know that we do not need to
		// escape the data or add a prefix. There's probably a more graceful
		// way to deal with this, but I'm not thinking of it
		//
		// Added exception for single quotes as well, we don't want to alter
		// literal strings.
		if (strcspn($item, "()'") !== strlen($item))
		{
			return $item;
		}

		// Convert tabs or multiple spaces into single spaces
		$item = preg_replace('/\s+/', ' ', trim($item));

		// If the item has an alias declaration we remove it and set it aside.
		// Note: strripos() is used in order to support spaces in table names
		if ($offset = strripos($item, ' AS '))
		{
			$alias = ($protectIdentifiers) ? substr($item, $offset, 4) . $this->escapeIdentifiers(substr($item, $offset + 4)) : substr($item, $offset);
			$item = substr($item, 0, $offset);
		} elseif ($offset = strrpos($item, ' '))
		{
			$alias = ($protectIdentifiers) ? ' ' . $this->escapeIdentifiers(substr($item, $offset + 1)) : substr($item, $offset);
			$item = substr($item, 0, $offset);
		} else
		{
			$alias = '';
		}

		// Break the string apart if it contains periods, then insert the table prefix
		// in the correct location, assuming the period doesn't indicate that we're dealing
		// with an alias. While we're at it, we will escape the components
		if (strpos($item, '.') !== FALSE)
		{
			$parts = explode('.', $item);

			// Does the first segment of the exploded item match
			// one of the aliases previously identified? If so,
			// we have nothing more to do other than escape the item
			//
			// NOTE: The ! empty() condition prevents this method
			//       from breaking when QB isn't enabled.
			if ( ! empty($this->aliasedTables) && in_array($parts[0], $this->aliasedTables, TRUE))
			{
				if ($protectIdentifiers === TRUE)
				{
					foreach ($parts as $key => $val)
					{
						if ( ! in_array($val, $this->reservedIdentifiers, TRUE))
						{
							$parts[$key] = $this->escapeIdentifiers($val);
						}
					}

					$item = implode('.', $parts);
				}

				return $item . $alias;
			}

			// Is there a table prefix defined in the config file? If not, no need to do anything
			if ($this->DBPrefix !== '')
			{
				// We now add the table prefix based on some logic.
				// Do we have 4 segments (hostname.database.table.column)?
				// If so, we add the table prefix to the column name in the 3rd segment.
				if (isset($parts[3]))
				{
					$i = 2;
				}
				// Do we have 3 segments (database.table.column)?
				// If so, we add the table prefix to the column name in 2nd position
				elseif (isset($parts[2]))
				{
					$i = 1;
				}
				// Do we have 2 segments (table.column)?
				// If so, we add the table prefix to the column name in 1st segment
				else
				{
					$i = 0;
				}

				// This flag is set when the supplied $item does not contain a field name.
				// This can happen when this function is being called from a JOIN.
				if ($fieldExists === FALSE)
				{
					$i++;
				}

				// Verify table prefix and replace if necessary
				if ($this->swapPre !== '' && strpos($parts[$i], $this->swapPre) === 0)
				{
					$parts[$i] = preg_replace('/^' . $this->swapPre . '(\S+?)/', $this->DBPrefix . '\\1', $parts[$i]);
				} // We only add the table prefix if it does not already exist
				elseif (strpos($parts[$i], $this->DBPrefix) !== 0)
				{
					$parts[$i] = $this->DBPrefix . $parts[$i];
				}

				// Put the parts back together
				$item = implode('.', $parts);
			}

			if ($protectIdentifiers === TRUE)
			{
				$item = $this->escapeIdentifiers($item);
			}

			return $item . $alias;
		}

		// In some cases, especially 'from', we end up running through
		// protect_identifiers twice. This algorithm won't work when
		// it contains the escapeChar so strip it out.
		$item = trim($item, $this->escapeChar);

		// Is there a table prefix? If not, no need to insert it
		if ($this->DBPrefix !== '')
		{
			// Verify table prefix and replace if necessary
			if ($this->swapPre !== '' && strpos($item, $this->swapPre) === 0)
			{
				$item = preg_replace('/^' . $this->swapPre . '(\S+?)/', $this->DBPrefix . '\\1', $item);
			} // Do we prefix an item with no segments?
			elseif ($prefixSingle === TRUE && strpos($item, $this->DBPrefix) !== 0)
			{
				$item = $this->DBPrefix . $item;
			}
		}

		if ($protectIdentifiers === TRUE && ! in_array($item, $this->reservedIdentifiers, TRUE))
		{
			$item = $this->escapeIdentifiers($item);
		}

		return $item . $alias;
	}

	//--------------------------------------------------------------------

	/**
	 * Escape the SQL Identifiers
	 *
	 * This function escapes column and table names
	 *
	 * @param mixed $item
	 *
	 * @return mixed
	 */
	public function escapeIdentifiers($item)
	{
		if ($this->escapeChar === '' || empty($item) || in_array($item, $this->reservedIdentifiers, TRUE))
		{
			return $item;
		}

		if (is_array($item))
		{
			foreach ($item as $key => $value)
			{
				$item[$key] = $this->escapeIdentifiers($value);
			}

			return $item;
		}

		// Avoid breaking functions and literal values inside queries
		if (ctype_digit($item) || $item[0] === "'" || ($this->escapeChar !== '"' && $item[0] === '"') || strpos($item, '(') !== FALSE)
		{
			return $item;
		}

		static $pregEc = [];

		if (empty($pregEc))
		{
			if (is_array($this->escapeChar))
			{
				$pregEc = [
					preg_quote($this->escapeChar[0], '/'), preg_quote($this->escapeChar[1], '/'), $this->escapeChar[0], $this->escapeChar[1],
				];
			} else
			{
				$pregEc[0] = $pregEc[1] = preg_quote($this->escapeChar, '/');
				$pregEc[2] = $pregEc[3] = $this->escapeChar;
			}
		}

		foreach ($this->reservedIdentifiers as $id)
		{
			if (strpos($item, '.' . $id) !== FALSE)
			{
				return preg_replace('/' . $pregEc[0] . '?([^' . $pregEc[1] . '\.]+)' . $pregEc[1] . '?\./i', $pregEc[2] . '$1' . $pregEc[3] . '.', $item);
			}
		}

		return preg_replace('/' . $pregEc[0] . '?([^' . $pregEc[1] . '\.]+)' . $pregEc[1] . '?(\.)?/i', $pregEc[2] . '$1' . $pregEc[3] . '$2', $item);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an array of table names
	 *
	 * @param boolean $constrainByPrefix = FALSE
	 *
	 * @return boolean|array
	 * @throws DatabaseException
	 */
	public function listTables(bool $constrainByPrefix = FALSE)
	{
		// Is there a cached result?
		if (isset($this->dataCache['table_names']) && $this->dataCache['table_names'])
		{
			return $constrainByPrefix ? preg_grep("/^{$this->DBPrefix}/", $this->dataCache['table_names']) : $this->dataCache['table_names'];
		}

		if (FALSE === ($sql = $this->_listTables($constrainByPrefix)))
		{
			if ($this->DBDebug)
			{
				throw new DatabaseException('This feature is not available for the database you are using.');
			}
			return FALSE;
		}

		$this->dataCache['table_names'] = [];
		$query = $this->query($sql);

		foreach ($query->getResultArray() as $row)
		{
			// Do we know from which column to get the table name?
			if ( ! isset($key))
			{
				if (isset($row['table_name']))
				{
					$key = 'table_name';
				} elseif (isset($row['TABLE_NAME']))
				{
					$key = 'TABLE_NAME';
				} else
				{
					/* We have no other choice but to just get the first element's key.
					 * Due to array_shift() accepting its argument by reference, if
					 * E_STRICT is on, this would trigger a warning. So we'll have to
					 * assign it first.
					 */
					$key = array_keys($row);
					$key = array_shift($key);
				}
			}

			$this->dataCache['table_names'][] = $row[$key];
		}

		return $this->dataCache['table_names'];
	}

	//--------------------------------------------------------------------

	/**
	 * Orchestrates a query against the database. Queries must use
	 * Database\Statement objects to store the query and build it.
	 * This method works with the cache.
	 *
	 * Should automatically handle different connections for read/write
	 * queries if needed.
	 *
	 * @param string $sql
	 * @param mixed ...$binds
	 * @param boolean $setEscapeFlags
	 * @param string $queryClass
	 *
	 * @return BaseResult|Query|boolean
	 *
	 * @todo BC set $queryClass default as null in 4.1
	 */
	public function query(string $sql, $binds = NULL, bool $setEscapeFlags = TRUE, string $queryClass = '')
	{
		$queryClass = $queryClass ?: $this->queryClass;

		if (empty($this->connID))
		{
			$this->initialize();
		}

		/**
		 * @var Query $query
		 */
		$query = new $queryClass($this);

		$query->setQuery($sql, $binds, $setEscapeFlags);

		if ( ! empty($this->swapPre) && ! empty($this->DBPrefix))
		{
			$query->swapPrefix($this->DBPrefix, $this->swapPre);
		}

		$startTime = microtime(TRUE);

		// Always save the last query so we can use
		// the getLastQuery() method.
		$this->lastQuery = $query;

		// Run the query for real
		if ( ! $this->pretend && FALSE === ($this->resultID = $this->simpleQuery($query->getQuery())))
		{
			$query->setDuration($startTime, $startTime);

			// This will trigger a rollback if transactions are being used
			if ($this->transDepth !== 0)
			{
				$this->transStatus = FALSE;
			}

			if ($this->DBDebug)
			{
				// We call this function in order to roll-back queries
				// if transactions are enabled. If we don't call this here
				// the error message will trigger an exit, causing the
				// transactions to remain in limbo.
				while ($this->transDepth !== 0)
				{
					$transDepth = $this->transDepth;
					$this->transComplete();

					if ($transDepth === $this->transDepth)
					{
						log_message('error', 'Database: Failure during an automated transaction commit/rollback!');
						break;
					}
				}

				return FALSE;
			}

			if ( ! $this->pretend)
			{
				// Let others do something with this query.
				Events::trigger('DBQuery', $query);
			}

			return FALSE;
		}

		$query->setDuration($startTime);

		if ( ! $this->pretend)
		{
			// Let others do something with this query
			Events::trigger('DBQuery', $query);
		}

		// If $pretend is true, then we just want to return
		// the actual query object here. There won't be
		// any results to return.
		if ($this->pretend)
		{
			return $query;
		}

		// resultID is not false, so it must be successful
		if ($this->isWriteType($sql))
		{
			return TRUE;
		}

		// query is not write-type, so it must be read-type query; return QueryResult
		$resultClass = str_replace('Connection', 'Result', get_class($this));
		return new $resultClass($this->connID, $this->resultID);
	}

	//--------------------------------------------------------------------

	/**
	 * Performs a basic query against the database. No binding or caching
	 * is performed, nor are transactions handled. Simply takes a raw
	 * query string and returns the database-specific result id.
	 *
	 * @param string $sql
	 *
	 * @return mixed
	 */
	public function simpleQuery(string $sql)
	{
		if (empty($this->connID))
		{
			$this->initialize();
		}

		return $this->execute($sql);
	}

	//--------------------------------------------------------------------

	/**
	 * Complete Transaction
	 *
	 * @return boolean
	 */
	public function transComplete(): bool
	{
		if ( ! $this->transEnabled)
		{
			return FALSE;
		}

		// The query() function will set this flag to FALSE in the event that a query failed
		if ($this->transStatus === FALSE || $this->transFailure === TRUE)
		{
			$this->transRollback();

			// If we are NOT running in strict mode, we will reset
			// the _trans_status flag so that subsequent groups of
			// transactions will be permitted.
			if ($this->transStrict === FALSE)
			{
				$this->transStatus = TRUE;
			}

			//            log_message('debug', 'DB Transaction Failure');
			return FALSE;
		}

		return $this->transCommit();
	}

	//--------------------------------------------------------------------

	/**
	 * Rollback Transaction
	 *
	 * @return boolean
	 */
	public function transRollback(): bool
	{
		if ( ! $this->transEnabled || $this->transDepth === 0)
		{
			return FALSE;
		}

		// When transactions are nested we only begin/commit/rollback the outermost ones
		if ($this->transDepth > 1 || $this->_transRollback())
		{
			$this->transDepth--;
			return TRUE;
		}

		return FALSE;
	}

	//--------------------------------------------------------------------

	/**
	 * Commit Transaction
	 *
	 * @return boolean
	 */
	public function transCommit(): bool
	{
		if ( ! $this->transEnabled || $this->transDepth === 0)
		{
			return FALSE;
		}

		// When transactions are nested we only begin/commit/rollback the outermost ones
		if ($this->transDepth > 1 || $this->_transCommit())
		{
			$this->transDepth--;
			return TRUE;
		}

		return FALSE;
	}

	//--------------------------------------------------------------------

	/**
	 * Determines if the statement is a write-type query or not.
	 *
	 * @param string $sql
	 *
	 * @return boolean
	 */
	public function isWriteType($sql): bool
	{
		return (bool)preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD|COPY|ALTER|RENAME|GRANT|REVOKE|LOCK|UNLOCK|REINDEX|MERGE)\s/i', $sql);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an instance of the query builder for this connection.
	 *
	 * @param string|array $tableName
	 *
	 * @return BaseBuilder
	 * @throws DatabaseException
	 */
	public function table($tableName)
	{
		if (empty($tableName))
		{
			throw new DatabaseException('You must set the database table to be used with your query.');
		}

		$className = str_replace('Connection', 'Builder', get_class($this));

		return new $className($tableName, $this);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the last query's statement object.
	 *
	 * @return mixed
	 */
	public function getLastQuery()
	{
		return $this->lastQuery;
	}

	//--------------------------------------------------------------------

	/**
	 * "Smart" Escape String
	 *
	 * Escapes data based on type.
	 * Sets boolean and null types
	 *
	 * @param mixed $str
	 *
	 * @return mixed
	 */
	public function escape($str)
	{
		if (is_array($str))
		{
			return array_map([&$this, 'escape'], $str);
		}

		if (is_string($str) || (is_object($str) && method_exists($str, '__toString')))
		{
			return "'" . $this->escapeString($str) . "'";
		}

		if (is_bool($str))
		{
			return ($str === FALSE) ? 0 : 1;
		}

		if (is_numeric($str) && $str < 0)
		{
			return "'{$str}'";
		}

		if ($str === NULL)
		{
			return 'NULL';
		}

		return $str;
	}

	//--------------------------------------------------------------------

	/**
	 * Escape String
	 *
	 * @param string|string[] $str Input string
	 * @param boolean $like Whether or not the string will be used in a LIKE condition
	 *
	 * @return string|string[]
	 */
	public function escapeString($str, bool $like = FALSE)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $val)
			{
				$str[$key] = $this->escapeString($val, $like);
			}

			return $str;
		}

		$str = $this->_escapeString($str);

		// escape LIKE condition wildcards
		if ($like === TRUE)
		{
			return str_replace([
				$this->likeEscapeChar, '%', '_',
			], [
				$this->likeEscapeChar . $this->likeEscapeChar, $this->likeEscapeChar . '%', $this->likeEscapeChar . '_',
			], $str);
		}

		return $str;
	}

	//--------------------------------------------------------------------

	/**
	 * This function enables you to call PHP database functions that are not natively included
	 * in CodeIgniter, in a platform independent manner.
	 *
	 * @param string $functionName
	 * @param array ...$params
	 *
	 * @return boolean
	 * @throws DatabaseException
	 */
	public function callFunction(string $functionName, ...$params): bool
	{
		$driver = strtolower($this->DBDriver);
		$driver = ($driver === 'postgre' ? 'pg' : $driver) . '_';

		if (FALSE === strpos($driver, $functionName))
		{
			$functionName = $driver . $functionName;
		}

		if ( ! function_exists($functionName))
		{
			if ($this->DBDebug)
			{
				throw new DatabaseException('This feature is not available for the database you are using.');
			}

			return FALSE;
		}

		return $functionName(...$params);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the last error code and message.
	 *
	 * Must return an array with keys 'code' and 'message':
	 *
	 *  return ['code' => null, 'message' => null);
	 *
	 * @return array
	 */
	abstract public function error(): array;

	//--------------------------------------------------------------------

	/**
	 * Determine if a particular field exists
	 *
	 * @param string $fieldName
	 * @param string $tableName
	 *
	 * @return boolean
	 */
	public function fieldExists(string $fieldName, string $tableName): bool
	{
		return in_array($fieldName, $this->getFieldNames($tableName), TRUE);
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch Field Names
	 *
	 * @param string $table Table name
	 *
	 * @return array|false
	 * @throws DatabaseException
	 */
	public function getFieldNames(string $table)
	{
		// Is there a cached result?
		if (isset($this->dataCache['field_names'][$table]))
		{
			return $this->dataCache['field_names'][$table];
		}

		if (empty($this->connID))
		{
			$this->initialize();
		}

		if (FALSE === ($sql = $this->_listColumns($table)))
		{
			if ($this->DBDebug)
			{
				throw new DatabaseException('This feature is not available for the database you are using.');
			}
			return FALSE;
		}

		$query = $this->query($sql);
		$this->dataCache['field_names'][$table] = [];

		foreach ($query->getResultArray() as $row)
		{
			// Do we know from where to get the column's name?
			if ( ! isset($key))
			{
				if (isset($row['column_name']))
				{
					$key = 'column_name';
				} elseif (isset($row['COLUMN_NAME']))
				{
					$key = 'COLUMN_NAME';
				} else
				{
					// We have no other choice but to just get the first element's key.
					$key = key($row);
				}
			}

			$this->dataCache['field_names'][$table][] = $row[$key];
		}

		return $this->dataCache['field_names'][$table];
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an object with field data
	 *
	 * @param string $table the table name
	 *
	 * @return array
	 */
	public function getFieldData(string $table)
	{
		return $this->_fieldData($this->protectIdentifiers($table, TRUE, FALSE, FALSE));
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an object with key data
	 *
	 * @param string $table the table name
	 *
	 * @return array
	 */
	public function getIndexData(string $table)
	{
		return $this->_indexData($this->protectIdentifiers($table, TRUE, FALSE, FALSE));
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an object with foreign key data
	 *
	 * @param string $table the table name
	 *
	 * @return array
	 */
	public function getForeignKeyData(string $table)
	{
		return $this->_foreignKeyData($this->protectIdentifiers($table, TRUE, FALSE, FALSE));
	}

	//--------------------------------------------------------------------

	/**
	 * Disables foreign key checks temporarily.
	 */
	public function disableForeignKeyChecks()
	{
		$sql = $this->_disableForeignKeyChecks();

		return $this->query($sql);
	}

	//--------------------------------------------------------------------

	/**
	 * Enables foreign key checks temporarily.
	 */
	public function enableForeignKeyChecks()
	{
		$sql = $this->_enableForeignKeyChecks();

		return $this->query($sql);
	}

	//--------------------------------------------------------------------

	/**
	 * Empties our data cache. Especially helpful during testing.
	 *
	 * @return $this
	 */
	public function resetDataCache()
	{
		$this->dataCache = [];

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Insert ID
	 *
	 * @return integer|string
	 */
	abstract public function insertID();

	//--------------------------------------------------------------------
	//--------------------------------------------------------------------
	// META Methods
	//--------------------------------------------------------------------

	/**
	 * Accessor for properties if they exist.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get(string $key)
	{
		if (property_exists($this, $key))
		{
			return $this->$key;
		}

		return NULL;
	}

	//--------------------------------------------------------------------

	/**
	 * Checker for properties existence.
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function __isset(string $key): bool
	{
		return property_exists($this, $key);
	}

	//--------------------------------------------------------------------

	/**
	 * Platform dependent way method for closing the connection.
	 *
	 * @return mixed
	 */
	abstract protected function _close();

	//--------------------------------------------------------------------

	/**
	 * Begin Transaction
	 *
	 * @return boolean
	 */
	abstract protected function _transBegin(): bool;

	//--------------------------------------------------------------------

	/**
	 * Generates the SQL for listing tables in a platform-dependent manner.
	 *
	 * @param boolean $constrainByPrefix
	 *
	 * @return string|false
	 */
	abstract protected function _listTables(bool $constrainByPrefix = FALSE);

	//--------------------------------------------------------------------

	/**
	 * Executes the query against the database.
	 *
	 * @param string $sql
	 *
	 * @return mixed
	 */
	abstract protected function execute(string $sql);

	//--------------------------------------------------------------------

	/**
	 * Rollback Transaction
	 *
	 * @return boolean
	 */
	abstract protected function _transRollback(): bool;

	//--------------------------------------------------------------------

	/**
	 * Commit Transaction
	 *
	 * @return boolean
	 */
	abstract protected function _transCommit(): bool;

	//--------------------------------------------------------------------

	/**
	 * Platform independent string escape.
	 *
	 * Will likely be overridden in child classes.
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	protected function _escapeString(string $str): string
	{
		return str_replace("'", "''", remove_invisible_characters($str, FALSE));
	}

	//--------------------------------------------------------------------

	/**
	 * Generates a platform-specific query string so that the column names can be fetched.
	 *
	 * @param string $table
	 *
	 * @return string|false
	 */
	abstract protected function _listColumns(string $table = '');

	//--------------------------------------------------------------------

	/**
	 * Platform-specific field data information.
	 *
	 * @param string $table
	 *
	 * @return array
	 * @see    getFieldData()
	 */
	abstract protected function _fieldData(string $table): array;

	//--------------------------------------------------------------------

	/**
	 * Platform-specific index data.
	 *
	 * @param string $table
	 *
	 * @return array
	 * @see    getIndexData()
	 */
	abstract protected function _indexData(string $table): array;

	//--------------------------------------------------------------------

	/**
	 * Platform-specific foreign keys data.
	 *
	 * @param string $table
	 *
	 * @return array
	 * @see    getForeignKeyData()
	 */
	abstract protected function _foreignKeyData(string $table): array;

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------


	//--------------------------------------------------------------------


	//--------------------------------------------------------------------


	//--------------------------------------------------------------------


	//--------------------------------------------------------------------


	//--------------------------------------------------------------------


	//--------------------------------------------------------------------


	//--------------------------------------------------------------------

}
