<?php 
	/*
	--------------------------------------------
				 manageadministratorsform.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	This form lists the users, checked if they are administrator.  You can change the check
	marks and thus change who's admin.
	
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
	
	//This crashes if loaded without knowing $conn...so this page cannot be loaded on its own.  Good.
	// select all user without superadmin user
	
	// Get the user info of all users whos ID is in the group_roster table for the current group (as specified by user's cookie)
	$currentGroupId = $_COOKIE['currentGroupId'];
	$get_uinfo_query = "SELECT displayname,admin,id FROM users WHERE id IN
						(SELECT user_id FROM group_roster WHERE group_id = $currentGroupId)";
	$get_uinfo = mysqli_query($conn, $get_uinfo_query) or sqlCrash($conn);
	
	
?>

<script language=JavaScript>
	$('input[type="checkbox"]').on('change', function() 
	{
		$('input[name="' + this.name + '"]').not(this).prop('checked', false);
	});
</script>

	<form name="update_admin" method="post"  action="manageadminstatushandler.php">
	
		<label for="users">Check user(s) to give admin rights to:</label>
		
		<?php
		while($userrow = mysqli_fetch_array($get_uinfo)) 
		{
		?>		
		<table>
			<tr>
				<td>
					<?php
						echo '<input type="checkbox" name="adminUser[]" value=';
						echo $userrow['id'];
						echo ' ';
						if($userrow['admin'])
							echo "checked";
						echo ">\n";
					?>
					<label>
						<?php
							echo $userrow['displayname']
						?>
					</label>
				</td>
			</tr>
		<?php
		}
		mysqli_free_result ($get_uinfo);
		?>
		</table>
		<input type="submit" name="submit" id= "submit" value="Submit">
	</form>