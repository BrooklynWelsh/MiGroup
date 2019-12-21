<?php /*
	--------------------------------------------
				 addspecialmeetingform.php
					Yu Fu
				  2015-11-6
			  Edits 2016-05 by W Briggs
	--------------------------------------------
	Form to manually add a meeting.

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
verifyAdmin ($conn, $id, $name, $isAdmin); //if you aren't admin, you can't see this page	  
?>

<script language=JavaScript>

function InputCheck(dateForm)
{
  if (dateForm.date.value == "")
  {
    alert("Please select a valid date, no earlier than today.");
    dateForm.date.focus();
    return (false);
  }
}

</script>
<div>
	<fieldset>
	<legend>Add a meeting:</legend>
	<form name="dateForm" method="post"  onSubmit="return InputCheck(this)" action="adddeletedateshandler.php" >	
		<label for="date">Select date (after today):</label>
		<p>
			<?php
				echo '<input id="date" name="date" type="date" class="input" min="'.date("Y-m-d").'">';
			?>
		</p>
		<input type="submit" name="addDate" id= "submit" value="Add Date ">
	</form>
	</fieldset>
</div>