<?php /*
	--------------------------------------------
					setup.php
					W Briggs
				  2016-05
	--------------------------------------------
	This is a page to help with initial setup. 

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
require_once 'utils/dbconnection.php'; 
require_once 'utils/warnings.php';
require_once 'utils/cookiefunctions.php';
require_once 'changeweekdayfunctions.php';

verifySuperAdmin ($conn, $id, $name, $isAdmin);

	//If we got form input, process it.  If it was successful, go to index.
$warning = changeWeekdayOfMeeting ($conn);
if ($warning == 'suc') 	header("Location:changeprofile.php?result=suc"); 
?>

<html>
<head>
<meta content="en-us" http-equiv="Content-Language" >
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" >
<link rel="stylesheet" type="text/css" href="style.css" >
<title><?php echo SITE_NAME; ?></title> </head>

<body>
	<?php include ('header.php'); ?>
	<?php printMessageFromURLArgument ($warning);	/* maybe we failed? */ ?>
	<div class="info">
		<p>Looks like there are no meetings yet, which likely means that you are just starting out.</p> 
		<p>Please edit the file utils/globals.php before going further, setting up your time zone, site name, and other parameters.</p>
		<p>After that...</p> 
	</div>
	<?php printChangeWeekdayForm ($conn, 'setup.php', true); ?>
	<div class="info">
		<p>After setting the weekday, you will be taken to a form to change your administrator name and password.</p>
		<p>You can cancel instead of making the change, but it is strongly recommended that you do it now.</p>
	</div>
	<?php include ('footer.php'); ?>
</body>
</html>
