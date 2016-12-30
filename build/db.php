<?php

/// Defines class to handle database connections and queries.

class db {
    var $link;  ///

    /// Create new database connection, possibly using server-provided password.
    function db($database, $host = "localhost", $user = "", $password = "") {
        $this->connect($host, $user, $password);
        $this->select_db($database);
    }

    /// Connect to database.
    function connect($host, $user, $password) {
       if ($password == "") {
        $this->link = mysql_pconnect($host, $user)
                      or die(mysql_error($this->link));
       } else {
	  $this->link = mysql_pconnect($host, $user, $password)
	                or die(mysql_error($this->link));
       }
    }

    /// Select database.
    function select_db($database) {
        mysql_select_db($database, $this->link)
            or die(mysql_error($this->link));
    }

    /// Execute SQL query.
    function query($sql) {
		//print"<br>$sql<br>";
        return mysql_query($sql, $this->link);
    }

    /// Return number of rows in result.
    function num_rows($query_result) {
        return mysql_num_rows($query_result);
    }

    /// Return result as an object.
    function fetch_object($query_result) {
        return mysql_fetch_object($query_result);
    }

    /// Free spaced used by result.
    function free_result($query_result) {
        return mysql_free_result($query_result);
    }

    /// Seek to a given row of result.
    function data_seek($query_result, $rowindex = 0) {
        return mysql_data_seek($query_result, $rowindex);
    }
}
?>
