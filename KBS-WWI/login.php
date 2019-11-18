<?php
include("functions.php");
include "components/config.php";
$username = "";
$password = "";
$login = false;
//set password
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
//check ifempty
    if (check2Empty($username, $password)) {
        print("Een of meerdere velden zijn leeg!");
    } else {
//Kijk of user exists
        $stmt = $pdo->prepare("Select username,password FROM  users where username= :username");
        $stmt->execute(array("username" => $username));
        $user_dbarray = $stmt->fetch();
       // print_r($username_dbarray[0]);
        $username_db = $user_dbarray[0];
        if (($username_db == $username) && password_verify($password, $user_dbarray[1])) {
            print("Username: " . $username_db . " bestaat!<BR>");
            print("Password is " . $user_dbarray[1]);
        } else {
            print("Deze gebruiker bestaat niet!");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="">
        <label>Password</label>
        <input type="password" name="password" class="form-control" value="">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit" name="login">
    </form>
</div>
</body>
</html>
