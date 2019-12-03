<?php
include("components/header.php");
include("functions.php");
include("components/config.php");

//Variabelen
$email = "";
$password = "";
$confirm_password = "";
$adress = "";
$postcode = "";
$telefoonnummer = "";
$naam ="";

//Checken of account al bestaat
$check = false;
if (!$check) {
    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {
        $password = trim($_POST["password"]);
        $email = trim($_POST["email"]);
        $adress = trim($_POST["address"]);
        $postcode = trim($_POST["PostalCode"]);
        $telefoonnummer = trim($_POST["phone"]);
        $confirm_password = $_POST["confirm_password"];
        $naam = $_POST["name"];
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
            $stmt = $pdo->prepare("Select EmailAddress FROM customers where EmailAddress = :email");
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
        "INSERT INTO customers (CustomerName, EmailAddress, HashedPassword, DeliveryPostalCode, DeliveryLocation, PhoneNumber) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute(array(($naam), ($email), ($hashedpassword), ($postcode), ($adress), ($telefoonnummer)));
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
                <label>Naam</label>
                <input type="text" name="name" class="form-control">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
                <label>Adress</label>
                <input type="text" name="address" value="ABCstraat 123" class="form-control">
                <label>Postcode</label>
                <input type="text" name="PostalCode" Value="1234AB" class="form-control">
                <label>Telefoonnummer</label>
                <input type="int" name="phone" class="form-control">
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