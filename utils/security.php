<?php
	//security.php
	//sanitizeInput sanitizes inputs for HTML injection and SQL injection
	//Other functions handle encryption
	//Will Briggs
	//June 2016
	//Edits by Brooklyn Welsh November 2019
	
/*	--

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
	
	require_once 'globals.php';				//For SALT1, SALT2, HOW_LONG_TILL_LOGOUT	
	require_once "warnings.php";
	
	function sanitizeInput ($connection, $string)
	{
		if (get_magic_quotes_gpc ()) $string = stripslashes ($string);
		$string = mysqli_real_escape_string ($connection, $string);
		$string = htmlentities ($string);
		return $string;
	}
	
	function myEncrypt ($thing)
	{
		return hash ('ripemd160', SALT1.$thing.SALT2);
	}
	
	function login ($conn)
	{	
		$result = '';

		$user_username = sanitizeInput ($conn,trim($_POST['username'])); //overkill on prepared statements?
		$user_password = sanitizeInput ($conn,trim($_POST['password'])); //perhaps...but HTML injection is still possible

		$user_username = strtolower($user_username);					//usernames are always lower case
		
		if(!empty($user_username)&&!empty($user_password))
		{ 			
			// Find out if member exists, and has this password
			$encryptedPassword = password_hash($user_password, PASSWORD_DEFAULT);
			//$encryptedPassword = myEncrypt ($user_password);\
			// Redo to use only username then check for hashed password
			// Check authenticate in the examples
			if (! ($stmt = 
			  $conn->prepare("SELECT * FROM users WHERE username = '$user_username'")))
			 	sqlCrash ($conn); 
		
		    if (! $stmt->execute()) 			sqlCrash ($conn);
			
		    if (! $stmt_result = $stmt->get_result()) 	sqlCrash ($conn); 
			//if (! $stmt->store_result()) 		sqlCrash ($conn);

			//if username exists, check to see if password given matches the real one, then set cookie and reload main
			if ($stmt->num_rows > 1) crashWarning ("Database has multiple entries for this user."); 
			if($stmt_result->num_rows==1)
			{ 
				$row = $stmt_result->fetch_assoc(); 

				// Check to see if password given matches the hashed password for the user in the DB
				if(password_verify($user_password, $row['password']))
				{
					setcookie("logintoken", $row['id'] . '|'.$user_username.$row['password'], time() + HOW_LONG_TILL_LOGOUT);
				}
				else die("Invalid username/password combination");
				
				$stmt->free_result(); $stmt->close();

				// Now we need to set the groupId cookie, just pick the first group_id for now, they can change group later
				if(! $group_select_stmt = $conn->prepare("SELECT group_id FROM group_roster WHERE user_id = ?")) die(mysqli_error($conn));
				if(! $group_select_stmt->bind_param('i', $row['id']))	die("Error binding parameters.");
				if(! $group_select_stmt->execute())	die("Error executing SQL statement");
				
				$group_result = $group_select_stmt->get_result();
				$group_info = $group_result->fetch_assoc();
				$group_id = $group_info['group_id'];
				$group_select_stmt->free_result();
				$group_select_stmt->close();

				// Now get the group name
				$sql = "SELECT group_name FROM groups WHERE group_id = $group_id";
				$getGroupInfo = mysqli_query($conn, $sql);
				$groupInfo = mysqli_fetch_array($getGroupInfo);
				
				$group_name = $groupInfo['group_name'];

				// Now we can set the cookie
				setcookie("currentGroupId", $group_id);
				setcookie("currentGroupName", $group_name);

				//Why are we reloading now?  Because cookies aren't set until the next page load -- and we need them!  
				//So this is right.
				// http://php.net/manual/en/function.setcookie.php
			}
			else 
			{
				$result= "error";
				$stmt->free_result(); $stmt->close();
			}
		}
		else 
		{
			$result= "empty";
			$stmt->free_result(); $stmt->close();
		}

	    //$stmt->free_result(); $stmt->close();
		
		// Edit: 11/11, going to try setting session variables for things like site name
		session_start();
		
		if ($result != '')
			header ("Location:index.php?login=".$result);
		else
		{
			header ("Location:index.php");
		}
	}

	function logout ()
	{
		if (isset ($_COOKIE['logintoken']))
			//expire all your cookies.  We use cookie to say who the user is.
			setcookie('logintoken','',time()-12000); 
		
		// Get rid of the currentGroupId and currentGroupName cookies
		setcookie('currentGroupId');
		setcookie('currentGroupName');
		//return to home page
		header('Location:index.php'); 
	}
	
	
?>