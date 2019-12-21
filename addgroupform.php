<?php /*
	--------------------------------------------
			     changeprofileform.php
					Yu Fu
                    Will Briggs
                    Brooklyn Welsh
                    2019-11-17
	--------------------------------------------
	A form to add another group to the database.

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

require_once "utils/warnings.php";
require_once "utils/cookiefunctions.php";
require_once "header.php";
	//Get info on user, for prompts below
verifyLogin ($conn, $user, $displayname, $isAdmin);
getUserInfo ($conn, $myid, $name, $isUserAdmin, $currentGroupName);


$query = "SELECT username, hidden FROM users WHERE id = $user";  //also need display name
if (! ($stmt = $conn->prepare($query))) sqlCrash ($conn);
if (! $stmt->execute()) 				sqlCrash ($conn);
if (! $stmt->bind_result($username, $hidden)) sqlCrash ($conn);
if (! $stmt->store_result ()) 			sqlCrash ($conn);
if ($stmt->num_rows != 1)				crashWarning ("Database error: user should have exactly one database entry, but has ".$stmt->num_rows.".");

$stmt->fetch ();

$stmt->free_result (); $stmt->close();

?>

<script type="text/javascript">
// Function to reveal the user info fields if the user checks the radio button
	function toggleFields()
	{
		if(document.getElementById('createNewUser').checked)
		{
			document.getElementById('ifCreatingUser').style.visibility = "visible";
			document.getElementById('ifCreatingUser').style.display = "";
			//Setting display to empty string allows table elements to use their default display types. Should work in all browsers.
		}
		else
		{
			document.getElementById('ifCreatingUser').style.visibility = "hidden";		
			document.getElementById('ifCreatingUser').style.display = "none";		
		}
	}
</script>

<fieldset class="profileBox">
<legend>Add a New Group</legend>
<form name="addgroup" method="post" action="addgroup.php" onSubmit="return InputCheck(this)">
<table align="left" cellpadding="5">
	<tr><p></p>
	</tr>
	<tr>
		<td style="width:30%">	<label for="groupname" class="label">New group name:  </label>		</td>
		<td style="width:30%">  	
			<?php
					//Have the text field for user name.  
					//If the user just tried submitting and it failed, prompt with last attempted username
				echo '<input id="groupname" name="groupname" type="text" class="input" ';
				echo "value=\"$username\">\n";					
			?>			
		</td>	
		<td style="width:40%">	Name for the new group. Required.								</td>
	</tr>
	<tr>  
		<td><label for="createNewUser" class="label" >Create a new user to administrate this group?</label></td>
		<td>
			<?php
					//Have the text field for display name.  
					//If the user just tried submitting and it failed, prompt with last attempted value
				echo '<input id="createNewUser" name="createNewUser" type="checkbox" value="createNewUser" onclick="javascript:toggleFields()"';
					
				echo ">\n";
			?>				
		</td>
		<td><span>If you want a different account to be the admin of this group, you can create it now.</span></td>
	</tr> 
	<tbody id="ifCreatingUser" style="visibility:hidden; display:none">
		<tr>  
			<td style="width:20%"><label for="username" class="label">Username:  </label></td>
			<td style="width:30%">	
				<?php
						//Have the text field for user name.  If the user just tried submitting and it failed, prompt with last attempted username
					echo '<input id="username" name="username" type="text" class="input" ';
					if (isset ($_POST['username'])) //If we tried this before and FAILED, keep the old value
						echo 'value="'.$_POST['username'].'"';
						
					echo ">\n";
				?>					
			</td>
			<td style="width:40%"><span>(Length: 3-15; letters, digits, and _ allowed)</span></td>
		</tr> 
		
		<tr>  
			<td><label for="displayname" class="label" >Display name</label></td>
			<td>
				<?php
						//Have the text field for display name.  
						//If the user just tried submitting and it failed, prompt with last attempted value
					echo '<input id="displayname" name="displayname" type="text" class="input" ';
					if (isset ($_POST['displayname']))
						echo 'value="'.$_POST['displayname'].'"';
						
					echo ">\n";
				?>			
			</td>
			<td><span>(Cannot be empty.  Spaces are allowed, as in Bill W, or John Albert)</span></td>
		</tr> 
			
		<tr>  
			<td><label for="password" class="label">Password:  </label></td>
			<td>
				<?php
						//Have the text field for password.  If the user just tried submitting and it failed, prompt with last attempted value
					echo '<input id="password" name="password" type="password" class="input" ';
					if (isset ($_POST['password']))
						echo 'value="'.$_POST['password'].'"';
						
					echo ">\n";
				?>			
			</td>
			<td>
				<span></span>
			</td>
		</tr> 

		<tr>  
			<td><label for="repw" class="label">...again:</label></td>
			<td>
				<?php
						//Have the text field for repeated psw.  If the user just tried submitting and it failed, prompt with last attempted value
					echo '<input id="repw" name="repw" type="password" class="input" ';
					if (isset ($_POST['repw']))
						echo 'value="'.$_POST['repw'].'"';
						
					echo ">\n";
				?>			
			</td>
			<td><span>(Type in the same password again)</span></td>
		</tr> 
	</tbody>
	<tr> 
		<td><input type="submit" name="submit" value="Submit" class="left">		</td>
		<td><input type="button" onclick="window.location.href='index.php';" value="Cancel" class="left" >	</td>
		<td><input type="button" onclick="window.location.href='logout.php';" value="Log out" class="right"></td>

	</tr> 
</table>
</form>
</fieldset>

