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


	--	
	
	
	This i_functions.php file is a collection of old functions I'd like re-rwritten top down

*/

function doVoicemailConfAuthentication($p_mailbox, $p_password) {
	global $g_voicemail_conf_path;
	$success = false;
	
	// cat the voicemail.conf file, grep for mailbox
	$cmd = "cat ".$g_voicemail_conf_path." | grep ".$p_mailbox;
	$last_line = exec($cmd, $retvalue);
	// parse out the info - 1001 => 1001,Chris Carey,chris@chriscarey.com
	list($mailbox, $everything_else) = split('=>', $last_line);
	list($password,$name,$email) = split(",", $everything_else);
	
	// TODO: Check if $mailbox is not disabled
	
	// see if the password matches for this mailbox
	if ($p_password==$password) {
		$success = true;
	}
	return $success;
}

function getSettings($p_mailbox) {
	global $g_use_database;
	global $g_voicemail_conf_path;
	
	if ($g_use_database == true) {
	
		// Authenticate with Database
		$my_db = new DB();
		$my_db->construct($g_db_host, $g_db_name, $g_db_user, $g_db_pass);
		$my_db->connect();
		$my_db->select();
		
		$sql = "SELECT uniqueid,password,fullname,email,saycid,envelope,sendvoicemail,delete FROM voicemail WHERE mailbox='$p_mailbox';";
		if ($debug) echo("SQL: $sql<br />\n");
		
		$result = $my_db->query($sql);
		if ($result) {
			if (mysql_num_rows($result) > 0) {
				$row = mysql_fetch_array($result);
				$temp['FullName'] = $row['fullname'];
				$temp['Password'] = $row['password'];
				$temp['SayCallerId'] = $row['saycid'];
				$temp['Envelope'] = $row['envelope'];
				$temp['Email'] = $row['email'];
				$temp['SendToEmail'] = $row['sendvoicemail'];
				$temp['DeleteAfterEmail'] = $row['delete'];
			}
			mysql_free_result($result);
		}
		$my_db->disconnect();
		
		
	} else {
	
		// cat the voicemail.conf file, grep for mailbox
		$cmd = "cat ".$g_voicemail_conf_path." | grep ".$p_mailbox;
		$last_line = exec($cmd, $retvalue);
		// parse out the info - 1001 => 1001,Chris Carey,chris@chriscarey.com
		list($mailbox, $everything_else) = split('=>', $last_line);
		list($password,$name,$email) = split(",", $everything_else);
	
		// Settings from voicemail.conf
		$temp['FullName'] = $name;
		$temp['Password'] = $password;
		$temp['SayCallerId'] = "true";
		$temp['Envelope'] = "true";
		$temp['Email'] = $email;
		$temp['SendToEmail'] = "true";
		$temp['DeleteAfterEmail'] = "true";
	}
	
	return $temp;
}

