<?php /*
	--------------------------------------------
				 deletedateform.php
					Yu Fu
				  2015-11-6
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	This form allows deletion of a meeting.  
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
require_once 'utils/datefunctions.php'; //for dropDownBoxOfDates
?>

<div>
<fieldset>
	<legend>Cancel a meeting:</legend>
	<form name="deleteDateForm" method="post" action="adddeletedateshandler.php" >	
		<?php dropDownBoxOfDates ($conn); ?>
		<input type="submit" name="deleteDate" id= "deleteDate" value="Delete">
	</form>
</fieldset>	
</div>