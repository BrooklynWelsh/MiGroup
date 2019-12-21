<?php
	/*
	--------------------------------------------
				   calendar.php
					Yu Fu
				  2015-10-31
			  Major edits 2016-05 by W Briggs
			  Edits 2019-11 by Brooklyn Welsh (Small edits for making calendar compatible with new CSS)
	--------------------------------------------
	This shows the calendar:  date, how many, who's coming.

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

	require_once 'utils/globals.php';		//for LIST_MEMBERS_COMING global
	require_once 'utils/dbconnection.php'; 
	require_once 'utils/cookiefunctions.php';
	
											//Find out (names of) who's checked out and who isn't
	function getAttendees ($databaseConnection, $meetingDate, $uid_checkout_array, &$stillComing, &$notComing)
	{
		//select names from database userinfo
		//$query  = "SELECT id,displayname, hidden FROM users WHERE DATE <= $meetingDate ORDER BY displayname;";
		
		// To get only the attendees for the current group, we need to get user_ids from table 'group_roster'
		// which are matched with currentGroupId.
		$currentGroupId = $_COOKIE['currentGroupId'];
		$user_id_query = "SELECT user_id FROM group_roster WHERE group_id = $currentGroupId;";
		$user_id_result = mysqli_query($databaseConnection,$user_id_query) or sqlCrash ($databaseConnection);
		
		$user_id_rows = array();
		while($user_id_row = $user_id_result->fetch_assoc())
			$user_id_rows[] = $user_id_row;
		
		// Now that we have the array of user_ids for current group, we can get their info from the 'users' table
		$user_name_rows = array();
		foreach($user_id_rows as $row)
		{
			$current_user_id = $row['user_id'];
			$user_name_query = "SELECT id, displayname, hidden FROM users WHERE id = $current_user_id;";
			$user_name_result = mysqli_query($databaseConnection,$user_name_query) or sqlCrash ($databaseConnection);
			$current_user_name = $user_name_result->fetch_assoc();
			array_push($user_name_rows, $current_user_name);
		}
		
		//echo $query.'<br>';
		//($result = mysqli_query($databaseConnection,$query)) or sqlCrash ($databaseConnection);

		//Convert query result to an array
		/* $rows= array();
		while ($row = $result->fetch_assoc()) 
 		 	$rows[] = $row; */
		
		//Now extract all the names that aren't checked out, put 'em in $stillComing
		//Those that are checked out go into $notComing
		// We must initialize each array each time or we'll get last date's
		// entries
		$stillComing = array (); $notComing   = array ();
		foreach ($user_name_rows as $row)
		  if (! $row['hidden'])
			if (in_array ($row['id'],$uid_checkout_array))
				$notComing[]   = $row;
			else
				$stillComing[] = $row;
				
	}
	
												//Find out (names of) any guests coming
	function getGuests ($databaseConnection,$dateId, &$rows)
	{
		if(isset($_COOKIE['currentGroupId']))
		{
			//select names from database userinfo
			$query  = "SELECT name, inviterId, groupId from guests WHERE dateId=$dateId";
			($result = mysqli_query($databaseConnection,$query)) or sqlCrash ($databaseConnection);

			//Convert query result to an array
			$rows= array();
			while ($row = $result->fetch_assoc())
			{
				// Only show guests from the relevant group
				if($row['groupId'] == $_COOKIE['currentGroupId'])	$rows[] = $row;
			}		
		}
		else die("Error: currentGroupId cookie not set. This should always be set.");
	}

											//Display names of all in a list
											//If you're administrator, you also get a button
											// to check the member in or out
	function listNames ($names, $action, $date, $currentdateid, $administrator)
	{
		//Print each element of a list of names.  Put commas after
		// all but last entry.
		for ($i = 0; $i < count($names); ++$i)
		{
			$row = $names[$i];
			
				//Make these names to be clickable to check in/out if the current user is administrator

			if ($administrator && $date>=strtotime("today")) //for dates today or later...
			{
					//Here I set up an onclick function of form
					// if (confirm ("Really check this user out?")) 
					//   windows.location.href=checkouthandler.php?id=userid&date=currentdate
					
					//It's a little complicated because of trying to nest 's, at least
					//  when the display name contains a '
					
				$question = "Really check ".addslashes($row['displayname']).' '.$action."?";
				$action_url="window.location.href='checkouthandler.php?id=".
						$row['id']."&dateid=".$currentdateid."'";

				echo "<button class='linkbutton' " ;
					echo 'onclick="if (confirm (\''.$question.'\')) '.$action_url.'"';
				
					echo ">".$row['displayname'];
					if ($i < count ($names)-1) echo ',';
				echo "</button>\n";
			}
			else
			{
				echo $row['displayname'];
				if ($i < count($names)-1) echo ",";
			}
			if ($i < count ($names)-1) echo ' ';
		}									
	}
	
											//Display names of guests in a list
											//If you're administrator, you also get a button
											// to check the member in or out
	function listGuests ($names, $date, $currentdateid, $administrator, $myId)
	{
		//Print each element of a list of names.  Put commas after
		// all but last entry.
		for ($i = 0; $i < count($names); ++$i)
		{
			$row = $names[$i];
			
				//Make these names to be clickable to check in/out if the current user is administrator or the inviter
			if (($administrator || $row['inviterId'] == $myId) && $date>=strtotime("today")) //for dates today or later...
			{			
				$name = addslashes($row['name']);
				
				$question = "Really drop guest ".$name."?";
				$action_url="window.location.href='deleteguesthandler.php?name=".
						$name."&dateid=".$currentdateid."'";

				echo "<button class='linkbutton' " ;
				echo 'onclick="if (confirm (\''.$question.'\'))'. $action_url.'"';
				echo ">".$row['name'];
				if ($i < count ($names)-1) echo ',';
				echo "</button>";
			}
			else
				echo $row['name'];
							
			if ($i < count($names)-1)
				echo ", \n";
		}									
	}

											//display a button to check this user in or out
	function showButton ($myid, $currentdateid, $uid_checkout_array)
	{									
		// set button action as
									// give checkuser.php 
									// user id, date id
		$action_url="window.location.href='checkouthandler.php?id=".$myid."&dateid=".$currentdateid."'";

		echo "<input type='button' onclick=$action_url id = $currentdateid value ='notset' >\n";								
									
		//--i use js to control the text on button --
		echo '<script type="text/javascript">'."\n";
		// set the default("notset") to co/ci 
		// denpend on if i can find user id in database
		echo 'if ($("#"+'.$currentdateid.').val()== "notset")';
		echo "\n{\n";

		if (in_array($myid,$uid_checkout_array)) $value = "'Check In'"; else $value = "'Check Out'";

		echo "document.getElementById($currentdateid).value=$value\n";
									
		// get update of dateid and ci/co from adddeletedateshandler.php 
		// and reset the text on button											
		if (isset($_GET['status']) && isset($_GET['dateid']))
		{
			if ($_GET['status']=="ci") $value = "'Check In'"; else $value = "'Check Out'";
			$id = $_GET['dateid'];
			echo "document.getElementById($id).value=$value\n";
		}

		unset($_GET['dateid']);
		echo "}\n</script>\n";
	}	

		//We shouldn't be here unless a) we got here thru index.php, which sets $myid and b) index.php
		// found we were logged on and set $myid
	if (!isset ($myid) || $myid == '') 
		crashWarning ('Should not be here without being logged in, and loading as index.php, not calendar.php.  If need be, '); 
	
	if ($isUserAdmin)
		$iHaveGuests = instancesFound ($conn, "SELECT name FROM guests");
	else
		$iHaveGuests = instancesFound ($conn, "SELECT name FROM guests WHERE inviterId = $myid");

				//Set to true if this user invited any guests.  Then a message will be posted on how to remove guests.
	if ($iHaveGuests) 
		echo '<p><center>To remove a guest you invited, click the name.  Administrators can remove all guests.</center></p>';