function GetMessageArray($vm_folder, $vm_mailbox) {

	// Get all messages in a folder
	// also convert the messages to MP3 during the loop
	global $g_debug;
	global $g_voicemail_context_path;
	
	$arr = array();

	// Get the paths set up
	$voicemail_path = $g_voicemail_context_path . $vm_mailbox . "/" . $vm_folder . "/";
		
	// Open the voicemail directory and look for files
	if (is_dir($voicemail_path)) {
	
		$dir = dir($voicemail_path);
		
		// **********************************************************************
		// Load the files for this directory, then sort (by Date)
		// **********************************************************************
		
		// Get an array of files and unix timestamp
		$arr_vm_files = GetMessages($voicemail_path);
		
		// Sort that array by the timestamp
		SortByDate($arr_vm_files);
		
		// Get the size of the array of files
		$array_size = sizeof($arr_vm_files);
		
		// If there are no files, show a message, otherwise show the header
		if ($array_size > 0) {
		
		// Loop through the array
		for ($i=$array_size - 1;$i>=0;$i--) {
		
			// Get the file name
			$file = $arr_vm_files[$i][0];
		
			$loopcount = 0;
			
			if($file != "." && $file != "..") {
				
				// We're looking for the txt files as the proof that there is a voicemail here.
				// Then we work from there...
				
				if (strpos($file,".txt") != FALSE) {
				
					// We got a .txt file (which is like our INI file)
				
					// Read INI File
					$ini_array = parse_ini_file($voicemail_path . $file, false);
					
					// Strip extension from file name
					$file_noext = substr($file,0,strpos($file,".txt"));
				
					// Add .wav and .mp3 on to file name
					$file_wav = $file_noext . ".wav";
					$file_mp3 = $file_noext . ".mp3";
				
					// Return a better date
					$origdate = str_replace("  "," ", $ini_array['origdate']);
					list($day,$month,$date,$time,$ampm,$tz,$year) = split(' ', $origdate);
					$origdate_unix = strtotime("$month $date $year $time $ampm");
					//$datetimebetter = date("D M j Y, g:i a", $origdate_unix); Sun Feb 02 2008
					$datetimebetter = date("M j Y, g:i a", $origdate_unix);
						
					$arr[$i] = array('file' => $file_noext, 'calleridname' => CallerIdGetName($ini_array['callerid']), 'calleridnumber' => CallerIdGetNumber($ini_array['callerid']), 'datetime' => $ini_array['origdate'], 'datetimebetter'=>$datetimebetter, 'duration' => $ini_array['duration']);
					
					// **********************************************************************
					// See if there is a MP3 file. If not, create one.
					// **********************************************************************
					if (!(file_exists($voicemail_path . $file_noext . ".mp3"))) {
					
						// Create a standard wav file
						//$cmd = "sox ".$voicemail_path.$file_noext.".WAV -r 8000 -c 1 -s ".$voicemail_path.$file_noext.".wav";
						//echo($cmd . "<br />\n");
						//exec($cmd);
						
						// Create a mp3 file from the wav file
						$cmd = "lame --resample 11.025 ".$voicemail_path.$file_noext.".wav ".$voicemail_path.$file_noext.".mp3";
						//echo($cmd . "<br />\n");
						exec($cmd);
						
						// Delete the wav file
						//$cmd = "unlink ".$voicemail_path.$file_noext.".wav";
						//echo($cmd . "<br />\n");
						//exec($cmd);
						
						// Touch the mp3 file to set the timestamp to match the .wav file.
						// This is so the auto cleanup script will work.
						// To set the date to 7:30 am 1st October 2015
						// TOUCH /t 2015 10 01 07 30 00 MyFile.txt
						$origdate = $ini_array['origdate'];

						// Fix a bug in the asterisk date. It puts an extra space in
						$origdate = str_replace("  "," ",$origdate);
						list($day,$month,$date,$time,$ampm,$tz,$year) = split(' ', $origdate);
						$monthnumber = MonthToMonthNumber($month);
						if (strlen($date) == 1) $date = "0" . $date; // Pad a 0 to the beginning of date
						list($hour,$minute,$second) = split(":", $time);
						if ($ampm == "PM") $hour = ($hour + 12); // Compensate for PM
						$touchcmd = "touch -t ".$year.$monthnumber.$date.$hour.$minute." ".$voicemail_path.$file_noext.".mp3";
						exec($touchcmd);
					}	
				}
								
				$loopcount++;
				
			} // end if . or ..
			
	   } // end while
	   
	   $dir->close();
	   
		}
	}
	return $arr;
} // End GetMessageArray

function CallerIdGetName($pCallerId) {
	// INPUT: CallerID "Chris Carey<0000000000>"
	// OUTPUT: Chris Carey
	
	if (strpos($pCallerId,"<") > 0) {
		$value = substr($pCallerId,0,strpos($pCallerId,"<"));
	} else {
		$value = $pCallerId;
	}
	
	// Special case for "unavailable"
	if ($value == "unavailable") {
		return " ";
	}
	
	return $value;
}

function CallerIdGetNumber($pCallerId) {
	// INPUT: CallerID "Chris Carey<0000000000>"
	// OUTPUT: 000-000-0000
	
	if (strpos($pCallerId,"<") > 0) {
		$value = substr($pCallerId,strpos($pCallerId,"<")+1,strpos($pCallerId,">")-1-strrpos($pCallerId,"<"));
	} else {
		$value = $pCallerId;
	}
	
	// Special case for "Restricted"
	if ($value == "Restricted") {
		return $value;
	}
	
	// Special cases for 7 or 10 digit numbers
	if (strlen($value) == 10) {
		$value = "(" . substr($value,0,3) . ") " . substr($value,3,3) . "-" . substr($value,6,4);
	} elseif (strlen($value) == 7) {
		$value = substr($value,0,3) . "-" . substr($value,3,4);
	}
	
	return $value;
}


