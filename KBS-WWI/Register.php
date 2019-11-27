<?php
include("components/header.php");
include("functions.php");
include("components/config.php");

//Variabelen
$email = "";
$password = "";
$confirm_password = "";

//Checken of account al bestaat
$check = false;
if (!$check) {
    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {
      $password = trim($_POST["password"]);
      $email = trim($_POST["email"]);
        $confirm_password = $_POST["confirm_password"];
        if (empty($email)) {
            $userror = "<BR>Please enter email!";
            print($userror);
        } elseif (empty(trim($_POST["password"]))) {
            $passerror = "<br>Please enter password!";
            print($passerror);
        } elseif (!($password == $confirm_password)) {
            print("Je moet wel hetzelfde wachtwoord invoeren!");
        } else {
            $email = $_POST["email"];
            $stmt = $pdo->prepare("Select LogonName FROM people where EmailAddress = :email");
            $stmt->execute(array("email" => $email));
            print_r($stmt);
            print('hoi');
            $email_dbarray = $stmt->fetch();
            $email_db = $email_dbarray[0];
            print($email_db);
            print_r($email_dbarray);
            if ($email_db == $email) {
                print("Deze <gebruikersnaam></gebruikersnaam> bestaat al!<br>Probeer het opnieuw.");
            } else {
                $check = true;
            }
        }
    }
}

//email en wachtwoord inserten
if ($check) {
    session_start();
    print($_POST["email"] . "<br>");
    $_SESSION["email"] = $email;
    //Hashen wachtwoord
    $hashedpassword = password_hash($password, PASSWORD_BCRYPT);
    print($hashedpassword);
    $stmt = $pdo->prepare(
        "INSERT INTO people (LogonName, HashedPassword) VALUES (?, ?)"
    );
    $stmt->execute(array(($email), ($hashedpassword)));
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
                <label>Email</label>
                <input type="email" name="email" class="form-control">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit" name="submit">
                    <p>Heeft u al een account? <a href="login.php">Klik hier om in te loggen</a></p>
                </div>
            </form>
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>