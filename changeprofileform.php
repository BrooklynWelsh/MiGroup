<?php /*
	--------------------------------------------
			     changeprofileform.php
					Yu Fu
				  2015-11-18
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	A form to change your user profile. 

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

$query = "SELECT username, hidden FROM users WHERE id = $user";  //also need display name
if (! ($stmt = $conn->prepare($query))) sqlCrash ($conn);
if (! $stmt->execute()) 				sqlCrash ($conn);
if (! $stmt->bind_result($username, $hidden)) sqlCrash ($conn);
if (! $stmt->store_result ()) 			sqlCrash ($conn);
if ($stmt->num_rows != 1)				crashWarning ("Database error: user should have exactly one database entry, but has ".$stmt->num_rows.".");

$stmt->fetch ();

$stmt->free_result (); $stmt->close();

?>

<script language=JavaScript>
function InputCheck(changepw)
{
/*  if (changepw.displayname.value == "" && changepw.username.value=="" && changepw.password.value=="")
  {
	    alert("Must enter new user id, display name, or password to make changes.");
	    changepw.username.focus();
	    return (false);
  }*/
  if (changepw.password.value != "" && changepw.oldpw.value == "")
  {
    alert("Enter old password to make password changes.");
    changepw.username.focus();
    return (false);
  }
  if (changepw.repass.value != changepw.password.value)
  {
    alert("Passwords do not match.");
    changepw.repass.focus();
    return (false);
  }
  
  return true;
}
</script>
<fieldset class="profileBox">
<legend>User Profile Change</legend>
<form name="changepw" method="post" action="changeprofile.php" onSubmit="return InputCheck(this)">
<table align="left">
	<tr><p></p>
	</tr>
	<tr>
		<td style="width:20%">	<label for="username" class="label">New user id:  </label>		</td>
		<td style="width:30%">  	
			<?php
					//Have the text field for user name.  
					//If the user just tried submitting and it failed, prompt with last attempted username
				echo '<input id="username" name="username" type="text" class="input" ';
				echo "value=\"$username\">\n";					
			?>			
		</td>	
		<td style="width:40%">	(The name you log in with.)								</td>
	</tr>
	<tr>
		<td style="width:20%">	<label for="displayname" class="label">New display name:  </label></td>
		<td style="width:30%">  
			<?php
					//Have the text field for display name.  
					//If the user just tried submitting and it failed, prompt with last attempted value
				echo '<input id="displayname" name="displayname" type="text" class="input" ';
				echo "value=\"$displayname\">\n";					
			?>			
		</td>
		<td style="width:40%">	(The name for you after login.)							</td>
	</tr>
	<tr>  
		<td style="width:20%">	<label for="oldpw" class="label">Old password:  </label>		</td>
		<td style="width:30%">  <input id="oldpw" name="oldpw" type="password" class="input">	</td>
		<td style="width:40%">	(Required for password changes.)								</td>
	</tr> 
	<tr> 
	</tr> 
	<tr> 
		<td><label for="password" class="label">New password:  </label>			    </td>
		<td><input id="password" name="password" type="password" class="input">	</td>
		<td> If you change password, you'll be prompted to log in again.		</td>
	</tr> 
	
	<tr>  
		<td><label for="repw" class="label">New password again:</label>				</td>
		<td><input id="repw" name="repw" type="password" class="input">			</td>
		<td>					</td>
	</tr> 
<?php
	if ($isAdmin)
	{
		echo '<tr><td><label for="hidden" class="label">Hidden user?</label></td>';
		echo '    <td><input type="checkbox" name="hidden" value="hidden" ';
		if ($hidden) echo 'checked="checked"';
		echo '></td>';
		echo '    <td>Check this to not show up on the calendar.  Useful for an administrator who is not a participant.</td></tr>';
	}
?>
	<tr>  
		<td><input type="submit" name="submit" value="Submit" class="left">		</td>
		<td><input type="button" onclick="window.location.href='index.php';" value="Cancel" class="left" >	</td>
	</tr> 
</table>
</form>
</fieldset>

