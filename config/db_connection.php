<?php
$servername = "mysql"; 
$username = "root";                           
$password = "";                  
$dbname = "demo_project_cicd";  
$conn = mysqli_connect($servername, $username, $password, $dbname);
if( !$conn ){
    die('Connection failed : ' . mysqli_connect_error());
}else{
    echo "Connected successfully.";
}

