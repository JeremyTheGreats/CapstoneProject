<?php

$conn = mysqli_connect("localhost","root","","Ecocare");

if(!$conn){
    die("Connection Failed!: " . mysqli_connect_error());
}

?>