<?php

/*
	--------------------------------------------
				adddeletedateshandler.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	This handles requests to add or delete meetings.

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
	
	verifyAdmin ($conn, $id, $name, $isAdmin); //if user isn't administrator, don't load this file.
	
	$result = "noaction";					//a default URL argument, in case nothing happens
	
	if (isset($_POST['deleteDate']))
	{
		$selected_date_id=$_POST['date'];
		//$delete_checkouts="Delete from checkouts where dateId=$selected_date_id";
		//executeQuery ($conn, $delete_checkouts);
		
		//$delete_date="Delete from dates where id=$selected_date_id";
		$query = "UPDATE dates SET canceled = true WHERE id = $selected_date_id";
		executeQuery ($conn, $query);
		
		$result = "datedeleted";
	}
	else if(isset($_POST['addDate']))
	{
		$new_date=$_POST['date'];
		$new_date_strtotime=strtotime($new_date);

		if($new_date!="")
		{	
			$today = strtotime (date ("Y-m-d"));	
			
			if ($new_date_strtotime < $today) 
			{
				$result = 'datebeforetoday';
			}
			else
			{
				//check if date exist?
				$find_date="select date from dates where date='$new_date_strtotime' limit 1";
								
				if (instancesFound ($conn, $find_date) > 0)
				{
					$result = "dateexists";
				}
				else
				{
					//add a new date
					executeQuery ($conn, "Insert dates (date) value('$new_date_strtotime')");
					$result = "dateadded";
				}
			}
		}
	}

	mysqli_close ($conn);
	$href="index.php?result=".$result;
	header("Location:".$href); 
?>