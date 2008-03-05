<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Voicemail for iPhone</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<meta name = "format-detection" content = "telephone=no">
<style type="text/css" media="screen">@import "iui/iui.css";</style>
<script type="application/x-javascript" src="iui/iui.js"></script>
<script language="javascript">
{literal}
function doDelete(message, path) {
	makePostRequest("delete.php", message, path);
}

function doReload() {
	window.location.replace = './';
}

	function makePostRequest(url, message, path) {

        http_request = false;

		

        if (window.XMLHttpRequest) { // Mozilla, Safari,...
            http_request = new XMLHttpRequest();
            if (http_request.overrideMimeType) {
                http_request.overrideMimeType('text/xml');
            }
        } else if (window.ActiveXObject) { // IE
            try {
                http_request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            }
        }

        if (!http_request) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        http_request.onreadystatechange = alertContents;
       
		http_request.open('POST',url,false);
		http_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		http_request.send('message='+path);

    }

    function alertContents() {

        if (http_request.readyState == 4) {
            if (http_request.status == 200) {
			
              
				
				
            } else {
			
				// Failed
                alert('There was a problem with the request.');
            }
        }

    }
	function changeDiv() {
		//document.getElementById('replaceme').innerHTML = "testing ont two three";
		//document.getElementById('replaceme').style.color = '#ff0000';
	}
	
	
{/literal}
</script>
</head>

<body>
<form name="frmDelete" id="frmDelete" action="delete.php">
<input type="hidden" name="message" id="message" value="" />
</form>
    <div class="toolbar">
        <h1 id="pageTitle"></h1>
        <a id="backButton" class="button" href="#"></a>
    </div>
	
    <ul id="home" title="Main" selected="true">
        <li><a href="#messages"><!-- img align="absmiddle" style="border:0px;height:25px;padding-right:4px;margin-bottom:4px;" src="http://www.pc-shop.hr/images/mail.gif" / -->Messages</a></li>
        <li><a href="#settings">Settings</a></li>
		<!-- li><a href="main.php" target="_self">Refresh Messages</a></li -->
        <li><a href="#about">About</a></li>
		<li><a href="logout.php" target="_self">Logout</a></li>
		
		<li class="group">Info</li>
		<h3 style="padding-left:10px;">
			{$app_name}<br />
			Logged in as <span style="color:darkblue;">{$mailbox_formatted_encoded}</span><br />
		</h3>
		
		<!--
		<div style="margin-top:110px;"></div>
		
		<li class="group">Development: ChangeLog (History)</li>
		<h3 style="padding-left:10px;font-size:12px;">
		
			<span style="color:blue;">2008-03-03</span><br />
			- Prepare code for release<br />
			- Write stub function for voicemail.conf authentication<br />
			<br />
			
			<span style="color:blue;">2008-02-25</span><br />
			- Added "Settings" back, and Added "Move Messages" button. Both of these features are non-functional at this time.<br />
			- Cleaned up date format on Message Detail, and changed it's font size back up to normal<br />
			<br />
			
			<span style="color:blue;">2008-02-24</span><br />
			- Added Cookie Support<br />
			- Renamed "Check Messages" to "Messages"<br />
			- Displaying "Saved" messages<br />
			- Added ChangeLog<br />
			- Updated About page with email link<br />
			- Removed "Settings" page until I get it working<br />
			- Allow 7 digit logins, will prepend a default prefix<br />
			- Disabled iPhone's "format detection" for phone numbers<br />
		</h3>
		
		<li class="group">Development: Known Issues</li>
		<h3 style="padding-left:10px;font-size:12px;">
			- Need to write voicemail.conf authentication. We only have a stub function which does no authentication<br />
			<br />
			- Listening to messages is done in an unsecure way. We are adding an apache alias which anyone can go to the URL and listen. Need to write a php page which will perform session/security checks and pass the mp3 sound file through. The iPhone quicktime interface grabs the music in chunks which I am not sure how to handle (sample code in listen.php is a good head start, but it needs to be fixed)<br />
			<br />
			
			- Some MP3s are not playable. For some reason they always have a duration of 10 seconds. I believe this to be a bug with the mp3 generation (sox or lame)<br />
		</h3>
		-->
    </ul>
	
	
    <ul id="messages" title="Messages">
	
        <li class="group">Inbox</li>
{foreach from=$messages key=myId item=i}
		<li id="li_inbox_{$i.file}"><a href="#inbox_{$i.file}"><span style="color:darkblue;">{$i.calleridname}</span><br />
		{$i.calleridnumber} ({$i.duration}sec)</a></li>
{/foreach}

		<li class="group">Saved</li>
{foreach from=$messages_old key=myId item=i}
		<li id="li_old_{$i.file}"><a href="#old_{$i.file}"><span style="color:darkblue;">{$i.calleridname}</span><br />
		{$i.calleridnumber} ({$i.duration}sec)</a></li>
{/foreach} 
   
    </ul>

