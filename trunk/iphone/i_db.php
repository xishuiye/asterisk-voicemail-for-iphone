<?php
/*
	Copyright 2008 Christopher P Carey (http://chriscarey.com)
	This program is distributed under the terms of the GNU General Public License
	
	This file is part of Asterisk Voicemail for iPhone.

    Asterisk Voicemail for iPhone is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Asterisk Voicemail for iPhone.
	If not, see <http://www.gnu.org/licenses/>.
*/

class DB {

	var $link;
	var $my_host = "";
	var $my_db = "";
	var $my_user = "";
	var $my_pass = "";
	
	function construct($p_host, $p_db, $p_user, $p_pass) {
		$this->my_host = $p_host;
		$this->my_db = $p_db;
		$this->my_user = $p_user;
		$this->my_pass = $p_pass;
   }
  
    function connect() {
        $this->link = mysql_connect($this->my_host, $this->my_user, $this->my_pass);
		if (!$this->link) {
			printf("Error: Connection to MySQL server '%s' failed.<BR>\n", $this->my_host);
			return;
		} 
    }
	
	function select() {
		mysql_select_db($this->my_db);
	}
	
	function query($p_sql) {
		$result = mysql_query($p_sql, $this->link)
		or die ("Error: [$p_sql] - <b>" . mysql_error($this->link) . "</b>" );
		return $result;
    }
	
	function disconnect() {
		if (!mysql_close($this->link)) {
			die("Could not close DB");
		}
	}
}
?>
