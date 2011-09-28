<?php
/**
 * UriParser class, parses, splits the requested URI to chunks to know what page is
 * requested.
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 */

class UriParser extends BasicObject {
	/**
	 * Constructor of class...
	 * 
	 * @param &$db Send in a Db instance if you want too
	 */
	public function __construct(&$db = False) {
		$structure = array('baseuri' => 'string', 'page' => 'string', 'action' => 'string', 'parts' => 'array');
		parent::__construct($db, $structure, True);
		
		$this->baseuri = $_SERVER['REQUEST_URI'];
		
		$cleanuri = preg_replace('/^\/index.php/', '', $this->baseuri);
		$cleanuri = preg_replace('/^\//', '', $cleanuri);
		$cleanuri = preg_replace('/\/$/', '', $cleanuri);
		
		$uriparts = array_map('strtolower', explode('/', $cleanuri));
		
		$this->page   = array_shift($uriparts);
		$this->action = array_shift($uriparts);
		$this->parts  = $uriparts;
	}
}
