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
	if (isset($_POST['message'])) $message = $_POST['message'];
	if (isset($_GET['message'])) $message = $_GET['message'];
	
	if ($message) {
		$arr = array($message);
		
		DeleteMessages($s_mailbox, $arr);
		ReindexMessages($s_mailbox, "INBOX");
		
	}
	
	
?>