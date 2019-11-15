<?php
include "components/config.php";
$username = "";
$password = "";
$confirm_password = "";
if (isset($_POST["username"])) {
    print($_POST["username"]);
    if (empty(trim($_POST["username"]))) {
        $userror = "Please enter username";
        echo "Enter username";
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO users (username, password) VALUES (?, ?)"
    );
        $stmt->execute(array((trim($_POST["username"])), (trim($_POST["password"]))));
    }
    unset($stmt);
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
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="Register.php" method="post">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="">
        <label>Password</label>
        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>