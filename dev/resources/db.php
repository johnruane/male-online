<?php

class db {
    var $link;  ///

    /// Create new database connection, possibly using server-provided password.
    function db($database, $host, $user, $password) {
        $this->connect($host, $user, $password);
        $this->select_db($database);
    }

    /// Connect to database.
    function connect($host, $user, $password) {
	  $this->link = mysql_pconnect($host, $user, $password)
	                or die(mysql_error($this->link));
    }
}
?>
