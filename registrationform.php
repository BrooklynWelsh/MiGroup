<?php 
	/*--------------------------------------------
				  registrationform.php
					Yu Fu
				  2015-10-3
				  Edits 2016-05 by W Briggs
	--------------------------------------------
	This is the registration form.  

	--

	Mi-Group:  a web site for keeping track of checkouts for a small-group organization.

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
	   <http://www.gnu.org/licenses/>.*/
	   
verifyAdmin ($conn, $id, $name, $isAdmin); //if you aren't admin, you can't see this page	   
?>

<script language=JavaScript>
function InputCheck(RegForm)
{
	//Note that you can test values simply by sending them to alert...
  if (RegForm.username.value == "" ) 
  {
    alert("Username must not be empty.");    RegForm.username.focus(); return (false);
  }
  else if (RegForm.password.value == "")  
  {
    alert("Password must not be empty.");    RegForm.password.focus(); return (false);
  }
  else if (RegForm.displayname.value == "") 
  {
    alert("Display name must not be empty.");RegForm.repass.focus();   return (false);
  }
  else if (RegForm.repw.value != RegForm.password.value) 
  {
    alert("Passwords do not match.");	    RegForm.repass.focus();    return (false);
  }
  else return true;  
}

window.onload = function() //This makes username automatically get copied into displayname
{
    var usernameField    = document.getElementById("username"),
        displaynameField = document.getElementById("displayname");
    usernameField.addEventListener('input', function() { displaynameField .value = usernameField.value; });
};
</script>

<div class="biggerBox">
<fieldset>
	<legend>User Registration</legend>
	<form name="RegForm" method="post" action="registration.php" onSubmit="return InputCheck(this)">
	<table>  
    <tr>  
		<td style="width:20%"><label for="username" class="label">Username:  </label></td>
		<td style="width:30%">	
			<?php
					//Have the text field for user name.  If the user just tried submitting and it failed, prompt with last attempted username
				echo '<input id="username" name="username" type="text" class="input" ';
				if (isset ($_POST['username']) && $warningIfAny != 'regsuc') //If we tried this before and FAILED, keep the old value
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
				if (isset ($_POST['displayname'])&& $warningIfAny != 'regsuc')
					echo 'value="'.$_POST['displayname'].'"';
					
				echo ">\n";
			?>			
		</td>
		<td><span>(Cannot be empty.  Spaces are allowed, as in Bill W, or John Albert)</span></td>
	</tr> 
		
	<tr>  
		<td><label for="existinggroupname" class="label" >Add to group: </label></td>
		<td>
			<?php 	require('utils\groupfunctions.php');
					dropDownBoxOfGroupsUserIsMemberOf($conn); // PHP function found in utils\groupfunctions.php ?>			
		</td>
		<td><span>Choose which group to add this user to. (Must not be left blank) </span></td>
	</tr> 
		
	<tr>  
		<td><label for="isAdmin" class="label" >Make user Admin of group?  </label></td>
		<td>
			<?php
					//Have the text field for display name.  
					//If the user just tried submitting and it failed, prompt with last attempted value
				echo '<input id="isAdmin" name="isAdmin" type="checkbox" value="isAdmin" ';
				if (isset ($_POST['isAdmin'])&& $warningIfAny != 'regsuc')
					echo 'value="'.$_POST['isAdmin'].'"';
					
				echo ">\n";
			?>				
		</td>
		<td><span>This will give user administrator rights over the group you are adding them to.</span></td>
	</tr> 
		
	<tr>  
		<td><label for="password" class="label">Password:  </label></td>
		<td>
			<?php
					//Have the text field for password.  If the user just tried submitting and it failed, prompt with last attempted value
				echo '<input id="password" name="password" type="password" class="input" ';
				if (isset ($_POST['password'])&& $warningIfAny != 'regsuc')
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
				if (isset ($_POST['repw'])&& $warningIfAny != 'regsuc')
					echo 'value="'.$_POST['repw'].'"';
					
				echo ">\n";
			?>			
		</td>
		<td><span>(Type in the same password again)</span></td>
	</tr> 
	<tr>  
		<td><input type="submit" name="submit" value="Submit" class="left"></td>
		<td><input type="button" onclick="window.location.href='index.php';" value="Finished" class="left" ></td>
	</tr> 
	</table>
	</form>
</fieldset>
</div>