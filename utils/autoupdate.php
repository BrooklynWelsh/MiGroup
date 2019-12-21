<?php

	/*
	--------------------------------------------
				autoupdate.php
					Yu Fu
				  2015-10-3
			  Major edits 2016-05 by W Briggs
	--------------------------------------------
	The function autoupdate runs when the user logs in.
	If the database table dates is empty
	   it creates a range of dates
	Else
	   get current date
	   for all dates earlier than current date - WEEKS_BACK_TO_KEEP_IN_CALENDAR, erase 'em
	   for all dates beyond current date + WEEKS_TO_LOOK_AHEAD, if it doesn't exist, create it
			
	So there are always WEEKS_BACK_TO_KEEP_IN_CALENDAR+WEEKS_TO_LOOK_AHEAD dates in database:
			WEEKS_BACK_TO_KEEP_IN_CALENDAR    weeks ago, on $weekday
			WEEKS_BACK_TO_KEEP_IN_CALENDAR-1  weeks ago, on $weekday
			...
			WEEKS_TO_LOOK_AHEAD weeks ahead, on $weekday
			
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
	require_once 'datefunctions.php';
	require_once 'warnings.php';
	require_once 'queryfunctions.php';
	
	function deleteOldMeetings ($connection)
	{	
	    $debug = false;				//Do a little debugging on this script

		$begin_date = strtotime(" - ".WEEKS_BACK_TO_KEEP_IN_CALENDAR ." weeks");	// oldest
		if ($debug) echo "The day to begin is $begin_date.<br>";

		$query = "SELECT * from dates ORDER BY date";
		$queryResult = mysqli_query($connection,$query );
		if ($debug) echo $query .'<br>';
	
		if (! $queryResult)	return;							//just quit if queryResult is bad
			
		while ($daterow = mysqli_fetch_array ($queryResult))//for each date
		{
			if ($debug) echo 'Row '.$j.': '.$begin_date." vs. ".$daterow['date'].'.  ';
			
			if ($begin_date > $daterow['date'])				//if it's earlier than our cutoff
			{												// delete it and all ckouts on that date
				if ($debug) echo '<br>';
															// delete all old checkouts 
				executeQuery($connection,"DELETE from checkouts where dateId =".$daterow['id']);
															// delete all old guest invites
				executeQuery($connection,"DELETE from guests where dateId =".$daterow['id']);
															// update old date to new date
				executeQuery($connection,"DELETE FROM dates WHERE id = ".$daterow['id']);	
			}
			else if ($debug) echo 'Leave this one alone.<br>';	
		}
		
		mysqli_free_result ($queryResult);
	}

	function addNewMeetings ($connection, $dateToStart)
	{
		$debug = false; 										//used for debugging, of course	

															//Find last scheduled meeting date
		$new_date   = strtotime(" + ".WEEKS_TO_LOOK_AHEAD." weeks");	// newest 
		if ($debug) echo "The timestamp to end   is $new_date.<br>";	
		$lookahead = WEEKS_TO_LOOK_AHEAD;

		$new_weekday = getWeekdayForNewMeetings ($connection);
		
		$result = mysqli_query ($connection, "SELECT * FROM dates ORDER BY date DESC LIMIT 1");
		if (! $result) sqlCrash ($connection);  
				
		if ($debug) { echo 'Here\'s the query result: '; print_r ($result); echo '<br>'; }
		$row = mysqli_fetch_array ($result);
		if ($debug) { echo 'And here\'s the first row, if any: '; print_r ($row); echo '<br>'; }
		if (! $row)											//If the dates table is empty
		{
		    if ($debug) echo 'There are no dates in the database; better add some.<br>';
	
			$start = 0;
			if (weekday (date('w')) == $new_weekday)
			{
				$today = strtotime ("today");
				$query = "INSERT INTO dates(date) VALUES ('$today')";
				if ($debug) echo $query." -- this one is today!<br>\n";
				else executeQuery ($connection, $query);
				//$start = 1;
			}

			for ($i = $start; $i < (int) $lookahead; $i++) //Fill it up!
			{
				$add_date=strtotime("next ".$new_weekday." +".$i." weeks");
				$query = "INSERT INTO dates(date)VALUES('$add_date')";
				if ($debug) echo "Add $start weeks to get ".$query.'<br>';
				else 
				{
					executeQuery($connection,$query);
				}
			}
		}
		else												//If not, when's the last meeting?
		{
			if ($dateToStart == '')
			{
				$dateToStart = $row['date'];
	
				if ($debug) echo "Last meeting so far is   ".$dateToStart;
				if ($debug) echo " or ".date("M d, Y", $dateToStart)."<br>";
			}
			else if ($debug) echo "Our given starting date was ".date("M d, Y", $dateToStart)."<br>";
	
															//When's the next meeting day after that one?
			$nextMeetingToCreate = strtotime ("next ".$new_weekday, $dateToStart);

			if ($debug) echo "So next mtg to create is ".$nextMeetingToCreate.", or ";	
			if ($debug) echo date("M d, Y", $nextMeetingToCreate)."<br>";		
			
			if ($debug) echo "We'll go up through      ".$new_date.", or ".date("M d, Y", $new_date)."<br>";
			
															//Starting there, add meetings, till you get to end of
															// range
			while ($nextMeetingToCreate < $new_date)
			{
  			  $query = "INSERT INTO dates(date) VALUES ('$nextMeetingToCreate')";
			  executeQuery ($connection, $query); 

			  $nextMeetingToCreate = strtotime ("+1 week", $nextMeetingToCreate);
			}
		 }
		 
		mysqli_free_result ($result);	
			 
		if ($debug) echo "No crashes.<br>";
		if ($debug) die ();		
	}

	function getWeekdayForNewMeetings ($connection)	//Return the weekday of the meeting, which is something like "Monday"
	{
													//The table weekday has one field weekday, and one row.
		$queryResult = mysqli_query ($connection,"select * from weekday");
		
		if (mysqli_num_rows($queryResult) != 1) 
			crashWarning ("Database corrupted:  the weekday table should have 1 entry.  Please reinstall.");
			
		$row = mysqli_fetch_array($queryResult);
		
		mysqli_free_result ($queryResult);
		
		return weekday ($row['weekday']);
	}
	
	function thereAreNoMeetingsScheduled ($conn)
	{
		$query = "SELECT * FROM dates ";
		return instancesFound ($conn, $query) == 0;
	}
	
	function autoupdate ($connection)
	{
	    if (thereAreNoMeetingsScheduled ($connection)) header("Location:setup.php");	//Before going further, get weekday for our new mtgs
	    									
		deleteOldMeetings ($connection);
		addNewMeetings    ($connection, '');		
	}
?>