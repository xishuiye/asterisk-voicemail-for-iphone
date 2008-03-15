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

	=============================================================
		
	This file's purpose is to perform the session/security checks
	and streaming of voicemails to the phone
	
	The iPhone apparently hands off the URL to play off to Quicktime
	which starts a brand new session. This sucks because I can no longer
	trust PHP session to handle our authentication verification.
	
	What I ended up doing was combining the remote_addr with a secret salt
	(configurable in the i_settings.php file), then md5 it. We compare the hash
	with the hash provided and if they match, then we have verified that the
	person is in fact at the same IP number. This limits the spoofing mailbox
	vulnerability. You can only spoof if you are at the same IP that generated
	the php page. If you have any better ideas please send them my way.
	
*/
	
	require_once("i_db.php");
	require_once("i_settings.php");
	require_once("i_functions.php");
	
	if ($g_debug) {
		doDebugFile('--------------------------------------------------------');
		foreach($_SERVER as $name => $vaule) {
			doDebugFile('SERVER:['.$name.']['.$vaule.']');
		}
	}
	
	// Parse out the Request URI to get the variables we need.
	$garbage1="";$garbage2="";$garbage3="";$p_secret="";$p_mailbox="";$p_folder="";$p_file="";
	list($garbage1, $garbage2, $garbage3, $p_secret, $p_mailbox, $p_folder, $p_file) = split("/", $_SERVER['REQUEST_URI']);
	
	// Hash their IP, with the secret salt, and compare.
	$secret_key = md5($_SERVER['REMOTE_ADDR'] . $g_secret_salt);
	if ($secret_key != $p_secret) {
		// Failed the security test, exit
		if ($g_debug) doDebugFile('listen.php - FAILED SECURITY TEST!');
		exit;
	}
		
	// Compile the full file path
	$sound_file = $g_voicemail_context_path . $p_mailbox . "/" . $p_folder . "/" . $p_file;
	if ($g_debug) doDebugFile('listen.php - Calling doSendFileWithResume ' . $sound_file);
	
	// Send the file down to the iPhone
	doSendFileWithResume($sound_file);

?>