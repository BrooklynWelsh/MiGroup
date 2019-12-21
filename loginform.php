<?php /*
	--------------------------------------------
				 loginform.php
					Yu Fu and Will Briggs
				  2015-10-3, updates by Briggs 2015-11-13 and 2016-05
	--------------------------------------------
	The form to log you in.

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

*/ ?>


		<script language="JavaScript" type="text/javascript">
		function InputCheck(LoginForm)
		{
		  if (LoginForm.username.value == "" )
		  {
		    alert("Username can not be empty!");
		    LoginForm.username.focus();
		    return (false);
		  }
		  if (LoginForm.password.value == "")
		  {
		    alert("Must have password!");
		    LoginForm.password.focus();
		    return (false);
		  }
		
		}
		</script>
		
		<table class="box">
			<tr>
				<td >
					<fieldset>
					<legend>Login</legend>
						<form name="LoginForm" method="post" onSubmit="return InputCheck(this)" action="index.php" >
							<p>
								<label for="username" class="label">Username:</label>
								<input id="username" name="username" type="text" class="input" />
							</p>
							<p>
								<label for="password" class="label">Password:</label>
								<input id="password" name="password" type="password" class="input" />
							</p>
							<p><input type="submit" name="login" id= "submit" value=" Log in "/></p>	
						</form>
					</fieldset>
				</td>
			</tr>
		</table>
