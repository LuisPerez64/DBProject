<?php
/*
  More or less just the database connection query. Held here to make DB manip
  as simple to alter as is possible.
*/
//Set up a connection to the given DB
$connection=mysqli_init();
//Test that the connection was actually instantiated.
if( !$connection)
    die("MySQLi Initialization failed.");
if (!mysqli_real_connect($connect,"localhost:25000","root","")
  die("Connect Error: " . mysqli_connect_error()); 

//Select the Database that will be getting manipulated for the queries.
$mydb = mysql_select_db ('ProjectsDB') or die ('Could not select database');
?>	