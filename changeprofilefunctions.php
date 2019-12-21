<?php
/*
	--------------------------------------------
		     changeprofilehandler.php
				Yu Fu
			  2015-11-18
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	Updates profile information, based on information posted in changeprofileform.php.
	
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

require_once 'utils/security.php';
require_once 'utils/warnings.php';

function processProfileChange ($conn, $user)
{
	if (isset($_POST['submit']))
	{	
		$somethingGotUpdated = false;
		
		//Get form data
		$opw			= $_POST['oldpw'];
		$npw			= $_POST['password'];
		$nrepw			= $_POST['repw'];
		$newDisplayName = $_POST['displayname'];
		$newUserName	= $_POST['username'];
		$hidden			= $_POST['hidden']; 

		$newDisplayName = sanitizeInput ($conn,trim($newDisplayName)); //overkill on prepared statements?
		$newUserName    = sanitizeInput ($conn,trim($newUserName));     //maybe, but we need to stop HTML injection
		$newUserName    = strtolower ($newUserName);
		
			//If new username, displayname, and password aren't provided...there's not thing to do.  Quit
		if ($newDisplayName == "" && $npw == "" && $newUserName == "" && $hidden == "") return 'noprofileinputs';
		
			//find out if user was already hidden
		$query  = "SELECT hidden from users WHERE id=$user";
		($result = mysqli_query($conn,$query)) or sqlCrash ($conn);
		$row = $result->fetch_assoc(); print_r ($row);
		$wasHidden = ($row['hidden'] == 1);
	
		if ($hidden == 'hidden' && ! $wasHidden)						//toggle hiddenness if need be
		{
			executeQuery ($conn, "UPDATE users SET hidden=1 WHERE id=$user");
			$somethingGotUpdated = true;
		}
		else if ($hidden != 'hidden' && $wasHidden)
		{
			executeQuery ($conn, "UPDATE users SET hidden=0 WHERE id=$user");
			$somethingGotUpdated = true;
		}
		
			//If display name conflicts with an existing one, quit
		if ($newDisplayName)
		{
			$query = "SELECT id FROM users WHERE displayname='$newDisplayName'";
			if (! ($stmt = $conn->prepare($query))) sqlCrash ($conn);
			if (! $stmt->execute()) 				sqlCrash ($conn);
			if (! $stmt->bind_result($whoConflicts))sqlCrash ($conn);
			if (! $stmt->store_result ()) 			sqlCrash ($conn);
		
			if ($stmt->num_rows != 0)				//This display name already exists!
			{	
				$stmt->fetch ();
	
				if ($stmt->num_rows ==1 && $whoConflicts == $user) //the only duplicate is... your old display name
					;					//OK, you're resetting your display name back to what it was; ignore this
				else
				{
					return 'displaynameexists';
				}
			}	
		
				//Display name is OK to add.  Let's add it
			executeQuery ($conn, "UPDATE users SET displayname='$newDisplayName' WHERE id=$user");
			$somethingGotUpdated = true;
		}
		
			//If user name conflicts with an existing one, quit
		if ($newUserName)
		{
			$query = "SELECT id FROM users WHERE username='$newUserName'";
			if (! ($stmt = $conn->prepare($query))) sqlCrash ($conn);
			if (! $stmt->execute()) 				sqlCrash ($conn);
			if (! $stmt->bind_result($whoConflicts))sqlCrash ($conn);
			if (! $stmt->store_result ()) 			sqlCrash ($conn);
		
			if ($stmt->num_rows != 0)				//This user name already exists!
			{	
				$stmt->fetch ();
	
				if ($stmt->num_rows ==1 && $whoConflicts == $user) //the only duplicate is... your old user name
					;					//OK, you're resetting your user name back to what it was; ignore this
				else
				{
					return 'userexists';
				}
			}	
		
				//Display name is OK to add.  Let's add it
			executeQuery ($conn, "UPDATE users SET username='$newUserName' WHERE id=$user");
			$somethingGotUpdated = true;
		}

				//Now ensure that old and new passwords are there, quit if not.  (If all are empty and we just updated display name, OK)
		if ($somethingGotUpdated && $opw=="" && $npw == "" && $nrepw == "") return 'profileupdatesuccessful';
		if ($opw=="") 		 						return 'oldfail'; 			
		if ($npw=="") 		 						return 'passwordmissing'; 	
		if ($npw != $nrepw)  						return 'passwordsdontmatch'; 	 	
				
				//All clear.  So look up the user and see if old password is right.
		$query="select password from users where id = $user";
		if (! ($stmt = $conn->prepare($query))) sqlCrash ($conn);
		if (! $stmt->execute()) 				sqlCrash ($conn);
		if (! $stmt->bind_result($password))	sqlCrash ($conn);
		if (! $stmt->store_result ()) 			sqlCrash ($conn);
		
		if ($stmt->num_rows == 0) 				crashWarning ("Database error -- this user is not found.  Please reinstall.");
		if ($stmt->num_rows >  1)				crashWarning ("Database error -- multiple copies of same user.  Please reinstall.");
		
		$stmt->fetch ();
		
		//if ($password != myEncrypt($opw))		return 'oldfail';
		
		$stmt->free_result (); $stmt->close();		

			//All clear.  Change the password.
		$update_pw="update users set password ='".myEncrypt($npw)."' where id = '$user'";
		executeQuery ($conn, $update_pw);
		
			//Logout user.  We'll make 'em log in again with the new password.  
		logout (); 
	} 
	else if (isset($_POST['cancel']))									return 'cancel';

	return ''; 
}
?>