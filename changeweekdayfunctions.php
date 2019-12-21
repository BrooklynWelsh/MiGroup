<?php 

/*
	--------------------------------------------
			 changeweekdayfunctions.php
					Yu Fu
				  2015-11-12
			 Edits 2016-05 by W Briggs
	--------------------------------------------
	This provides a function to process requests to change weekday of meetings.
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

include 	 'utils/datefunctions.php';
require_once 'utils/autoupdate.php';
require_once 'utils/queryfunctions.php';
	
function changeWeekdayOfMeeting ($conn)			//Given form input, change weekday of meeting
{
	if (! (isset ($_POST['date']) && isset ($_POST['weekday']))) //We didn't process a form (right), so nothing to report
		return '';
		
		//So at this point we know we did get inputs from a form:  date and weekday
		
		// get from $_POST:  When does the new weekday take effect?
	$new_date=strtotime($_POST['date']);
		//Date earlier than today?  No way!
	if ($new_date < strtotime("today"))				return 'datebeforetoday';
		//...and when is it?
	$new_day=		    $_POST['weekday'];
	
		//Change the weekday entry in weekday table
	$query = "UPDATE weekday SET weekday=$new_day";
	executeQuery ($conn, $query);
	
		//delete all checkouts for meetings about to be canceled
	$query = "DELETE checkouts FROM checkouts INNER JOIN dates WHERE checkouts.dateId = dates.id AND dates.date >= $new_date";
	executeQuery ($conn, $query);
	
		//delete all guest entries for meetings about to be canceled
	$query = "DELETE guests    FROM guests    INNER JOIN dates WHERE guests.dateId    = dates.id AND dates.date >= $new_date";
	executeQuery ($conn, $query);
	
		//delete all meetings after the date given
	$query = "DELETE FROM dates WHERE date >= $new_date";
	executeQuery ($conn, $query);

		//now recreate all the meetings after that date, using the new weekday
	addNewMeetings ($conn, $new_date);
	
		//clean up and leave
	mysqli_close($conn);
	return 'suc';
}

function printChangeWeekdayForm ($conn, $actionURL, $isFirstTimeEver)	//The form to change weekday, customized by whether it's
																//the first time ever.
{
echo <<<END

<script language=JavaScript>
function weekdayFormCheck(dateForm)
{
	if (dateForm.date.value == "")
	{
		alert("Please select a valid date, no earlier than today.");
		dateForm.date.focus();
		return (false);
	}
	return true;
}
</script>

<div class = "biggerBox">
<fieldset>
	<legend>Change meeting date</legend>
END;

	echo '<form name="weekdayForm" method="post" action="';
	echo $actionURL;
	echo '" onSubmit="return weekdayFormCheck(this)">';

	echo "\n<table><tr>\n";

	//printMessageFromURLArgument ($warning);	//maybe we failed?

$weekday = 3;		//Default to Wed, I suppose, in the absence of other information.
if (! $isFirstTimeEver)
{
	$get_new_date="select * from weekday";
	$date = mysqli_query($conn,$get_new_date); 
	if(mysqli_num_rows($date)==1)
	{ 
		$row = mysqli_fetch_array($date); 
		$weekday = $row ['weekday'];
	}
	
	mysqli_free_result ($date);
}

echo <<<END
	</tr> 
	<tr>
		<td>
			<label for="date">Change meeting date starting:</label>
		</td>
		<td>
END;

echo '<input id="date" name="date" type="date" class="input" value="'.date("Y-m-d").'" min="'.date("Y-m-d").'">'; 
echo "</td></tr>\n";
if (! $isFirstTimeEver)
	echo '<tr><td colspan="2" align="center">(This will erase all checkouts after this date.)</td></tr>';


echo <<<END
	<tr><td>&nbsp;</td></tr>
		<tr>
		<td>
			<label for="day">Set new default weekday:</label>
		</td>
		<td>
			<select name="weekday"> 
END;

				for ($value = 0; $value <= 6; ++$value)
					if ($value == $weekday) //$row['weekday'])
						echo '<option value="'.$value.'" selected>'.weekday($value).'</option>';
					else
						echo '<option value="'.$value.'">'.weekday($value).'</option>';

echo <<<END
			</select>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>  
		<td>
			<input type="submit" name="submit" value="Submit" class="left">
		</td>
		<td>
			<input type="button" onclick="window.location.href='index.php';" value="Cancel" class="left" >
		</td>
	</tr> 

	</table>
	</form>
</fieldset>
</div>
END;
}
?>