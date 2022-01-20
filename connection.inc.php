<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'previous_database';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'/FINAL_DBMS_PROJECT_LATEST/DBMS_Project/previous_database/');
define('SITE_PATH','http://127.0.0.1//FINAL_DBMS_PROJECT_LATEST/DBMS_Project/previous_database/');

define('PRODUCT_IMAGE_SERVER_PATH',SERVER_PATH.'../media/product/');
define('PRODUCT_IMAGE_SITE_PATH',SITE_PATH.'../media/product/');
?>