<?php

    /*
	--------------------------------------------
				newinstall.php
				Brooklyn Welsh
				2019-11-05
			  
	--------------------------------------------
	The page you'll see on first use when no accounts have been set up.
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

    require_once 'utils/globals.php'; //for SITE_NAME
    require_once "utils/dbconnection.php"; 
    require_once 'utils/security.php';
    require_once "utils/warnings.php"; 
    require_once 'utils/cookiefunctions.php';	
    require_once 'utils/autoupdate.php';

    
    $users_stmt = $conn->prepare("SELECT id FROM users LIMIT 1");	// Check if table is empty
    if(! $users_stmt) die("Error");
    if(! $users_stmt->execute()) die("Error");
    if(! $users_stmt->store_result()) die("Error");

    // If there's already users in the database, they shouldn't be using this page!
    if($users_stmt->num_rows > 0)
{
	header("Location:index.php");
	exit();
}
?>

<html>
<head>
<meta content="en-us" http-equiv="Content-Language" >
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" >
<link rel="stylesheet" type="text/css" href="style.css" >
<title><?php echo SITE_NAME; ?></title> </head>
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<h2>Welcome to MiGroup!</h2>
<p>It looks like this is a new installation. You'll need to create an administrator account</p>
<p>This account will be the <strong>SUPER ADMIN</strong> for your installation.
	This account will have access and admin rights to <strong>ALL</strong> groups. Keep this information safe and do not give it to anyone else.
</p>
<div class="biggerBox">
<fieldset>
	<legend>Admin Account Registration</legend>
	<form name="RegForm" method="post" action="newinstallregistration.php" onSubmit="return InputCheck(this)">
	<table cellpadding="5">  
    <tr>  
		<td style="width:30%"><label for="username" class="label">Username:  </label></td>
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
		<td><label for="groupname" class="label" >Group name</label></td>
		<td>
			<?php
					//Have the text field for display name.  
					//If the user just tried submitting and it failed, prompt with last attempted value
				echo '<input id="groupname" name="groupname" type="text" class="input" ';
				if (isset ($_POST['groupname'])&& $warningIfAny != 'regsuc')
					echo 'value="'.$_POST['groupname'].'"';
					
				echo ">\n";
			?>			
		</td>
		<td><span>(Cannot be empty.  This is the name of the group you'll be administrating.)</span></td>
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