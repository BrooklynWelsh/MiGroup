<?php
	/*
	--------------------------------------------
				   globals.php
					W Briggs
				  2016-05-25
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	Some global preferences, set here, used as needed in other files.

	--

	Mi-Group:  a web site for keeping track of checkouts for a small-group organization.
	Copyright (C) 2015-2016 Yu Fu and Will Briggs of Lynchburg College.

	  This program is free software: you can redistribute it and/or modify
	  it under the terms of the GNU General Public License as published by
	  the Free Software Foundation, either version 3 of the License, or
	  (at your option) any later version.

	  This program is distributed in the hope that it will be useful,
	  but WITHOUT ANY WARRANTY; without even the implied warranty of
	  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	  GNU General Public License for more details.

	  You should have received a copy of the GNU General Public License
	  along with this program.  (It's in the file GPL.txt.)  If not, see
	   <http://www.gnu.org/licenses/>.

	*/

	// SITE_NAME will need to be a global variable and not a constant, since we could change groups
	// Look into using isset and sessions to detect group and site names
	

	define ('SITE_NAME','Welcome to MiGroup'); // Used for the new installation screen
		
	define('DB_HOST', 'localhost');			//Database login information
	define('DB_USER', 'root');				//You'll need to change the password; maybe more
	define('DB_PASSWORD', ''); 				//depending on how your system is set up
	define('DB_NAME','shcvly');			
										
	define ('LIST_MEMBERS_COMING', true); 	//Set to false if you want your calendar to display members
											//only members who ARE coming.  As it is, it lists both those
											//coming and those not coming.  Administrators see who's not coming regardless	
											
	define ('WEEKS_BACK_TO_KEEP_IN_CALENDAR', "2");	//After the meeting date, how many weeks should we
													// keep the meeting in the calendar, before erasing it?
	define ('WEEKS_TO_LOOK_AHEAD', "10");				//How many weeks past current date should the calendar
													// show (and allow checkouts)?	
													
	define ('HOW_LONG_TILL_LOGOUT', 86400*365*10);	//How long till your login expires.  I'm saying 10 years (!)
													// that is, effectively forever	
													// so you can stay logged in forever on your mobile device
													
	date_default_timezone_set('America/New_York');  //Set time zone 
													/*Other common time zones include
													Europe/Rome			Europe/London
													Africa/Johannesburg
													America/Chicago		America/Denver		America/Los_Angeles
													America/Phoenix 
													Pacific/Honolulu
													Pacific/Auckland
													Australia/Brisbane 	Australia/Sydney 	Australia/Perth
													*/
	define ('CRASH_MESSAGE_PREFIX', 'A serious error has occurred that may prevent use of this site until rectified:<br><br>');												
	define ('CRASH_MESSAGE_SUFFIX','<br><br>Please report this error to your site administrator.'); //Appended to all crash messages			

				//What follows is for experts only
				
	//define ('SALT1', "Jd8%!N");						//These are random strings for encrypting passwords & cookies
	//define ('SALT2', "*}#ea]");						//You can change them if you like
	//define ('SALT3', "^yi&+R");						//If you alter the first 2, you'll have to alter the admin password as stored								
														
													//See index.php, near the top, for instructions on how to do that
													// (around the word "myEncrypt")
																								
?>				