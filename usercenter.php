<?php
/*

	--------------------------------------------
			  usercenter.php
				Yu Fu
			  2015-10-3
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	The user center goes on the main page (index) after login.
	It contains links and buttons to change user information, log out, and invite guests.
	If the user is an administrator, it contains administrator functions.
	
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
?>

<body style="height: 200px">
<table class = "controls" style="width: 100%; height: 150px">
	<tr>
		<td style="width: 100%;height:100px">
			<fieldset>
				<legend>User Center:</legend>
				<?php
					echo "Welcome back, " . $userName . "!";		
				?>
				<br>		
				<p><a href="changeprofile.php">-Change profile information-</a><br>
						<br>
					<input type="button" onclick="window.location.href='logout.php';" value="Log out" class="right">
				</form>
			</fieldset>
		</td>
	</tr>

	<tr>
		<td><?php include 'addguestform.php'; ?> 
	</td>
	</tr>

	<tr>
		<td style="width: 100%; height:100px">
		<?php			
			if ($isUserAdmin)
				include "administratorforms.php";
		?>
		</td>

	</tr>
</table>
</body>


