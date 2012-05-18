<?php

include_once ('backend.php');

class MockBackend extends Backend {
	var $_user;
	var $_devid;
	var $_protocolversion;

	function Logon($username, $domain, $password) {
		debugLog("dentro");
		return true;
	}

	// completing protocol
	function Logoff() {
		return true;
	}

	function Setup($user, $devid, $protocolversion) {
		$this -> _user = $user;
		$this -> _devid = $devid;
		$this -> _protocolversion = $protocolversion;

		return true;
	}

};
?>