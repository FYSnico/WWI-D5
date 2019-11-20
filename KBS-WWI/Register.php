<?php
include("components/header.php");
include("functions.php");
include("components/config.php");

//Variabelen
$username = "";
$password = "";
$confirm_password = "";

//Checken of account al bestaat
$check = false;
if (!$check) {
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $confirm_password = $_POST["confirm_password"];
        if (empty($username)) {
            $userror = "<BR>Please enter username!";
            print($userror);
        } elseif (empty(trim($_POST["password"]))) {
            $passerror = "<br>Please enter password!";
            print($passerror);
        } elseif (!($password == $confirm_password)) {
            print("Je moet wel hetzelfde wachtwoord invoeren!");
        } else {
            $username = $_POST["username"];
            $stmt = $pdo->prepare("Select username FROM  users where username= :username");
            $stmt->execute(array("username" => $username));
            $username_dbarray = $stmt->fetch();
            $username_db = $username_dbarray[0];
            if ($username_db == $username) {
                print("Deze <gebruikersnaam></gebruikersnaam> bestaat al!<br>Probeer het opnieuw.");
            } else {
                $check = true;
            }
        }
    }
}

//Username en wachtwoord inserten
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
    if (isset($_POST["submit"])) {
        print("<h2>U wordt nu ingelogd</h2>");
        echo '<script type="text/javascript">window.location = "index.php"</script>';
        die();
    }
}
?>
    <div class="container">
        <div class="content">
            <h3>Registreren</h3>
            <br>
            <p>Please fill this form to create an account.</p>
            <form action="register.php" method="post">
                <label>Username</label>
                <input type="text" name="username" class="form-control">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit" name="submit">
                    <p>Heeft u al een account? <a href="login.php">Klik hier om in te loggen</a></p>
                    <p>Heeft u de database niet <a href="components/create_users.sql"><br>Klik hier voor het bestand</a></p>
                </div>
            </form>
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>