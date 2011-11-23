<?php

/**
 * Db class, wrapperclass for PDO, handles connections automagicly with options from the
 * config.ini file.
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

class Db extends PDO {
	/**
	 * Fetches the config-options and uses PDO to connect to the database,
	 * and kills the proccess with an error on connection failure.
	 * 
	 * @param $cfg The Config Object
	 */
	public function __construct(Config &$cfg) {
		$db = $cfg->getDatabase();
		
		$dsn = $db['driver'].':dbname='.$db['database'].';host='.$db['hostname'];
		try {
			parent::__construct($dsn, $db['username'], $db['password']);
		} catch (PDOException $e) {
			die('Connection to database failed: '.$e->getMessage());
		}
		
		$this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}
}
