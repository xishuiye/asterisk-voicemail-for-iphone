<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Voicemail for iPhone</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<style type="text/css" media="screen">@import "iui/iui.css";</style>
<script type="application/x-javascript" src="iui/iui.js"></script>
{literal}
<script language="javascript">
function doLogin() {
	document.frmLogin.submit();
}
</script>
<style>
input.login {
	padding:4px;
	width: 150px;
	font-weight:bold;
	font-size:24px;
}
</style>
{/literal}
<link rel="apple-touch-icon" href="images/asterisk-voicemail-iphone.png" />
</head>

<body>

    <div class="toolbar">
        <h1 id="pageTitle"></h1>
        <a id="backButton" class="button" href="#"></a>
    </div>
	
    <ul id="login" title="Login" selected="true">
	
    <center>
	<div style="margin-top:10px;">
{if isset($mailbox_error)}
		<span style="color:red;">{$mailbox_error}</span>
{/if}
	</div>
	<form name="frmLogin" action="" method="post">
	<table>
		<tr>
			<th style="text-align:right;">Phone:</th>
			<td><input class="login" type="text" name="mailbox" value="{$mailbox}" /></td>
		</tr>
		<tr>
			<th style="text-align:right;">Password:</th>
			<td><input class="login" type="password" name="password" value="" /></td>
		</tr>
		<!-- tr>
			<th style="text-align:right;">&nbsp;</th>
			<td><input type="checkbox" name="chkCookie" value="true" checked="checked" /> Save Cookie</td>
		</tr -->
		<tr>
			<th style="text-align:right;">&nbsp;</th>
			<td><input type="button" name="cmdLogin" value="Login" onclick="doLogin()" /></td>
		</tr>
	</table>
	</form>
	</center>
	   
	   
       
    </ul>
	
   


    
    
 
</body>
</html>
