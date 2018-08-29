<?php

// example code
/*
    include "insert.php";

    $connection = mysqli_connect("localhost","root","");
    $con = mysqli_connect("localhost", "root", "", "demo");

    $name=$_POST["name"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $gender=$_POST["gender"];

    if(mysqli_query("insert into tbl (name,email,password,gender) values ('$name','$email','$password','$gender')"){     echo "inserted successfully"; }
    else{ echo mysqli_error($con);}
*/
    
    $d = $_POST["descript"];

    $con = mysqli_connect("localhost", "root", "", "rentroom");
    
    mysqli_set_charset($con,"utf8");
    $exc = mysqli_query($con,"insert into test_save (description) values ('$d')");

    if($exc) {
        echo "inserted successfully";
    }
    else {
        echo mysqli_error($con);
    }

?>