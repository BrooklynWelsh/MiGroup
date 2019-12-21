<?php /*
	--------------------------------------------
				deleteusersform.php
					Yu Fu
				  2015-11-14
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	Form to delete users.
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
	
	// Get the user info of all users whos ID is in the group_roster table for the current group (as specified by user's cookie)
	$currentGroupId = $_COOKIE['currentGroupId'];
	$get_uinfo_query = "SELECT displayname,admin,id FROM users WHERE id IN
						(SELECT user_id FROM group_roster WHERE group_id = $currentGroupId)";
	$get_uinfo = mysqli_query($conn, $get_uinfo_query) or sqlCrash($conn);
?>

<script language="javascript">
	function delForm(id)
	{
		alert=confirm("Do you really want to delete this user?")
		if(alert==true)
		{
			location.href="deleteuserhandler.php?id="+id;
		}
	}
</script> 

	<form name="update_admin" method="post"  action="deleteuserhandler.php">
		<table>
			<tr>
				<td>
				<label for="users">Check user(s) to delete:</label>
				</td>
			</tr>
			<?php
			while($userrow = mysqli_fetch_array($get_uinfo)) 
			{
			?>		
				
				<tr>
					<td>
						<label>
						<?php
							echo $userrow['displayname'];
						?>
						</label>
					</td>
					<td>
						<?php
							echo "<input type='button' onclick='delForm(".$userrow['id'].");' value='Delete' class='left' >";
						?>
					</td>
				</tr>
			
			<?php
			}
			mysqli_free_result ($get_uinfo);
			?>
		</table>
	</form>
