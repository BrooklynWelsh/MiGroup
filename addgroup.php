<?php /*
	--------------------------------------------
			     changeprofile.php
                    Yu Fu
                    Will Briggs
                    Brooklyn Welsh
                  2019-11-17
	--------------------------------------------
	A page to create another group.

	--

	Mi-Group:  a web site for keeping track of checkouts for a small-group organization.
	Copyright (C) 2015-2019 Yu Fu, Will Briggs, and Brooklyn Welsh of Lynchburg College.

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
require_once 'utils/dbconnection.php';
require_once 'utils/queryfunctions.php';
require_once 'changeprofilefunctions.php';
require_once 'utils/warnings.php';
require_once 'utils/globals.php'; //for SITE_NAME
require_once 'utils/cookiefunctions.php';

verifyLogin ($conn, $userId, $userName, $isAdmin);
verifyAdmin ($conn, $id, $name, $isAdmin);  // Only admins can use this page

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

function processGroupAdd($conn)
{
    if(isset($_POST['submit']))
    {
		// Check to make sure that a group with the same name is not already in the database
		if(isset($_POST['groupname']))		$group_name = $_POST['groupname'];
		else								return 'emptyField';
		
		$query = "select group_name from groups where lower(group_name) = '$group_name' limit 1";
			
		if (instancesFound ($conn, $query)>0)  							return 'groupexists';
		
        // First, create the group in the database
        $newgroupname = $_POST['groupname'];
        if(! $add_stmt = $conn->prepare("INSERT INTO groups (group_name) VALUES (?)")) 	die("Error preparing SQL add_user statement");
	    if( ! $add_stmt->bind_param('s', $newgroupname)) die("Error binding parameters to add_user statement");
	    if( ! $add_stmt->execute()) die("Error executing SQL statement");
        $add_stmt->close();
	
        // Now, get the id of the newly created group
        if(! $select_stmt = $conn->prepare("SELECT group_id FROM groups WHERE group_name = ?"))	die("Error preparing SQL statment.");
        if(! $select_stmt->bind_param('s', $newgroupname))	die("Error binding parameters.");
        if(! $select_stmt->execute())	die("Error executing SQL statment");
        
        $result = $select_stmt->get_result();
        if(($result->num_rows) > 1) die("Error: duplicate users in database.");
        $group_info = $result->fetch_assoc();
        $group_id = $group_info['group_id'];
        $select_stmt->free_result();
        $select_stmt->close();

		if(isset($_POST['createNewUser']))
		{
			// If the createNewUser box was checked in the form, we'll need to create a user with that info
			// and then add the new user as an admin to the new group.
			$username 	= sanitizeInput ($conn, strtolower($_POST['username'])); //Let's not let user names be case sensitive
			$password 	= $_POST['password'];
			$repw 		= $_POST['repw'];		//don't sanitize pswds -- they're abt to be encrypted anyway
			$displayname= sanitizeInput ($conn, $_POST['displayname']); 
			$isAdmin = 1;
			
				//Does the user name fit requirements?
			if(!preg_match('/^[\w\x80-\xff]{3,15}$/', $username))			return 'badusername';			
				//Do the passwords match?
			if($password!=$repw) 											return 'passwordsdontmatch';
				//Are we actually updating something?
			if ($username== "" || $password==""	|| $repw=="" || $displayname == "")
																			return 'emptyfield';
				//Does this username already exist?
			$query = "select id from users where lower(username) ='$username' limit 1";
			
			if (instancesFound ($conn, $query)>0)  							return 'userexists';	 		

				//Does this displayname already exist?
			$query = "select id from users where displayname ='$displayname' limit 1";
			if (instancesFound ($conn, $query)>0) 							return 'displaynameexists';
		
				//OK, we're good; let's add this new user
			$hash = password_hash($password, PASSWORD_DEFAULT);	
			
			add_user($conn, $displayname, $username, $hash, $group_id, $isAdmin);
			return 'regsuc'; 	
		}
		else
		{
			// Else, add the user who started the request to the group as admin.
			$stuff = explode ('|', $_COOKIE['logintoken']);	//split login token into user id and encrypted part
			$currentuid = $stuff[0];
			if(! $group_add_stmt = $conn->prepare("INSERT INTO group_roster(user_id,group_id,is_admin) VALUES (?,?,1)")) die("Error preparing SQL statement");  // Only admins can use this page, so set admin to 1
			if(! $group_add_stmt->bind_param('ii', $currentuid, $group_id))	die("Error binding parameters.");
			if(! $group_add_stmt->execute())	die("Error executing SQL statement");
		}
        return 'regsuc';
    }
}

verifyLogin ($conn, $userId, $userName, $isAdmin);
getUserInfo ($conn, $myid, $name, $isUserAdmin, $currentGroupName);
$warning = processGroupAdd ($conn);

if ($warning == 'regsuc') 	header ('Location: index.php?result=groupaddsuccessful');
if ($warning == 'cancel') 					header ('Location:index.php'); //user hit cancel -- just go back to index
?>

<html>
<head>
<meta content="en-us" http-equiv="Content-Language" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script language="javascript" type="text/javascript" src="jquery/jquery.js"></script>


<title><?php echo ucwords($currentGroupName); ?></title> 
</head>
<body>
	<?php include ('header.php'); ?>
	<?php printWarning ($warning);	?> 
	<?php include ('addgroupform.php'); ?>
	<?php include ('footer.php'); ?>
</body>

</html>