<?php
// $conn = mysqli_connect("localhost", "root", "", "demo_project_cicd");
$conn = mysqli_connect("127.0.0.1", "root", "", "demo_project_cicd");
if( !$conn ){
    die('Connection failed : ' . mysqli_connect_error());
}else{
    echo "Connected successfully.";
}

