<?php

	/*
	--------------------------------------------
				manageadminstatushandler.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
			  Edits 2019-12 by Brooklyn Welsh
	--------------------------------------------
	This processes requests	to change administrator status of users.
	
	Welsh: 	Updated to add more explicit controls for adding and removing admin rights for all users.
			Also, fixed logic in SQL queries and modified them to work with groups.
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


	require_once 'utils/dbconnection.php'; 			//connect to database
	require_once 'utils/queryfunctions.php';
	require_once 'utils/warnings.php';
	require_once 'utils/cookiefunctions.php';
	
	verifyAdmin ($conn, $id, $name, $isAdmin); 
	
	$sql = "SELECT * FROM users WHERE id!='1'"; 	// select all users who aren't user #1
	$get_uinfo = mysqli_query($conn,$sql) or sqlCrash ($conn);
	
	while($userrow = mysqli_fetch_array($get_uinfo))  	//make them not be admin's.  Why? Because the user just checked and unchecked
														// them in the form.  We should go with what he just posted in $_POST,
														// not the old values
	{
		$sql_admin = "UPDATE group_roster SET is_admin=0 WHERE user_id='$userrow[id]' AND user_id != 1";
		executeQuery ($conn, $sql_admin);
		$result="adminprivsremoved";
	}
	
	$adminUser=$_POST["adminUser"];
	$count_admin=count($adminUser);					//Now only set the ones labeled adminUser in $_POST, to admin
	
	//update setup admin
	$result="adsuc";
	for($i=0;$i<$count_admin;$i++)
	{
		$sql_setAdmin = "UPDATE group_roster SET is_admin = 1 WHERE user_id=".$adminUser[$i].";";
		executeQuery ($conn,$sql_setAdmin);
	}
	
	executeQuery ($conn, "UPDATE users SET hidden = false WHERE admin = 0"); //if you're not an admin, you're not hidden
		
	mysqli_free_result ($get_uinfo);
	mysqli_close ($conn);
	header('Location: manageusers.php?result='.$result); //back to index
?>