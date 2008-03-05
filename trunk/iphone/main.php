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

	$g_app_name = "Asterisk Voicemail for iPhone";
	$g_app_version = "0.03";

	require_once("i_db.php");
	require_once("i_settings.php");
	require_once("i_functions.php");
	require_once($g_smarty_root.'Smarty.class.php');
	
	// Start Session
	session_start();
	
	// Check for Cookie
	if (isset($_COOKIE['mailbox'])) {
		// Get Cookie
		
		// Setup Session from Cookie
		$_SESSION['mailbox'] = $_COOKIE['mailbox'];
		$_SESSION['fullname'] = $_COOKIE['fullname'];
		
		$s_mailbox = $_SESSION['mailbox'];
		$s_fullname = $_SESSION['fullname'];
		
	} else {
		// No Cookie, Check for Session
		if (!isset($_SESSION['mailbox'])) {
			// No Session, Send to the login screen
			header("Location: ./");
		} else {
			// Got Session. Grab information out of the session
			$s_mailbox = $_SESSION['mailbox'];
			$s_fullname = $_SESSION['fullname'];
		}
	}
		
	// Set up Smarty
	$smarty = new Smarty();
	$smarty->template_dir = $g_smarty_root.'templates';
	$smarty->compile_dir = $g_smarty_root.'templates_c';
	$smarty->cache_dir = $g_smarty_root.'cache';
	$smarty->config_dir = $g_smarty_root.'configs';
	
	// Get messages (into an array)
	$arr_messages = GetMessageArray("INBOX", $s_mailbox);
	$arr_messages_old = GetMessageArray("Old", $s_mailbox);
	
	// Assign variables into Smarty for the template
	$smarty->assign('app_name', $g_app_name);
	$smarty->assign('app_version', $g_app_version);
	$smarty->assign('mailbox', $s_mailbox);
	$smarty->assign('mailbox_formatted', format_phone($s_mailbox));
	$smarty->assign('mailbox_formatted_encoded', htmlspecialchars(format_phone($s_mailbox)));
	$smarty->assign('fullname', $s_fullname);
	$smarty->assign('messages', $arr_messages);
	$smarty->assign('messages_old', $arr_messages_old);
	$smarty->assign('apache_messages_alias', $g_apache_messages_alias);
	
	// Display the smarty template
	$smarty->display($g_smarty_template_folder.'main.tpl');
	
?>
