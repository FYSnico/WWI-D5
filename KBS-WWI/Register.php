<?php
include "components/config.php";
//Variabelen
$username = "";
$password = "";
$confirm_password = "";

//Checken of account al bestaat
$check = false;
if (!$check) {
if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])){
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $confirm_password = $_POST["confirm_password"];
    }
    if (empty($username)) {
        $userror = "<BR>Please enter username!";
        print($userror);
    } elseif (empty(trim($_POST["password"]))) {
        $passerror = "<br>Please enter password!";
        print($passerror);
    } elseif(!($password == $confirm_password)){
        print("Je moet wel hetzelfde wachtwoord invoeren!");
    }
    else {
        $username = $_POST["username"];
        $stmt = $pdo->prepare("Select username FROM  users where username= :username");
        $stmt->execute(array("username" => $username));
        $username_dbarray = $stmt->fetch();
        $username_db = $username_dbarray[0];
        if ($username_db == $username) {
            print("Deze gebruiker bestaat al!<br>Probeer het opnieuw.");
        } else {
            $check = true;
        }
    }
}
//Email en wachtwoord inserten
if ($check) {
    session_start();
    print($_POST["username"] . "<br>");
    $_SESSION["username"] = $username;
    //Hashen wachtwoord
    $hashedpassword = password_hash($password, PASSWORD_BCRYPT);
    print($hashedpassword);
    $stmt = $pdo->prepare(
        "INSERT INTO users (username, password) VALUES (?, ?)"
    );
    $stmt->execute(array(($username), ($hashedpassword)));

    unset($stmt);
    $PDO = null;
    if(isset($_POST["submit"])){
        print("<h2>U wordt nu ingelogd");
        header("refresh:5;url=http://localhost/WWI-D5/KBS-WWI/index.php", true, 303);
        die();
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
    <h2>Sign Up <?php print("<br>Welkom " . $username); ?></h2>
    <p>Please fill this form to create an account.</p>
    <form action="Register.php" method="post">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?php echo $username?>">
        <label>Password</label>
        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit" name="submit">
    </form>
</div>
</body>
</html>