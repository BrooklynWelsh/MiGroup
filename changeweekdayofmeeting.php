<?php /*
	--------------------------------------------
			   changeweekdayofmeeting.php
					Yu Fu
				  2015-11-12
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	Page to change the weekday of the meeting.

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
require_once 'changeweekdayfunctions.php';
require_once 'utils/cookiefunctions.php';

verifyAdmin ($conn, $userId, $userName, $isAdmin); //only admins can do this

	//If we got form input, process it.  If it was successful, go to index.
$warning = changeWeekdayOfMeeting ($conn);
if ($warning == 'suc') 	header("Location:index.php?result=suc"); 

?>


<html>
<head>
<meta content="en-us" http-equiv="Content-Language" >
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" >
<link rel="stylesheet" type="text/css" href="style.css" >
<title><?php echo SITE_NAME; ?></title>
</head>

<body>
	<?php include ('header.php'); ?>
	<?php printMessageFromURLArgument ($warning);	/* maybe we failed? */ ?>
	<?php printChangeWeekdayForm($conn, $changeweekdayofmeeting, false); ?>
	<?php include ('footer.php'); ?>
</body>

</html>