function GetMessages($dir) {

	//INPUT: directory
	//OUTPUT: Array { [0] file [1] unixtime }

	$Files = array();
	$It =  opendir($dir);
	if (! $It) die('Cannot list files for ' . $dir);
	while ($Filename = readdir($It)) {
		if ($Filename == '.' || $Filename == '..') continue;
		// TODO: We need to check the date from the INI file and not from the file Modified Stamp
		if (strpos($Filename,".txt") != FALSE) {
	
			// Strip extension from file name
			$file_noext = substr($Filename,0,strpos($Filename,".txt"));
			
			// Read INI File
			$ini_array = parse_ini_file($dir . $Filename, false);
			
			// Split up the date
			$origdate = $ini_array['origdate'];
			// Fix a bug in the asterisk date. It puts an extra space in
			$origdate = str_replace("  "," ",$origdate);
			list($day,$month,$date,$time,$ampm,$tz,$year) = split(' ', $origdate);
			$origdate_unix = strtotime("$month $date $year $time $ampm");
			
			$Files[] = array($file_noext.".txt", $origdate_unix);
		}
	}
	return $Files;
}
function DateCmp($a, $b) {
	//printf("comparting %s to %s <br />\n", $a[1], $b[1]);
	if ($a[1] == $b[1]) return 0;
	return ($a[1] < $b[1]) ? -1 : 1;
}

function SortByDate(&$Files) {
	usort($Files, 'DateCmp');
}


function getTxtFiles($path) {

	// Return an array of all .txt files in a directory
	
	$arrFiles = array();
	$oDir = opendir($path);
	if (! $oDir) die('Cannot list files for ' . $path);
	while ($filename = readdir($oDir)) {
	
		if ($filename == '.' || $filename == '..') continue;
	
		if (strpos($filename,".txt") != FALSE) $arrFiles[] = $filename;
	
	}
	return $arrFiles;
}

function ReindexMessages($vm_mailbox, $vm_folder) {

	// Reindex Messages
	// This will rename voicemail files so that they start
	// with msg0000.txt and move up to msg000n.txt
	
	global $debug;
	global $g_voicemail_context_path;
	$intMsgNum = 0;
	
	// Get the paths set up
	$voicemail_mailbox_path = $g_voicemail_context_path.$vm_mailbox."/";
	$voicemail_folder_path = $voicemail_mailbox_path.$vm_folder."/";
	
	// Open the voicemail directory and look for files
	if (is_dir($voicemail_folder_path)) {
			
		if ($debug) echo("$voicemail_folder_path<br />\n");
		
		
		// Only if msg0000.txt is missing
		if (file_exists($voicemail_folder_path."msg0000.txt") == false) {
			
			// Get an array of all .txt files in the folder
			$arrFiles = getTxtFiles($voicemail_folder_path);
			$intArraySize = sizeof($arrFiles);
			if ($debug) printf("array size: %s<br />\n", $intArraySize);
					
			// sort the array
			sort($arrFiles);
			
			// Loop through the array
			for ($i=0; $i<$intArraySize; $i++) {
			
				$strFile = $arrFiles[$i];
				
				// Get Current Number (Strip from name)
				$strFileNoExt = substr($strFile, 0, strpos($strFile, "."));
				
				// Get New Number
				$strMsgNum = $intMsgNum;
				while (strlen($strMsgNum) < 4) $strMsgNum = "0".$strMsgNum;
				
				// For each txt, wav, WAV, mp3
				$arrExtensions = array("txt", "wav", "WAV", "mp3");
				foreach ($arrExtensions as &$strExtension) {
					$rename_from = $strFileNoExt.".".$strExtension;
					$rename_to = "msg".$strMsgNum.".".$strExtension;
					if ($debug) printf("%s - ", $rename_from );
					if ($debug) printf("rename to %s<br />\n", $rename_to );
					rename($voicemail_folder_path.$rename_from, $voicemail_folder_path.$rename_to);
				}

				$intMsgNum++;
				
			} // for
		} else {
			if ($debug) printf("msg0000.txt exists. Doing nothing.<br />\n", $intArraySize);
		} // if (file_exists)
	} // if (is_dir)
}


   
function format_phone($phone) {

	$phone = preg_replace("/[^0-9]/", "", $phone);  
	if(strlen($phone) == 7)
      return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
	elseif(strlen($phone) == 10)
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
	else
      return $phone;
}

function MonthToMonthNumber($p_month) {
	$ret = 0;
	switch($p_month) {
		case "Jan":
			$ret = "01";
			break;
		case "Feb":
			$ret = "02";
			break;
		case "Mar":
			$ret = "03";
			break;
		case "Apr":
			$ret = "04";
			break;
		case "May":
			$ret = "05";
			break;
		case "Jun":
			$ret = "06";
			break;
		case "Jul":
			$ret = "07";
			break;
		case "Aug":
			$ret = "08";
			break;
		case "Sep":
			$ret = "09";
			break;
		case "Oct":
			$ret = "10";
			break;
		case "Nov":
			$ret = "11";
			break;
		case "Dec":
			$ret = "12";
			break;
		
	}
	return $ret;
}

?>