MiGroup:  a web site for checking in and checking out participants from meetings.

0. Licensing

   At present, please do not distribute.  Later I'll release it for free distribution.

   There is no warranty of any sort.  

   I would like for someone to donate an image to replace logo.png, as a giveaway.

--

  The included Skranji font has a different licensing agreement.  See fonts/OFL.txt    for its licensing information.

---
I.  Purpose

    This program keeps a membership database and allows users to check in or out of weekly meetings with any device having a web browser.

II. Requirements

    Your web site will need a PHP server, PHP 5.0 or later, and MySQL 5.6 or later.  (Earlier versions may also work.)

II. Setup

    To set your web site up
    1.  Copy it to wherever you want on your web site and rename the folder as you like.
    2.  Edit globals.php so that it has the appropriate database login and password.  
        Get this from your administrator.
    3.  Copy the provided database into MySQL.  If you're doing command line, this should work:
        mysql -u root -p -D "4qskwml" < 4qskwml.sql 
	If not, see docs on whatever system you have for establishing a database.
    4.  Change the username (if need be) and password to whatever your database admin 
        prefers you have.  This is set in utils/dbconnection.php.  
    5.  Go to the web site through your browser.  Your initial account is admin, password admin.  Change that password! and add new accounts.

III. Customization.


    When you first start up the site, there is one user admin, password admin.  If you're
    to be site administrator, I suggest you change the user name and display name to your
    own (and, of course, change the password).

    To change the image at the top, replace logo.png with your own image.  

    To change the font, add new fonts to the fonts folder if desired.  Then open styles.css.  Change occurrences of "Skranji" to whatever you want.

    To change the colors, open styles.css and change
	#aaa111 to your new desired background color 
	#b00100 to your new desired outline and alert color (currently red)
	#bbbbbb to your new desired header and footer color (currently grey)

    To change the number of weeks you keep a checkout record after the meeting passes (lookback), and the number of weeks ahead you can check out (lookahead), open autoupdate.php, and alter these lines as desired:

	$lookback = "3";		//how many weeks back do we keep records?
	$lookahead= "6";		//how many weeks ahead can you check out?

    You won't need to alter $weekday -- this is done by using the site.


IV.   Using the site
    You stay logged in indefinitely if you don't log out.  This is so users don't have to 
    set things up for automatic login.  If you want to log out, you can do this with the 
    log out button.

    A user has a login name and password, and a display name, which should probably be the same
    as the login name, but can be different.

    A superuser can register new users, check others in or out, and schedule or 
    cancel meetings.  There is no way a superuser can recover an old password, but it's easy
	enough to delete and recreate the user.

    The first user (the site administrator -- see Part III) can also make others into
    superusers, and delete users.

IV.   Bug reports

    We're not maintaining this; sorry.  Get your friendly neighborhood PHP programmer to help.