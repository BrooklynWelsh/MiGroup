<?php /* 
	--------------------------------------------
				 datefunctions.php
					Yu Fu
				  2015-10-12
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	Functions related to days and weekdays.
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

// this is function to get week date of time
function getWeek($timestamp)
{
    $timestamp=date('w',$timestamp);
    $str='';
    switch($timestamp)
    {
        case '0': $str.='Sun'; break;
        case '1': $str.='Mon'; break;
        case '2': $str.='Tue'; break;
        case '3': $str.='Wed'; break;
        case '4': $str.='Thr'; break;
        case '5': $str.='Fri'; break;
        case '6': $str.='Sat'; break;
    }
    return $str;
}


function weekday($weeknumber)
{
    $str='';
    switch($weeknumber)
    {
        case '0': $str='Sunday'; break;
        case '1': $str='Monday'; break;
        case '2': $str='Tuesday'; break;
        case '3': $str='Wednesday'; break;
        case '4': $str='Thursday'; break;
        case '5': $str='Friday'; break;
        case '6': $str='Saturday'; break;
    }
    return $str;
}

function dropDownBoxOfDates ($conn)
{
	echo '		<select name="date">';
	
	//get all dates
	$sql = "SELECT * from dates WHERE canceled = 0 ORDER BY date";
	$getDate = mysqli_query($conn,$sql);
	$today=strtotime("today-1day");
	while($daterow = mysqli_fetch_array($getDate)) 
	{
		if($daterow['date'] > $today)
		{
	
			echo "<option value=".$daterow['id'].">";
			echo 	date('D M d', $daterow['date']);
			echo '</option>';
		}
	}
	mysqli_free_result($getDate);
	
	echo '	</select>';	
}
?>