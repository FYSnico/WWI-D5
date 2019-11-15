<?php
$db = "mysql:host=localhost;dbname=wideworldimporters;port=3306";
$user = "root";
$pass = "";
$pdo = new PDO($db, $user, $pass);
try{
    // display a message if connected to database successfully
    if($pdo){
        // echo "Connected to the <strong>$db</strong> database successfully!";
    }
}catch (PDOException $e){
    // report error message
    echo $e->getMessage();
}
?>