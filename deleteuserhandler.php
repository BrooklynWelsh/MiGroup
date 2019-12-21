<?php /*
	--------------------------------------------
				 deleteuser.php
					Yu Fu
				  2015-11-14
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	This file handles requests to delete users.

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
	require_once 'utils/cookiefunctions.php';
	
	verifySuperAdmin ($conn, $id, $name, $isAdmin); //echo 'dont get this far unless you are superadmin'; die();
	$result = "";
	
	if ($_GET['id'] && $_GET['id']!="")
	{
												//Get id of user to delete
		$uid=$_GET['id'];
		
												//Delete all checkouts for this user
		$query="DELETE FROM checkouts where userId=$uid";
		executeQuery ($conn, $query);
			
												//Then delete user from users
		$query = "DELETE FROM users WHERE id=$uid";
		executeQuery ($conn, $query);
		$result = "userdeleted";	
	}
	
												//Clean up and leave
	mysqli_close ($conn); 
	$href="manageusers.php?result=".$result;
	header("Location:".$href); 
?>

	