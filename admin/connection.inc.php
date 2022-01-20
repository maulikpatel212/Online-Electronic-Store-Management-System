<?php
session_start();
$con=mysqli_connect("localhost","root","","previous_database");
define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'/FINAL_DBMS_PROJECT_LATEST/DBMS_Project/previous_database/');
define('SITE_PATH','http://127.0.0.1//FINAL_DBMS_PROJECT_LATEST/DBMS_Project/previous_database/');

define('PRODUCT_IMAGE_SERVER_PATH',SERVER_PATH.'../media/product/');
define('PRODUCT_IMAGE_SITE_PATH',SITE_PATH.'../media/product/');
?>