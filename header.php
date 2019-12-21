<?php /*
	--------------------------------------------
				header.php
					Yu Fu
				  2015-10-3
				  Edits 2016-05 by W Briggs
				  Edits 2019-11-06 by Brooklyn Welsh (Changed table to a header HTML5 tag and edited CSS style)
	--------------------------------------------
	This is the mastheader, or header, for our pages.

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
	   <http://www.gnu.org/licenses/>.

*/ 


require_once 'utils/globals.php'; //for SITE_NAME
require_once 'utils/cookiefunctions.php';
getUserInfo ($conn, $myid, $name, $isUserAdmin, $currentGroupName);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<header class="header">
		<img id="wheel_left" src="media/logo.png">
        <img id="wheel_right" src="media/logo.png">
        <h1><?php 	if(isset($_COOKIE['currentGroupName'])) echo ucwords($_COOKIE['currentGroupName']); 
					else echo "MiGroup - Manage Weekly Meetings";?></h1>
</header>
