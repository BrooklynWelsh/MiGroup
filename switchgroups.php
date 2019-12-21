<?php 
	/*
	--------------------------------------------
				logout.php
                    Brooklyn Welsh
                    2019-11-18
	--------------------------------------------
	Changes which group calendar the user is viewing.
	
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
       require_once 'utils/groupfunctions.php';
       require_once 'utils/queryfunctions.php'; //functions to do queries securely using prepared statements
    require_once "utils/dbconnection.php"; 
    require_once "utils/warnings.php"; 
    require_once 'utils/globals.php'; //for SITE_NAME
    require_once 'utils/cookiefunctions.php';
    require_once 'utils/security.php';

	switchGroups ($conn);
?> 