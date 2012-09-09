<?php

/**
 * Session class, my custom session-handler using mysql as backend
 * 
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

class Session {
	protected $orm;     ///< Orm instance
	protected $table;   ///< Instance of OrmTable
	protected $options; ///< Options for sessions
	protected $new;     ///< True if we need to create new rows when writing
	
	/**
	 * Class constructor...
	 * 
	 * @param $cfg Config instance
	 * @param $orm Orm Instance for database connections
	 */
	public function __construct(Config &$cfg, Orm &$orm) {
		$this->options = $cfg->getSession();
		$this->orm     = $orm;
		$this->new     = false; // Let's hope we don't make new rows
		
		session_set_save_handler(
			array($this, 'open'),
			array($this, 'close'),
			array($this, 'read'),
			array($this, 'write'),
			array($this, 'destroy'),
			array($this, 'gc')
		);
	}
	
	/**
	 * Open - session open handler
	 * 
	 * @param $session_save_path PHP's session.save_path variable from php.ini
	 * @param $session_name      Session Name
	 * @return boolean
	 */
	public function open($session_save_path, $session_name) {
		try {
			$this->table = $this->orm->getTable($this->options['table']);
			
		} catch (Exception $e) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Read - session read handler
	 * 
	 * Also locks the session.
	 * 
	 * @param $session_id Session ID to read
	 * @return string session content or empty string
	 */
	public function read($session_id) {
		if($this->table->load($session_id)) {
			return $this->table->value;
		} else {
			$this->new = true;
			return '';
		}
	}
	
	/**
	 * Write - session write handler
	 * 
	 * @param $session_id  Session ID to save to
	 * @param $session_val Session's content serialized by PHP
	 * @return boolean
	 */
	public function write($session_id, $session_val) {
		if($this->new) {
			$query = $this->orm->prepare('INSERT INTO '.$this->options['table'].'(`id`, `timestamp`, `value`) VALUES(:id, NOW(), :value)');
			$query->bindParam('id',    $session_id);
			$query->bindParam('value', $session_val);
			
			return $query->execute();
			
		} else {
			$this->table->timestamp = date('Y-m-d H:i:s');
			$this->table->value     = $session_val;
			
			return $this->table->commit();
		}
	}
	
	/**
	 * Close - session close handler
	 * 
	 * Also unlocks the session.
	 * 
	 * @return boolean
	 */
	public function close() {
		return true;
	}
	
	/**
	 * Destroy - session destroy hander
	 * 
	 * @param $session_id Session ID to destroy
	 * @return boolean
	 */
	public function destroy($session_id) {
		$query = $this->orm->prepare('DELETE FROM '.$this->options['table'].' WHERE id = :id');
		$query->bindParam('id', $session_id);
		
		return $query->execute();
	}
	
	/**
	 * gc - session garbarge colloctor, kills sessions that are too old to exist
	 * 
	 * @param $session_lifetime Sessions lifetime set in php.ini
	 * @return boolean
	 */
	public function gc($session_lifetime) {
		$old = date('Y-m-d H:i:s', strtotime('-'.$this->options['lifetime'].' minutes'));
		
		$query = $this->orm->prepare('DELETE FROM '.$this->options['table'].' WHERE timestamp > :timestamp');
		$query->bindParam('timestamp', $old);
		
		return $query->execute();
	}
	
	/**
	 * On destruct, close that session to make sure it's unlocked
	 */
	public function __destruct() {
		$this->close();
	}
}

