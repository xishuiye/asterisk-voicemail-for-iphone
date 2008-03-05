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
	
	THIS FILE IS A MESS BUT HAS SOME SAMPLE CODE I WILL USE IN THE FUTURE
	
	This file's purpose in the future will be to perform the session/security checks
	for listening to voicemails
	
	We will change the "Play Message" links to something like:
	listen.php?file=INBOX/msg0001.mp3
	If the quicktime player does not like that, then we can use other apache trickery
	such as: /listen/INBOX/msg0001.mp3
	...and use a apache directive to force /listen/ to parse listen.php
	
*/
	
	// Catch Session
	session_start();
	
	// Since we have a question mark elsewhere in the URL, we have to catch like this:
	// file_get.php/8016234127/Friends/msg0000.mp3
	$requesturi = $_SERVER['REQUEST_URI'];
	list($junk1, $path1, $file1, $mailbox, $folder, $file) = split("/", $requesturi);

	// Check if they have permission
	if ($mailbox != $vm_mailbox) {
		echo("You do not have permission to the mailbox $mailbox");
		exit;
	}

	// Globals
	$basepath = "/var/spool/asterisk/voicemail/default";

	if ($file) {
	
		// Make full file path
		$fullpath = $basepath . "/" . $mailbox . "/" . $folder . "/" . $file;
	
		
		
		// Check if file exists
		if (file_exists($fullpath)) {
		
			// Send Headers
			header('Content-Type: audio/mp3');
			header('Content-Length: ' . filesize($fullpath));
			header("Content-Transfer-Encoding: binary");
			header('Content-Disposition: attachment; filename="'.$file.'"');
			header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
			
			//$timestamp = time();
			//$timenextweek = time() + (24 * 60 * 60);
			//header('Last-Modified: '.gmdate('D, d M Y H:i:s', $timenextweek).' GMT'); 
		
			// URL should be something like 8015551212/Friends/msg0001.mp3
			// We need to prepend /var/spool/asterisk/voicemail/default/
			
			// Send File
			@readfile($fullpath) or die("Cant Read");
			
		} else {
			// Send error message
			printf("File %s does not exist", $file);
			
			// Maybe it would be cool to play some other mp3 sound file on error???
		}
		
	}
	
?>