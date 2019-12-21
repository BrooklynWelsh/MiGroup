<?php
/*
		--------------------------------------------
				 datefunctions.php
					Brooklyn Welsh
					2019-11-15,
					Yu Fu,
					W Briggs
	--------------------------------------------
	Functions related to groups.
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

function dropDownBoxOfGroups($conn)
{
	echo '<select id=existinggroupname name="existinggroupname" value="existinggroupname">';
	echo '<option hidden disabled selected value> -- select an option -- </option>';
	// Get all groups within the database
	$sql = "SELECT * FROM groups";
	$getGroups = mysqli_query($conn, $sql);
	
	while($groupname = mysqli_fetch_array($getGroups))
	{
		echo "<option value=" . $groupname['group_id'] . ">";
		echo $groupname['group_name'];
		echo "</option>";
	}
	mysqli_free_result($getGroups);
	
	echo "</select>";
}

function dropDownBoxOfGroupsUserIsMemberOf($conn)
{
	// Same as above, except it only displays groups the current user is member of
	echo '<select id=existinggroupname name="existinggroupname" value="existinggroupname">';
	echo '<option hidden disabled selected value> -- select an option -- </option>';

	// Get group_ids that the user is a member of
	$stuff = explode ('|', $_COOKIE['logintoken']);	//split login token into user id and encrypted part
	$currentuid = $stuff[0];
	$sql = "SELECT group_id FROM group_roster WHERE user_id = $currentuid";
	$getGroupIds = mysqli_query($conn, $sql);
	
	// Now fetch their name from groups table
	while($group_id = mysqli_fetch_array($getGroupIds))
	{
		$current_group_id = $group_id['group_id'];
		$sql = "SELECT * FROM groups WHERE group_id = $current_group_id";
		$getGroupInfo = mysqli_query($conn, $sql);
		while($groupInfo = mysqli_fetch_array($getGroupInfo))
		{
			echo "<option value=" . $groupInfo['group_id'] . ">";
			echo $groupInfo['group_name'];
			echo "</option>";
		}	mysqli_free_result($getGroupInfo);
	}	mysqli_free_result($getGroupIds);
	echo "</select>";
}

function getUsersOfCurrentGroup($conn)
{
	
}

function switchGroups ($conn)
	{
		if($_POST['existinggroupname'] != '') 
		{
			$existinggroupname = $_POST['existinggroupname'];
			setcookie('currentGroupId', $existinggroupname);
		} else header('Location:index.php');
			
		// Get the name of the group using the group id
		$sql = "SELECT group_name FROM groups WHERE group_id = $existinggroupname";
				$getGroupInfo = mysqli_query($conn, $sql);
				$groupInfo = mysqli_fetch_array($getGroupInfo);
				
				$group_name = $groupInfo['group_name'];

		// Now set the name cookie
		setcookie('currentGroupName', $group_name);
		//return to home page
		header('Location:index.php'); 
	}

?>
	
	
