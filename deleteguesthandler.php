<?php
	/*
	--------------------------------------------
				deleteguesthandler.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
			  Edits 2019-11 by Brooklyn Welsh
	--------------------------------------------
	Delete a listing for a guest coming to a meeting.
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
	require_once 'utils/cookiefunctions.php';

	verifyLogin ($conn, $userId, $userName, $isUserAdmin); //get user info, and ensure someone's logged in
	
				// Who's the guest?  On what date is he coming?
	$name=  addslashes($_GET['name']); //Just in case $name has a ' or " or such in it
	$dateid=$_GET['dateid'];
				
				//Bad arguments?  Quit	
	if ($name == "" || $dateid == "") header ("Location:index.php?result=guestnotfound");
		
				//Does the user really have permission to do this?
	$permissionsOK = false;

	if ($isUserAdmin) $permissionsOK = true; 
	else
	{			
		$query = "SELECT inviterId FROM guests WHERE dateId=$dateid AND name='$name'";
		if (! ($stmt = $conn->prepare($query)))  	sqlCrash($conn);
	    if (! $stmt->execute()) 					sqlCrash($conn);
	    if (! $stmt->bind_result($inviterId))		sqlCrash($conn);
	    if (! $stmt->store_result ()) 				sqlCrash($conn);
		
		$stmt->fetch ();
		
		if ($inviterId == $userId) $permissionsOK = true;
		
	    $stmt->free_result(); $stmt->close();
	}
	
				//If not, abort
	if (! $permissionsOK) header("Location:index.php?result=guestnotdeleted");
	
	if(isset($_COOKIE['currentGroupId']))
	{
	$currentGroupId = $_COOKIE['currentGroupId'];
	} else die("Error: Current group cookies not set. This shouldn't happen.");
				//But if so, delete this guest			
	$query = "DELETE FROM guests WHERE dateId=$dateid AND name='$name' AND groupID = $currentGroupId";
	executeQuery ($conn, $query);
	
				//Checkout complete -- go back to index
	header("Location:index.php?status=ci"); 
?>