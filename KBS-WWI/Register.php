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

?>
<div class="container">
    <div class="content">
        <h3>Registreren</h3>
        <br>
        <?php
        //Validatie
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
                    $userror = "<p class=\"alert alert-danger\">E-mailadres is verplicht.</p>";
                    print($userror);
                } elseif (empty(trim($_POST["password"]))) {
                    $passerror = "<p class=\"alert alert-danger\">Wachtwoord is verplicht.</p>";
                    print($passerror);
                }elseif(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/m', $password)) {
                    print("<p class=\"alert alert-danger\">Het wachtwoord moet minimaal 8 tekens lang zijn, een hoofdletter bevatten, een getal en eén van de speciale tekens.</p>");
                }elseif (!($password == $confirm_password)) {
                    print("<p class=\"alert alert-danger\">Beide wachtwoorden moeten hetzelfde zijn.</p>");
                }else{
                    $email = $_POST["email"];
                    $stmt = $pdo->prepare("Select EmailAddress FROM customers where EmailAddress = :email");
                    $stmt->execute(array("email" => $email));
                    $email_dbarray = $stmt->fetch();
                    $email_db = $email_dbarray[0];
                    if ($email_db == $email) {
                        print("<p class=\"alert alert-warning\">Account bestaat al! Probeer in te loggen.</p>");
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
        <p class="text-underline"><u>Alle velden met <strong class="text-danger">*</strong> zijn verplicht.</u></p>
        <form action="register.php" method="post">
            <label>Naam<strong class="text-danger">*</strong></label>
            <input type="text" name="name" value="<?php echo $naam ?>" class="form-control">
            <label>E-mailadres<strong class="text-danger">*</strong></label>
            <input type="email" name="email" value="<?php echo $email ?>" class="form-control">
            <label>Adress</label>
            <input type="text" name="address" value="<?php echo $adress ?>" placeholder="ABCstraat 123" class="form-control">
            <label>Postcode</label>
            <input type="text" name="PostalCode" Value="<?php echo $postcode ?>" placeholder="1234AB" class="form-control">
            <label>Telefoonnummer</label>
            <input type="int" name="phone" value="<?php echo $telefoonnummer ?>" class="form-control">
            <label>Nieuw wachtwoord <strong class="text-danger">*</strong></label>
            <input type="password" name="password" class="form-control">
            <label>Herhaal nieuw wachtwoord<strong class="text-danger">*</strong></label>
            <input type="password" name="confirm_password" class="form-control">
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-primary" value="Submit" name="submit">
                <p class="mt-2">Heeft u al een account? <a href="login.php">Klik hier om in te loggen</a></p>
            </div>
        </form>
    </div>
</div>
<br><br>
<?php include('components/footer.php') ?>