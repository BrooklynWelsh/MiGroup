<?php
	/*
	--------------------------------------------
				   index.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
			  Edits 2019-11 by Brooklyn Welsh
	--------------------------------------------
	The main page.	
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

		//If you decide to change the encryption of the passwords (see utils/globals.php), then...
		// ...to get an encrypted version of the original admin password, uncomment the next 2 lines
		// It would be better not to do that and run on a publicly viewable page.  
		
//echo myEncrypt ('admin');
//die ();

		//Then in MySQL, after selecting database (e.g. USE 4qskwml;)
		// say UPDATE users SET password='<whatever you got>' WHERE id=1;

// Create an admin with a php password_hash / password_verify password. Delete after use
// Maybe check to see if there are any users, if not go to a different page

$users_stmt = $conn->prepare("SELECT id FROM users LIMIT 1");	// Check if table is empty
if(! $users_stmt) die("Error");
if(! $users_stmt->execute()) die("Error");
if(! $users_stmt->store_result()) die("Error");

// If there are no registered users, take the user to a page to create an admin account
if($users_stmt->num_rows <= 0)
{
	header("Location:newinstall.php");
	exit();
}

if (isset($_POST['login'])) login ($conn); // if this is a login attempt, process it    

getUserInfo ($conn, $myid, $name, $isUserAdmin, $currentGroupName);

$sql = "SELECT admin FROM users WHERE displayname = '$name'";
$getSuperAdmin = mysqli_query($conn, $sql) or sqlCrash($conn);
$isSuperAdmin = mysqli_fetch_array($getSuperAdmin);
if($isSuperAdmin[0] == 1)
{
	header('Location:addgroup.php');
}
?>	

<html>
<head>
<meta content="en-us" http-equiv="Content-Language" >
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" >
<link rel="stylesheet" type="text/css" href="style.css">
<script language="javascript" type="text/javascript" src="jquery/jquery.js"></script>
<title><?php 	if($currentGroupName != ""){
					echo ucwords($currentGroupName); 
}
				else echo "Welcome to MiGroup";?></title>
</head>
<body>
	<?php include ('header.php');?>
	
	<div>
	<table style="width: 100%">
		<tr>
		<?php	
		if ($myid != '' && isset($_COOKIE['currentGroupId']))				//if we are logged in...
		{
			autoupdate ($conn);			//I'll do this every time we look at the main page.  So that's here.	
										// Erases old meetings and adds new ones in future, as time passes
			getUserInfo ($conn, $myId, $userName, $isUserAdmin, $currentGroupName);
		?>
		
			<td style="width: 60%">
				<?php printMessageFromURLArgument ();  /* if there's a message print it */ ?>
				<?php include "calendar.php" ?>
			</td>
			
			<td style="width: 40%">
				<?php include "usercenter.php"; ?>
			</td>
	
		<?php 
		}
		else
		//if not logged in
		{
		?>
			<td style="width: 60%">	
				<?php printMessageFromURLArgument ();  /* if there's a message print it */ ?>
			</td>
			
			
			<td style="width: 40%">
				<?php include "loginform.php";	?>
			</td>
		<?php	
		}
		?>
		</tr>
	</table>
	</div>
	
	
	<?php include ('footer.php');?>
</body>

</html>
