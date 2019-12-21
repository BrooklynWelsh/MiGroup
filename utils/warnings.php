<?php

	/*
	--------------------------------------------
				  warnings.php
				  Will Briggs 2016-05
	--------------------------------------------

	Provides:
		function to politely report a fatal error condition
		function to find out and politely report an SQL error
		function to print some warning in the appropriate font and size
		function to get and print that warning from URL arguments
		
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

	require_once 'globals.php';
	
	function sqlCrash ($conn)
	{
		crashWarning (mysqli_error ($conn));
		//die (CRASH_MESSAGE_PREFIX.mysqli_error ($conn).CRASH_MESSAGE_SUFFIX);
	}	
	
	function crashWarning ($message)
	{
		die (CRASH_MESSAGE_PREFIX.$message.CRASH_MESSAGE_SUFFIX);
	}
	
	
	function printWarning ($warning)
	{
		echo '<center><span class="warning">';
			
		switch ($warning)
		{
		case "dateadded":			echo "Date added successfully."; 					break;
		case "dateexists":			echo "Date exists.  Please select a new date!"; 	break;
		
		case "datedeleted": 		echo "Date deleted!"; 								break;

		case "userexists": 			echo "User already exists! Please try a different username."; 	break;
		case "emptyfield":			echo "Please fill in all blanks."; 								break;
		case "passwordsdontmatch":	echo "Passwords do not match.";			 					 	break;
		case "displaynameexists": 	echo "Display name is taken -- please choose another."; 		break;
		case "badusername":			echo "Username does not match requirements.";				 	break;
		
		case "oldfail": 			echo "Old password is not correct."; 							break;
		case "profileupdatesuccessful": echo "Profile update was successful.";						break;
		case "noprofileinputs":		echo "No input -- please try again.";							break;
		case "passwordmissing":		echo "Password not updated.";									break;
		
		case "regsuc":				echo "Registration successful!"; 					break;
		case "adsuc":				echo "Admin users setup successful."; 				break;
		case "adminprivsremoved": 	echo "Admin privileges removed from user(s)."; 		break;			
		
		case "pwsuc":				echo "Password changed successful. Please re-login."; 			break;
		
		case "userdeleted": 		echo "User deleted."; 								break;
		
		case "weekdaychanged": 		echo "Default weekday changed successfully."; 		break;
		case "datebeforetoday": 	echo "Please pick a date today or later.";			break;
		
		case "ci": 					echo "Checkout was successful"; 					break;
		case "co": 					echo "Checkin was successful"; 						break;
		
		case "error": 				echo "You typed in wrong Username/Password!"; 		break;
		case "empty": 				echo "Username and Password cannot be empty!"; 		break;
		
		case "nosuchdate":			echo "There is no meeting on this date.";			break;
		case "guestadded":			echo "Guest added.";								break;
		case "guestnotfound":		echo "Guest not found.";							break;
		case "guestnotdeleted":		echo "Couldn't delete guest.";						break;
		
		case "operationfailed":		echo "Operation failed.";							break;
		case "operationsucceeded":	echo "Operation successful.";						break;
		
		case "groupexists":			echo "A group with this name already exists! Please enter a different one.";		break;
		}
		echo "</span></center>";
	}
	
	function printMessageFromURLArgument ()
	{
		if (isset($_GET['result'])) printWarning ($_GET['result']); //Ordinary messages			
		if (isset($_GET['status'])) printWarning ($_GET['status']); //and one that also does duty telling calendar to ck members in/out
	}
?>
