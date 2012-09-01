<?php

/**
 * Orm class, wrapperclass for PDO, handles connections automagicly with options from the
 * config.ini file.
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */
class Orm extends PDO {
	protected $database;          ///< Name of the database
	protected $tables = array();  ///< Tables of the database
	
	/**
	 * Fetches the config-options and uses PDO to connect to the database,
	 * and kills the proccess with an error on connection failure.
	 * 
	 * @param $cfg The Config Object
	 */
	public function __construct(Config &$cfg) {
		$db = $cfg->getDatabase();
		$this->database = $db['database'];
		
		$dsn = $db['driver'].':dbname='.$db['database'].';host='.$db['hostname'];
		try {
			parent::__construct($dsn, $db['username'], $db['password']);
		} catch (PDOException $e) {
			die('Connection to database failed: '.$e->getMessage());
		}
		
		$this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		
		$query = $this->prepare('SHOW TABLES');
		$query->execute();
		
		foreach($query->fetchAll() as $table) {
			$tmp = 'Tables_in_'.$this->database;
			$name = $table->$tmp;
			
			$query = $this->prepare('DESCRIBE '.$name);
			$query->execute();
			
			$this->tables[$name] = array();
			
			foreach($query->fetchAll() as $cols) {
				$this->tables[$name][$cols->Field] = $cols;
			}
		}
	}
	
	/**
	 * Fetch OrmTable object for table
	 * 
	 * @param $table string Name of table to get OrmTable loaded with
	 * @returns if table exists, OrmTable olse Exception
	 */
	public function getTable($table) {
		if(isset($this->tables[$table])) {
			return new OrmTable($this, $table, $this->tables[$table]);
		} else {
			throw new Exception('table: '.$table.' does not exist in database '.$this->database);
		}
		
	}
}

