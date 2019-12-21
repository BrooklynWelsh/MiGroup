<?php
	/* 
	--------------------------------------------
				  dbconnection.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	Connect to the database.
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

	require_once 'globals.php';
	require_once 'warnings.php';
	
	// get connection
	$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);

	// conn fail
	if (!$conn) crashWarning ("Database connection failed.");
	
	//select db
	if(!mysqli_select_db($conn,DB_NAME))
	{
		// Consider rerouting to a .php file that contains all of our .sql file queries and runs them. 
		
	}	
	// or crashWarning ("Unknown database.");
?>