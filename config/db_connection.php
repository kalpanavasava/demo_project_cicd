<?php 
require_once '../config/config.php';

$conn = mysqli_connect(CON_DB_HOST, CON_DB_USER, CON_DB_PASSWORD, CON_DB_NAME);
if( !$conn ){
    die('Connection failed : ' . mysqli_connect_error());
}else{
    echo "Connected successfully.";
}

