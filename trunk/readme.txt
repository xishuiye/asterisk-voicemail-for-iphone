
Asterisk Voicemail for iPhone
Copyright 2008 Chris Carey (http://chriscarey.com)
This program is distributed under the terms of the GNU General Public License

==============================================================================		
Development: Known Issues
==============================================================================

- Need to write voicemail.conf authentication. We only have a stub function which does no authentication. This is because on my dev server I am using mysql authentication. MySQL authentication is functional. This is one area where I could use some assistance. The voicemail.conf login function is in i_functions.php and needs some work.
			
- Listening to messages is done in an unsecure way. We are adding an apache alias which anyone can go to the URL and listen. Need to write a php page which will perform session/security checks and pass the mp3 sound file through. The iPhone quicktime interface grabs the music in chunks which I am not sure how to handle (sample code in listen.php is a good head start, but it needs to be fixed)
			
- Some MP3s are not playable. For some reason they always have a duration of 10 seconds. I believe this to be a bug with the mp3 generation (sox or lame). This seems to happen maybe once every 10 or 20 messages.

==============================================================================
Development: ChangeLog (History)
==============================================================================

2008-03-04 (v0.02 release)
- Write preliminary function for voicemail.conf authentication
		
2008-03-03 (v0.01 release)
- Prepare code for release
- Write stub function for voicemail.conf authentication
				
2008-02-25
- Added "Settings" back, and Added "Move Messages" button. Both of these features are non-functional at this time.
- Cleaned up date format on Message Detail, and changed it's font size back up to normal<br />
	
2008-02-24
- Added Cookie Support
- Renamed "Check Messages" to "Messages"
- Displaying "Saved" messages
- Added ChangeLog
- Updated About page with email link
- Removed "Settings" page until I get it working
- Allow 7 digit logins, will prepend a default prefix
- Disabled iPhone's "format detection" for phone numbers
		
