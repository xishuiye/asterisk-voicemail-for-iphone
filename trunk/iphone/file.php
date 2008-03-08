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
	require_once("i_db.php");
	require_once("i_settings.php");
	require_once("i_functions.php");
	
	session_start();
	$s_mailbox = $_SESSION['mailbox'];
		
	// Catch
	$action = ""; if (isset($_POST['action'])) $action = $_POST['action'];
	$file = ""; if (isset($_POST['file'])) $file = $_POST['file'];
	$filepath = ""; if (isset($_POST['filepath'])) $filepath = $_POST['filepath'];
	$newfolder = ""; if (isset($_POST['newfolder'])) $newfolder = $_POST['newfolder'];
	
	if (isset($_GET['action'])) $action = $_GET['action'];
	if (isset($_GET['file'])) $file = $_GET['file'];
	if (isset($_GET['filepath'])) $filepath = $_GET['filepath'];
	if (isset($_GET['newfolder'])) $newfolder = $_GET['newfolder'];
	
	switch($action) {
		case "move":
			MoveMessage($s_mailbox, $filepath, $newfolder, $file);
			ReindexMessages($s_mailbox, "INBOX");
			ReindexMessages($s_mailbox, "Old");
			break;
		case "delete":
			$arr_files = array($filepath);
			DeleteMessages($s_mailbox, $arr_files);
			ReindexMessages($s_mailbox, "INBOX");
			break;
	}
	
	
?>