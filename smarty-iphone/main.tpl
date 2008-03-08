<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Voicemail for iPhone</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<meta name = "format-detection" content = "telephone=no">
<style type="text/css" media="screen">@import "iui/iui.css";</style>
<script type="application/x-javascript" src="iui/iui.js"></script>
<script type="application/x-javascript" src="main.js"></script>
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
			Logged in as <span style="color:darkblue;">{$mailbox_formatted}</span><br />
		</h3>
    </ul>
	
    <ul id="messages" title="Messages">
        <li class="group">Inbox</li>
{foreach from=$messages_inbox key=myId item=i}
		<li id="li_inbox_{$i.file}"><a href="#inbox_{$i.file}"><span style="color:darkblue;">{$i.calleridname}</span><br />
		{$i.calleridnumber} ({$i.duration}sec)</a></li>
{/foreach}
		<li class="group">Saved</li>
{foreach from=$messages_old key=myId item=i}
		<li id="li_old_{$i.file}"><a href="#old_{$i.file}"><span style="color:darkblue;">{$i.calleridname}</span><br />
		{$i.calleridnumber} ({$i.duration}sec)</a></li>
{/foreach} 
    </ul>

{foreach from=$messages_inbox key=myId item=i}
    <ul id="inbox_{$i.file}" title="Message Detail">
		<li class="group">Action</li>
        <li ><a href="{$apache_messages_alias}{$mailbox}/INBOX/{$i.file}.mp3" target="_self">Play Message</a></li>
		<li ><a href="#inbox_{$i.file}_move" target="_self">Move Message</a></li>
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
	
	<ul id="inbox_{$i.file}_move" title="Move Message">  
		<li class="group">Move Message</li>
		<li ><a href="#" target="_self">To Saved</a></li>
	</ul>
{/foreach}

{foreach from=$messages_old key=myId item=i}
    <ul id="old_{$i.file}" title="Message Detail">
		<li class="group">Action</li>
        <li ><a href="{$apache_messages_alias}{$mailbox}/Old/{$i.file}.mp3" target="_self">Play Message</a></li>
		<li ><a href="#old_{$i.file}_move" target="_self">Move Message</a></li>
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
	
	<ul id="old_{$i.file}_move" title="Move Message">  
		<li class="group">Move Message</li>
		<li ><a href="#" target="_self">To Inbox</a></li>
	</ul> 

{/foreach}
    
	<div id="about" class="panel" title="About Voicemail">
        <h2>{$app_name} v{$app_version}<br />
		by Christopher Carey<br />
		<a href="mailto:chris@chriscarey.com?Subject=Asterisk Voicemail for iPhone" target="_self">chris@chriscarey.com</a><br />
		<br />
{if $current_version}
		Current Version: v{$current_version}<br /><br />
{/if}
		
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
                <input type="text" name="userName" value="{$c_settings.FullName}"/>
            </div>
            <div class="row">
                <label>Password</label>
                <input type="password" name="password" value="{$c_settings.Password}"/>
            </div>
        </fieldset>
		
        <h2>Playback</h2>
        <fieldset>
			<div class="row">
                <label>Say Caller ID</label>
                <div class="toggle" onclick="settingsSayCallerId()" {if $c_settings.SayCallerId == 'true'}toggled="true"{else}toggled="false"{/if}>
				<span class="thumb"></span>
				<span class="toggleOn">ON</span>
				<span class="toggleOff">OFF</span>
			</div>
            </div>
			<div class="row">
                <label>Envelope</label>
                <div class="toggle" onclick="settingsEnvelope()" {if $c_settings.Envelope == 'true'}toggled="true"{else}toggled="false"{/if}>
				<span class="thumb"></span>
				<span class="toggleOn">ON</span>
				<span class="toggleOff">OFF</span>
			</div>
            </div>
        </fieldset>
        
		<h2>Email</h2>
		<fieldset>
			<div class="row">
                <label>Email</label>
                <input type="text" name="email" value="{$c_settings.Email}"/>
            </div>
			 <div class="row">
                <label>Send to Email</label>
                <div class="toggle" onclick="settingsSendToEmail()" {if $c_settings.SendToEmail == 'true'}toggled="true"{else}toggled="false"{/if}>
				<span class="thumb"></span>
				<span class="toggleOn">ON</span>
				<span class="toggleOff">OFF</span>
			</div>
            </div>
            <div class="row">
                <label>Delete after Email</label>
                <div class="toggle" onclick="settingsDeleteAfterEmail()" {if $c_settings.DeleteAfterEmail == 'true'}toggled="true"{else}toggled="false"{/if}>
				<span class="thumb"></span>
				<span class="toggleOn">ON</span>
				<span class="toggleOff">OFF</span>
			</div>
            </div>
		</fieldset>
		
    </div>
</body>
</html>
