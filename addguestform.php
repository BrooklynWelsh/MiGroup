<?php /*
	--------------------------------------------
				 addguestform.php
					Yu Fu
				  2015-11-6
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	A form to add a guest.

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

//This form accesses the database, which means it must have the database loaded or it will crash.
//It cannot itself make any changes
//If these things change, you can verifyLogin.
require_once 'utils/datefunctions.php'; //for dropDownBoxOfDates
?>

<script language=JavaScript>

function checkGuest(form)
{
  if (form.date.value == "")
  {
    alert("Please select a valid date, no earlier than today.");
    form.date.focus();
    return (false);
  }
  if (form.name.value == "")
  {
    alert("Please enter a name.");
    form.name.focus();
    return (false);
  }
  
  return true;
}

</script>
<div>
	<fieldset>
	<legend>Add a guest</legend>
	<form name="guestForm" method="post"  onSubmit="return checkGuest(this)" action="addguesthandler.php" >	
		<label for="date">Select a new date (after today):</label>
		<p>
		<?php dropDownBoxOfDates ($conn); ?>			
		</p>
		<label for="name">Guest name:</label>
		<p>
			<input id="name" name="name" type="text" class="input">
		</p>
		
		<input type="submit" name="addGuest" id= "submit" value="Add Guest">
	</form>
	</fieldset>
</div>