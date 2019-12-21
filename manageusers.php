<?php 

/*
	--------------------------------------------
			     manageusers.php
					Yu Fu
				  2015-11-14
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	A page to manage users' administrator status, and delete them.

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

require_once 'utils/dbconnection.php';
require_once "utils/warnings.php";
require_once 'utils/globals.php';
require_once 'utils/cookiefunctions.php';
require_once 'utils/queryfunctions.php';
verifyAdmin ($conn, $id, $name, $isAdmin);
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css" >
<script language="javascript" type="text/javascript" src="jquery/jquery.js"></script>
<title><?php echo SITE_NAME; ?></title> 
</head>
<body>
	<?php include ('header.php'); ?>
	<p></p>
	<?php printMessageFromURLArgument (); ?>
	<?php
		if (instancesFound ($conn, "SELECT * from users") < 2)	//We can't manage the superadministrator; if num users <2, that must be
																//the only one that exists.  So don't do anything
			echo '<p><center><span class="warning">No users found to manage!</span></center><p>';
		else
		{
			echo "<table><tr><td width='50%' class='outlineForForm'>";
			include 'manageusers/manageadminstatusform.php'; 
			echo "</td><td width='50%' class = 'outlineForForm'>";
			include 'manageusers/deleteusersform.php'; 
			echo "</td></tr></table>";
		}
	?>
	<input type='button' onclick=window.location.href='index.php' value='Home Page' class='left' >
	<?php include ('footer.php'); ?>
</body>
</html>