{foreach from=$messages key=myId item=i}
    <ul id="inbox_{$i.file}" title="Message Detail">
	  
		<li class="group">Action</li>
        <li ><a href="{$apache_messages_alias}{$mailbox}/INBOX/{$i.file}.mp3" target="_self">Play Message</a></li>
		<li ><a href="#" target="_self" onclick="alert('not implemented yet');">Move Message</a></li>
		<li ><a href="#delete" onclick="doDelete('{$i.file}','INBOX/{$i.file}');return true;">Delete Message</a></li>
		<li ><a class="nothing" href="tel:{$i.calleridnumber}" target="_self">Call Back {$i.calleridnumber}</a></li>
		
		<li class="group">Detail</li>
		<h3 style="padding-left:10px;">
		<table>
			<tr>
				<th style="text-align:right">CID:</th>
				<td><span style="color:darkblue;">{$i.calleridname}</span></td>
			</tr>
			<tr>
				<th style="text-align:right">Phone:</th>
				<td><span style="color:darkblue;">{$i.calleridnumber}</span></td>
			</tr>
			<tr>
				<th style="text-align:right">Length:</th>
				<td><span style="color:darkblue;">{$i.duration} seconds</span></td>
			</tr>
			<tr>
				<th style="text-align:right">Date:</th>
				<td><span style="color:darkblue;">{$i.datetimebetter}</span></td>
			</tr>
		</table>
		</h3>
		
    </ul>   

{/foreach}

{foreach from=$messages_old key=myId item=i}
    <ul id="old_{$i.file}" title="Message Detail">
	  
		<li class="group">Action</li>
        <li ><a href="{$apache_messages_alias}{$mailbox}/Old/{$i.file}.mp3" target="_self">Play Message</a></li>
		<li ><a href="#" target="_self" onclick="alert('not implemented yet');">Move Message</a></li>
		<li ><a href="#delete" onclick="doDelete('{$i.file}','Old/{$i.file}');return true;">Delete Message</a></li>
		<li ><a class="nothing" href="tel:{$i.calleridnumber}" target="_self">Call Back {$i.calleridnumber}</a></li>
		
		<li class="group">Detail</li>
		<h3 style="padding-left:10px;">
		<table>
			<tr>
				<th style="text-align:right">CID:</th>
				<td><span style="color:darkblue;">{$i.calleridname}</span></td>
			</tr>
			<tr>
				<th style="text-align:right">Phone:</th>
				<td><span style="color:darkblue;">{$i.calleridnumber}</span></td>
			</tr>
			<tr>
				<th style="text-align:right">Length:</th>
				<td><span style="color:darkblue;">{$i.duration} seconds</span></td>
			</tr>
			<tr>
				<th style="text-align:right">Date:</th>
				<td><span style="color:darkblue;">{$i.datetimebetter}</span></td>
			</tr>
		</table>
		</h3>
		
    </ul>   

{/foreach}
    
	<div id="about" class="panel" title="About Voicemail">
        <h2>{$app_name} v{$app_version}<br />
		by Christopher Carey<br />
		<a href="mailto:chris@chriscarey.com?Subject=Asterisk Voicemail for iPhone" target="_self">chris@chriscarey.com</a><br />
		<br />
		Homepage: <a href="http://chriscarey.com/projects/asterisk/iphone/" target="_blank">Click here</a>
		</h2>
    </div>
	
    <div id="delete" class="panel" title="Message Deleted">
        <h2>Message has been deleted.</h2>		
		<a style="margin-top:30px;margin-left:0px;" class="button" href="main.php" target="_self">Touch here twice for Main Menu</a>
        
    </div>
    
 
	<div id="settings" title="Settings" class="panel">
		<h2>User</h2>
        <fieldset>
            <div class="row">
                <label>Full Name</label>
                <input type="text" name="userName" value=""/>
            </div>
            <div class="row">
                <label>Password</label>
                <input type="password" name="password" value=""/>
            </div>
        </fieldset>
		
        <h2>Playback</h2>
        <fieldset>
			<div class="row">
                <label>Say Caller ID</label>
                <div class="toggle" onclick="" toggled="true"><span class="thumb"></span><span class="toggleOn">ON</span><span class="toggleOff">OFF</span></div>
            </div>
			<div class="row">
                <label>Envelope</label>
                <div class="toggle" onclick="" toggled="true"><span class="thumb"></span><span class="toggleOn">ON</span><span class="toggleOff">OFF</span></div>
            </div>
        </fieldset>
        
		<h2>Email</h2>
		<fieldset>
			<div class="row">
                <label>Email</label>
                <input type="text" name="email" value=""/>
            </div>
			 <div class="row">
                <label>Send to Email</label>
                <div class="toggle" onclick="" toggled="true"><span class="thumb"></span><span class="toggleOn">ON</span><span class="toggleOff">OFF</span></div>
            </div>
            <div class="row">
                <label>Delete after Email</label>
                <div class="toggle" onclick=""><span class="thumb"></span><span class="toggleOn">ON</span><span class="toggleOff">OFF</span></div>
            </div>
		</fieldset>
		
    </div>
</body>
</html>
