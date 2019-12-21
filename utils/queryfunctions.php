<?php

	/*
	--------------------------------------------
				   queryfunctions.php
				   Will Briggs 2016-05
	--------------------------------------------

	Functions to do secure queries using prepared statements.
		
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

	//How many rows do you get if you execute this query?  Suitable for SELECT
require_once 'warnings.php';
	
function instancesFound ($conn, $query)
{
	if (! ($stmt = $conn->prepare($query))) sqlCrash ($conn);
	if (! $stmt->execute()) 				sqlCrash ($conn);
	if (! $stmt->store_result ()) 			sqlCrash ($conn);
	
	$result= $stmt->num_rows;	
	
	$stmt->free_result (); $stmt->close();	
	
	return $result;
}

	//Just do a query, and die on failure.  Suitable for INSERT, DELETE, and UPDATE
function executeQuery ($conn, $query)
{
	if (! ($stmt = $conn->prepare($query))) sqlCrash ($conn);
	if (! $stmt->execute()) 				sqlCrash ($conn);	
	$stmt->free_result (); $stmt->close();	
}	
?>