<?php 
$servername = "184.168.102.106";
$username = "demo_project_cicd";
$password = "C]3*]9I23sFa";
$dbname = "demo_project_cicd";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if( !$conn ){
    die('Connection failed : ' . mysqli_connect_error());
}else{
    echo "Connected successfully.";
}

