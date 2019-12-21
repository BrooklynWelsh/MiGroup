<?php

/*
	--------------------------------------------
				addguesthandler.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
			  Edits 2019-11 by Brooklyn Welsh
	--------------------------------------------
	This file handles requests to add guests.

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

require_once 'utils/dbconnection.php'; 
require_once 'utils/queryfunctions.php';
require_once 'utils/security.php';
require_once 'utils/cookiefunctions.php';

function handleGuest ($conn, $inviterId)
{
	$name = $date = ""; //Get our arguments
	
	if (! isset($_POST['addGuest'])) 	return "";				//If they didn't click submit, do nothing
	if (isset($_POST['name'])) 			$name 		= sanitizeInput ($conn,trim($_POST['name']));
	if (isset($_POST['date'])) 			$dateId 	= sanitizeInput ($conn, $_POST['date']);

	if ($name == "" || $dateId == "") return "emptyfield"; 	//If they didn't fill in a field, do nothing
	
	if ($inviterId == "") logout ();				//I don't think this can happen!

	// Get the id of the group that is adding the guest
	if(isset($_COOKIE['currentGroupId']))
	{
		$current_group_id = $_COOKIE['currentGroupId'];
	}
	else die("Error: No group ID cookie set");
	
	//Add the guest
	$query="INSERT INTO guests(dateId,name,inviterId,groupId) VALUES($dateId,'$name',$inviterId,$current_group_id)";
	executeQuery ($conn, $query);
	return "guestadded";
}

verifyLogin ($conn, $id, $name, $isAdmin); 				//if you aren't admin, you can't load this file	  

$result = handleGuest ($conn, $id);						//OK, so handle the guest request (if any)
$href="index.php?result=$result";						//And reload the main page.
header("Location:".$href); 
?>