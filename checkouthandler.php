<?php
	/*
	--------------------------------------------
				checkouthandler.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	This php file processes checkout/ checkin requests.
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
	require_once 'utils/security.php';
		
	verifyLogin ($conn, $myId, $userName, $isUserAdmin); //if user isn't logged on, don't load the page
															//...find out who current user is
	
	function isCheckedOut ($conn, $userid, $dateid)
	{
		$query = "SELECT dateid, userid FROM checkouts WHERE dateId=$dateid AND userId = $userid";
		return instancesFound ($conn, $query) > 0;
	}
	
	// Who's checking out?  And for what date?
	$userid=sanitizeInput ($conn, $_GET['id']);		//Good practice to sanitize anything
	$dateid=sanitizeInput ($conn, $_GET['dateid']);	// you might put into database?
	
	if (! $isUserAdmin && $userid != $myId)			//if I am not administrator OR the user checking out...
															// looks like someone fudged a URL argument.  Do nothing.
		header ("Location:index.php?result=operationfailed");
		
	// Decide on query based on whehter the member is checked out
	if(isCheckedOut ($conn, $userid, $dateid))
	{
		$sql = "DELETE FROM checkouts WHERE dateId=$dateid AND userId=$userid;";
		$status="co";
	}
	else 
	{
		$sql = "INSERT INTO checkouts (dateId,userId) VALUES ($dateid,$userid)";
		$status="ci";
	}
	 
	//Make the query:  eliminate checkout, or add it
	executeQuery ($conn, $sql);
	
	//Checkin/checkout complete -- go back to index
	$href="index.php?status=".$status."&dateid=".$dateid;
	header("Location:".$href); 	
?>