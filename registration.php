<?php /*
	--------------------------------------------
				Registration.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
			  Edits 2019-11 by Brooklyn Welsh
	--------------------------------------------
	This is the registration page for creating new users. 

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
require_once 'utils/queryfunctions.php'; //functions to do queries securely using prepared statements
require_once "utils/dbconnection.php"; 
require_once "utils/warnings.php"; 
require_once 'utils/globals.php'; //for SITE_NAME
require_once 'utils/cookiefunctions.php';
require_once 'utils/security.php';

verifyAdmin ($conn, $id, $name, $isAdmin); //if you're not admin, you can't see this page.

function add_user($conn, $displayname, $username, $password, $existinggroupid, $isAdmin)
{
	// First add them to the users table
	$date = strtotime ("today");
	if(! $add_stmt = $conn->prepare("INSERT INTO users (displayname,username,password,admin) VALUES (?,?,?,0)")) 	die("Error preparing SQL add_user statement");
	if( ! $add_stmt->bind_param('sss', $displayname,$username,$password)) die("Error binding parameters to add_user statement");
	if( ! $add_stmt->execute()) die("Error executing SQL statement");
	$add_stmt->close();

	// Get ID from last statement to use for Insert into roster
	if(! $select_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?"))	die("Error preparing SQL statment.");
	if(! $select_stmt->bind_param('s', $username))	die("Error binding parameters.");
	if(! $select_stmt->execute())	die("Error executing SQL statment");
	
	$result = $select_stmt->get_result();
	if(($result->num_rows) > 1) die("Error: duplicate users in database.");
	$user_info = $result->fetch_assoc();
	$user_id = $user_info['id'];
	$select_stmt->free_result();
	$select_stmt->close();

	// Now add them to the appropriate group
	if(! $group_add_stmt = $conn->prepare("INSERT INTO group_roster(user_id,group_id,is_admin) VALUES (?,?,?)")) die("Error preparing SQL statement");
	if(! $group_add_stmt->bind_param('iii', $user_id, $existinggroupid, $isAdmin))	die("Error binding parameters.");
	if(! $group_add_stmt->execute())	die("Error executing SQL statement");
}

function processRegistration ($conn)
{ 
	if (isset($_POST['submit']))
	{	
			//Extract all the info we needed from the $_POST array
		$username 	= sanitizeInput ($conn, strtolower($_POST['username'])); //Let's not let user names be case sensitive
		$password 	= $_POST['password'];
		$repw 		= $_POST['repw'];		//don't sanitize pswds -- they're abt to be encrypted anyway
		$displayname= sanitizeInput ($conn, $_POST['displayname']); 
		if( isset($_POST['existinggroupname'])) $existinggroupname = $_POST['existinggroupname'];
		else return 'emptyfield';
		if( isset($_POST['isAdmin'])) $isAdmin = 1;
		else $isAdmin = 0;
		
			//Does the user name fit requirements?
		if(!preg_match('/^[\w\x80-\xff]{3,15}$/', $username))			return 'badusername';			
			//Do the passwords match?
		if($password!=$repw) 											return 'passwordsdontmatch';
			//Are we actually updating something?
		if ($username== "" || $password==""	|| $repw=="" || $displayname == "" || $existinggroupname == "")
																		return 'emptyfield';
			//Does this username already exist?
		$query = "select id from users where lower(username) ='$username' limit 1";
		
		if (instancesFound ($conn, $query)>0)  							return 'userexists';	 		

			//Does this displayname already exist?
		$query = "select id from users where displayname ='$displayname' limit 1";
		if (instancesFound ($conn, $query)>0) 							return 'displaynameexists';
	
			//OK, we're good; let's add this new user
		$hash = password_hash($password, PASSWORD_DEFAULT);	
		
		add_user($conn, $displayname, $username, $hash, $existinggroupname, $isAdmin);
		return 'regsuc'; 												//It worked!  Go to index and report success
	}
	else if (isset($_POST['cancel']))									return 'cancel';
	
	return ''; //They didn't submit, but instead hit cancel.  Let's go back to index
}

$warningIfAny = processRegistration ($conn);	
//if ($warningIfAny == 'regsuc') header('Location: index.php?result=regsuc');
if ($warningIfAny == 'cancel') header ('Location:index.php'); //user hit cancel -- just go back to index
?>

<html>
<head>
<meta content="en-us" http-equiv="Content-Language" >
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" >
<link rel="stylesheet" type="text/css" href="style.css" >
<title><?php echo SITE_NAME; ?></title> </head>

<body>
	<?php include ('header.php'); ?>
	<?php printWarning ($warningIfAny);	?> 
	<?php include ('registrationform.php'); ?>
	<?php include ('footer.php'); ?>
</body>

</html>
