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

	// Includes
	require_once("i_db.php");
	require_once("i_settings.php");
	require_once("i_functions.php");
	require_once($g_smarty_class.'Smarty.class.php');
	
	// Set up Smarty
	$smarty = new Smarty();
	$smarty->template_dir = $g_smarty_folder.'templates';
	$smarty->compile_dir = $g_smarty_folder.'templates_c';
	$smarty->cache_dir = $g_smarty_folder.'cache';
	$smarty->config_dir = $g_smarty_folder.'configs';
	
	// Application Variables
	$g_app_name = "Asterisk Voicemail for iPhone";
	$g_app_version = "0.07";
	$smarty->assign('app_name', $g_app_name);
	$smarty->assign('app_version', $g_app_version);
	
	// Session
	$s_mailbox = "";
	$s_fullname = "";
	doSessionCheck($s_mailbox);
	$smarty->assign('mailbox', $s_mailbox);
	$smarty->assign('mailbox_formatted', format_phone($s_mailbox));
	
	// Get messages (into an array)
	$arr_messages_inbox = GetMessageArray("INBOX", $s_mailbox);
	$arr_messages_old = GetMessageArray("Old", $s_mailbox);
	$smarty->assign('messages_inbox', $arr_messages_inbox);
	$smarty->assign('messages_old', $arr_messages_old);
	
	// Get Settings Screen info
	$c_settings = GetSettings($s_mailbox);
	$smarty->assign('c_settings', $c_settings);
	
	// Assign any global variables into Smarty
	$smarty->assign('apache_messages_alias', $g_apache_messages_alias);
	
	// Check for updates
	if ($g_check_for_updates != false) { $current_version = doCheckVersion($g_app_version);
	$smarty->assign('current_version', $current_version); }
	
	// Display the smarty template
	$smarty->display($g_smarty_template_folder.'main.tpl');
	
?>
