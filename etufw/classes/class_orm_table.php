<?php

/**
 * OrmTable class
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */
class OrmTable {
	protected $orm;                ///< Orm Instance
	protected $table;              ///< Name of table
	protected $cols;               ///< Cols in table
	protected $loadData = array(); ///< array($data, $col) used to load row from database
	protected $data     = array(); ///< Current data in database
	protected $changes  = array(); ///< Changes to commit
	
	/**
	 * Constroction of class
	 * 
	 * @param $orm   Instance of Orm class
	 * @param $table Which table we load
	 * @param $cols  What cols the table contains
	 */
	public function __construct(&$orm, $table, $cols) {
		$this->orm   = $orm;
		$this->table = $table;
		$this->cols  = $cols;
		
		foreach(array_keys($cols) as $key) {
			$this->data[$key] = '';
		}
	}
	
	/**
	 * On destruction of class, commit changes
	 */
	public function __destruct() {
		$this->commit();
	}
	
	/**
	 * Get value from table column
	 *
	 * @param $key tablecolumn
	 */
	public function __get($key) {
		$col = preg_replace('/^_/', '', $key);
		
		if(isset($this->changes[$col])) {
			return $this->changes[$col];
		} elseif(isset($this->data[$col])) {
			return $this->data[$col];
		} else {
			throw new Exception('table: '.$this->table.' does not have a column named '.$col);
		}
		
		return False;
	}
	
	/**
	 * Set value to table column
	 *
	 * @param $key table column
	 * @param $value value to set
	 * @returns bool
	 */
	public function __set($key, $value) {
		$col = preg_replace('/^_/', '', $key);
		
		if(isset($this->data[$col]) AND $this->data[$col] !== $value) { // If this column exist, change the value
			$this->changes[$col] = $value;
			
		} elseif(isset($this->changes[$col]) AND $this->changes[$col] !== $value) { // If this column is changed, look at it again.
			
			if($this->data[$col] === $value) // If the change revert to original content, undo the change
				unset($this->changes[$col]);
			else                             // If the change changes the value again, set it
				$this->changes[$col] = $value;
		} else {
			return False;
		}
		
		return True;
	}
	
	/**
	 * Load specific row from database
	 * 
	 * @param $data Data to use in WHERE
	 * @param $col  Column to use with data, defaults to 'id'
	 */
	public function load($data, $col = 'id') {
		if(isset($this->cols[$col])) {
			$this->loadData = array($data, $col);
			
			$query = $this->orm->prepare('SELECT * FROM '.$this->table.' WHERE '.$col.' = :data');
			$query->bindParam(':data',  $data);
			
			$query->execute();
			$res = $query->fetchAll();
			
			foreach(array_shift($res) as $key => $value) {
				$this->data[$key] = $value;
			}
		} else {
			throw new Exception('table: '.$this->table.' does not have a column named '.$col);
		}
	}
	
	/**
	 * Commit changes to database
	 */
	public function commit() {
		if(count($this->changes)) {
			$updates = array();
			foreach(array_keys($this->changes) as $key) {
				$updates[] = $key.' = :'.$key;
			}
			
			$query = $this->orm->prepare('UPDATE '.$this->table.' SET '.implode(', ', $updates).' WHERE '.$this->loadData[1].' = :_loadId');
			$query->bindParam(':_loadId', $this->loadData[0]);
			
			foreach($this->changes as $key => $value)
				$query->bindParam(':'.$key, $value);
			
			return $query->execute();
		}
	}
}

