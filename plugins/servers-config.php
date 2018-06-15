<?php

/** Multiple Per-Server configurations indexed by server IP or name.
 *
* @link https://www.adminer.org/plugins/#use
* @author Paul Caron, Jakub Vrana, https://www.vrana.cz/
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerServersConfig {
	/** @access protected */
	var $servers;
	
	/** Set supported servers
	* @param array array($serverId => array("name" => , "driver" => "server|pgsql|sqlite|..."), "connectionOptions" => array( various, depends on driver )
	*/
	function __construct($servers) {
		$this->servers = $servers;
	}
	
    function serversConfig() {
		return $this->servers;
	}
}
