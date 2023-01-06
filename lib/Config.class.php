<?php

/* 
We have our database configuration here and also other configurations that are -hard coded- within our site!
*/

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'shout');

/*
Hard coded settings!
*/
define('MAINTENANCE', FALSE);
define('SITENAME', 'Shout.it');

if(MAINTENANCE==TRUE){

    exit('Sorry, this website is under maintenance for now!');
}


/* 
Database Connection
*/
$databaseCon = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($databaseCon->connect_errno) {

    echo "[Critical Error]: We were unable to connect to the database, please check your settings!", $con->connect_error();
    exit();
}

?>