?>

<table class="calendar">
	<tr>
		<td >
			<fieldset > <!-- "style="min-width: 600px;"> -->
				<legend>Calendar</legend>
					<table class="calender">
						<tr >
							<td class="toprow">Dates</td>
							<td class="toprow">Number<br>checked<br>out</td> 
							<?php if (! $isUserAdmin && ! LIST_MEMBERS_COMING) echo '<td class="toprow"></td>'; /* Make column for the button if we aren't listing attendees */?>
							<td class="toprow">Who's coming?</td>
						<!--date-->
						</tr>
						<?php
						//get dates from database
						$sql = "SELECT * from dates ORDER BY date";	
						($result = mysqli_query($conn, $sql)) or sqlCrash ($conn);
						while($daterow = mysqli_fetch_array($result)) 
						{
							$thisDateIsCanceled = $daterow ['canceled'];
						?>
						<tr>
							<td class="date">
							<?php
								// print dates from database
								$currentdateid = $daterow['id']; 
								echo date('D M d', $daterow['date']);

								//is the current user checked out?
								$sql_uid = "SELECT userId from checkouts where dateId='$currentdateid'";
								($result_uid = mysqli_query($conn,$sql_uid)) or sqlCrash ($conn);
								
								$uid_checkout_array = array();	//When are my checkouts?
								while($row_uid = mysqli_fetch_array($result_uid)) 
								{
									array_push($uid_checkout_array, $row_uid['userId']);
								}
								echo '<p>';
								
								if(($isUserAdmin || LIST_MEMBERS_COMING) && ! $thisDateIsCanceled &&
									$daterow['date']>=strtotime("today")) //for dates today or later... print ckout button
								{
									showButton ($myid, $currentdateid, $uid_checkout_array);					
								}
								
							?>
							</td>
							<td class="con">
							<?php
								//print how many user checked out this day
								if (! $thisDateIsCanceled) echo $uid_count=count($uid_checkout_array);
							?>
							</td>
							<?php 
								if (! LIST_MEMBERS_COMING && ! $thisDateIsCanceled &&! $isUserAdmin) 	//If we aren't listing members coming, put a button in this column (else we'll put it elsewhere)
								{
									echo "<!--button-->\n<td class='but'>"; /* Make column for the button if we aren't listing attendees */
									//echo '<!--button-->\n<td class="but">'; /* Make column for the button if we aren't listing attendees */

									if($daterow['date']>=strtotime("today")) //for dates today or later...
									{
										showButton ($myid, $currentdateid, $uid_checkout_array);
									}
								
									echo '</td>';
								}
							?>
							<!--name list-->
							<td class="name" >
								<?php
									if ($thisDateIsCanceled)
									{
										echo 'Canceled.  ';
										if ($isUserAdmin)			
										{
											$question = "\"Really restore meeting on ".date ("F d", $daterow['date'])."?\"";
											$action_url="window.location.href=\"meetinghandler.php?action=restore&dateid=".$currentdateid."\"";
							
											echo "<button class='linkbutton' onclick='if (confirm ($question)) $action_url'>(Restore meeting)</button>";

											$question = "\"Permanently erase meeting on ".date ("F d", $daterow['date'])."?\"";
											$action_url="window.location.href=\"meetinghandler.php?action=erase&dateid=".$currentdateid."\"";
							
											echo "<button class='linkbutton' onclick='if (confirm ($question)) $action_url'>(Permanently erase meeting)</button>";
		
										}
									}
									else
									{
										getAttendees ($conn, $daterow['date'], $uid_checkout_array, $stillComing, $notComing); //Who's coming?
										getGuests    ($conn, $currentdateid, $guests);
										listNames ($stillComing,"out", $daterow['date'], $currentdateid, $isUserAdmin);		//List them
										 	
										if (count($guests) > 0)
										{
											if (count ($guests) == 1) echo '<br>Guest: ';
											else echo '<br>Guests: ';
											listGuests ($guests, $daterow['date'],$currentdateid, $isUserAdmin, $myid);
										} 	
									 	if (($isUserAdmin|| LIST_MEMBERS_COMING) && count($notComing) > 0)	
									 								 //Also print who isn't (if any, and if that's desired -- admins have to
									 	{
									 		echo '<hr class="line" width="100%" size="2"  align=center>';
									 		echo 'NOT coming:  '; listNames ($notComing, "in", $daterow['date'], $currentdateid, $isUserAdmin); 
									 	}
									 }
								?>
							</td>
						</tr>
						<?php
						}
					?>
				</table>
			</fieldset>
		</td>
	</tr>
</table>