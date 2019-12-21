<?php
	/*
	--------------------------------------------
				restoremeetinghandler.php
					2016-05 by W Briggs
	--------------------------------------------
	Restored a canceled meeting.
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
	$dateId=$_GET['dateid'];
	$action=$_GET['action'];
				
				//Bad argument? User isn't administrator?  Quit
	if ($dateId == ""  || ! isUserAdmin) header ("Location:index.php?result=operationfailed");
	if ($action == "") header ("Location:index.php");
		
				//But if so, delete this meeting
	if ($action == 'restore')
	{
		$query = "UPDATE dates SET canceled = false WHERE id=$dateId";
		executeQuery ($conn, $query);
	}
	else if ($action == 'erase')
	{
		executeQuery ($conn, "DELETE FROM guests WHERE dateId = $dateId");
		executeQuery ($conn, "DELETE FROM checkouts WHERE dateId = $dateId");
		executeQuery ($conn, "DELETE FROM dates WHERE id = $dateId");
	}
				//Checkout complete -- go back to index
	header("Location:index.php?result=operationsucceeded"); 
?>