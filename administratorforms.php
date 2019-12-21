<?php /*
	--------------------------------------------
				  administratorforms.php
					Yu Fu
				  2015-10-3
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	This file loads, on the main page, forms available only to administrators.
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
	require_once 'utils/queryfunctions.php';
	verifyAdmin ($conn, $userId, $userName, $isAdmin);  // You MUST be an admin to do this...
														// ...and you may also be the superadmin
	$iAmSuperAdmin = ($userId == 1);					//User #1 is always the sole superadministrator
	
	include ('deletedateform.php');
	include ('addspecialmeetingform.php');
?>
<legend>Admin Console:</legend>
<p><a href="changeweekdayofmeeting.php">-Change default meeting day-</a></p>

<p><a href="registration.php">-Register a new user-</a></p>

<p><a href="addgroup.php"> -Create another group-</a></p>

<p><a href="manageusers.php"> -Manage/Delete Users-</a></p>

<?php
	if ($iAmSuperAdmin)
	{
		//this is superadmin to setup admin users
	?>
		<p><a href="manageusers.php">-Manage users-</a>
	<?php	
	}
?>

