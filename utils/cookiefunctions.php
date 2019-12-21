<?php
	//cookiefunctions.php
	//Provides a function, getUserInfo, which uses cookies to tell who the current user is and whether s/he is an administrator
	//Will Briggs
	//May 2016
	
	// Edited by Brooklyn Welsh on 11/11/19
	
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

	require_once 'security.php';
		
		//Never call the next 3 functions from index.php, or you'll get a redirect loop
	function verifyLogin ($conn, &$id, &$name, &$isAdmin) //get user info...and STOP ACCESS to page if not logged in
	{
		getUserInfo ($conn, $id, $name, $isAdmin, $groupName); if ($id == '') { header ("Location:index.php"); die(); } 
	}
	
	function verifyAdmin ($conn, &$id, &$name, &$isAdmin) //get user info...and STOP ACCESS to page if not adminstrator
	{
		getUserInfo ($conn, $id, $name, $isAdmin, $groupName); if (! $isAdmin) { header ("Location:index.php"); }
	}
	
	function verifySuperAdmin ($conn, &$id, &$name, &$isAdmin) //get user info...and STOP ACCESS to page if not superadminstrator
	{
		getUserInfo ($conn, $id, $name, $isAdmin, $groupName); if ($id != 1) { header ("Location:index.php"); }
	}	
	
	function getUserInfo ($conn, &$currentuid, &$displayName, &$isUserAdmin, &$userGroupName) 
	{
		if (! isset ($_COOKIE["logintoken"])) { $currentuid = ''; $displayName = ''; $isUserAdmin = false; return; }

		$stuff = explode ('|', $_COOKIE['logintoken']);	//split login token into user id and encrypted part
		$currentuid = $stuff[0];
	
		if (! ($stmt = $conn->prepare("SELECT displayname, username, password FROM users WHERE '$currentuid' = id")))  sqlCrash($conn);
	
	    if (! $stmt->execute()) 																sqlCrash($conn);
	    if (! $stmt->bind_result($displayName, $username, $encryptedPassword))	sqlCrash($conn);
	    if (! $stmt->store_result ()) 															sqlCrash($conn);

		if ($stmt->num_rows > 1) 
			crashWarning ("Database error:  this user should have exactly one entry, but actually has ".$stmt->num_rows);
		//else if ($stmt->num_rows < 1)															//user not found? kill the cookie!
		//	logout ();
		$stmt->fetch ();
	    $stmt->free_result(); $stmt->close();
		
		if(!isset($_COOKIE["currentGroupId"]))
		{
			// Edit 11/11: Going to add code to support finding current group name
			// This code will assume user is only part of one group, will need to revisit to support multiple groups
			if(! ($group_find_stmt = $conn->prepare("SELECT group_id FROM group_roster WHERE user_id = '$currentuid'"))) 	sqlCrash($conn);
			if(! $group_find_stmt->execute())																				sqlCrash($conn);
			if(! $group_find_stmt->store_result ()) 																		sqlCrash($conn);
			if(! $group_find_stmt->bind_result($groupid))																	sqlCrash($conn);
			$group_find_stmt->fetch();
			$group_find_stmt->close();
			setcookie("currentGroupId", $groupid);	
		}
		
		if(!isset($_COOKIE["currentGroupName"]))
		{
			if(! ($group_name_find_stmt = $conn->prepare("SELECT group_name FROM groups WHERE group_id = '$groupid'")))	sqlCrash($conn);
			if(! $group_name_find_stmt->execute())																				sqlCrash($conn);
			if(! $group_name_find_stmt->store_result())																			sqlCrash($conn);
			if(! $group_name_find_stmt->bind_result($userGroupName))															sqlCrash($conn);
			$group_name_find_stmt->fetch();
			$group_name_find_stmt->close();

			setcookie("currentGroupName", $userGroupName);
		}
		
	    if ($stuff[1] != $username.$encryptedPassword) //if encrypted part didn't match, say
	    {																 //we aren't logged in
	      $currentuid = $displayName = ''; $isUserAdmin = false;
	    }
		
		// Now find out if user is admin of CURRENT group which is set in the currentGroupId cookie
		if (! ($stmt = $conn->prepare("SELECT is_admin FROM group_roster WHERE '$currentuid' = user_id")))  sqlCrash($conn);
		if (! $stmt->execute())																				sqlCrash($conn);
		if (! $stmt->bind_result($isUserAdmin))																sqlCrash($conn);
	    if (! $stmt->store_result ()) 																		sqlCrash($conn);
		$stmt->fetch();
		$stmt->close();
	}
?>