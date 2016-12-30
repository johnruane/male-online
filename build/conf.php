<?

   $db_host="localhost"; //name of the database host
   $db_user="bharat"; //database username
   $db_passwd=""; //password

   //database name give the name of the database where u created entry_detail table using egb_sql.sql file
   $db_database="gb"; //databasename

   define(ENTRY_TABLE,"entry_detail");

   //set this variable to FALSE if you want to display only a certain number
   //of entries of guestbook.
   $display_single_page=FALSE;
		//if above variable is FALSE following number of entries will be displayed at once.
		$display=5;

	//admin login and password for deleting entries required for page
	// admin.php
  $admin_login="admin";
  $admin_password="admin";

?>
