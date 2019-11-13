<?php
$db = "mysql:host=localhost;dbname=wideworldimporters;port=3306";
$user = "root";
$pass = "";
$conn = new PDO($db, $user, $pass);
try{
    // display a message if connected to database successfully
    if($conn){
        // echo "Connected to the <strong>$db</strong> database successfully!";
    }
}catch (PDOException $e){
    // report error message
    echo $e->getMessage();
}
?>