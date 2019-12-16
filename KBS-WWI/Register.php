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
$naam = "";

//Checken of account al bestaat
$check = false;

?>
    <div class="container">
        <div class="content">
            <div class="invoerform">
                <h3>Registreren</h3>
                <br>
                <?php
                //Validatie
                if (!$check) {
                    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {
                        $password = trim(filter_input(INPUT_POST, "password",FILTER_SANITIZE_STRING));
                        $adress = trim(filter_input(INPUT_POST, "address",FILTER_SANITIZE_STRING));
                        $email = trim(filter_input(INPUT_POST, "email",FILTER_SANITIZE_STRING));
                        $postcode = trim(filter_input(INPUT_POST, "postcode",FILTER_SANITIZE_STRING));
                        $telefoonnummer = trim(filter_input(INPUT_POST, "telefoonnummer",FILTER_SANITIZE_STRING));
                        $confirm_password = trim(filter_input(INPUT_POST, "confirm_password",FILTER_SANITIZE_STRING));
                        $naam = trim(filter_input(INPUT_POST, "name",FILTER_SANITIZE_STRING));
//                        $password = trim($_POST["password"]);
//                        $email = trim($_POST["email"]);
//                        $adress = trim($_POST["address"]);
//                        $postcode = trim($_POST["PostalCode"]);
//                        $telefoonnummer = trim($_POST["phone"]);
//                        $confirm_password = $_POST["confirm_password"];
//                        $naam = $_POST["name"];
                        if (empty($email)) {
                            $userror = "<p class=\"alert alert-danger\">E-mailadres is verplicht.</p>";
                            print($userror);
                        } elseif (empty(trim($_POST["password"]))) {
                            $passerror = "<p class=\"alert alert-danger\">Wachtwoord is verplicht.</p>";
                            print($passerror);
                        } elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,}$/m', $password)) {
                            print("<p class=\"alert alert-danger\">Het wachtwoord moet minimaal 8 tekens lang zijn, een hoofdletter bevatten, een getal en één van de speciale tekens.</p>");
                        } elseif (!($password == $confirm_password)) {
                            print("<p class=\"alert alert-danger\">Beide wachtwoorden moeten hetzelfde zijn.</p>");
                        } else {
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
                    //print($_POST["email"] . "<br>");
                    $_SESSION["email"] = $email;
                    $_SESSION["naam"] = $naam;
                    //Hashen wachtwoord
                    $hashedpassword = password_hash($password, PASSWORD_BCRYPT);
                    //print($hashedpassword);
                    $stmt = $pdo->prepare(
                        "INSERT INTO customers (CustomerName, EmailAddress, HashedPassword, DeliveryPostalCode, DeliveryLocation, PhoneNumber) VALUES (?, ?, ?, ?, ?, ?)"
                    );
                    $stmt->execute(array(($naam), ($email), ($hashedpassword), ($postcode), ($adress), ($telefoonnummer)));
                    unset($stmt);
                    $PDO = null;
                    if (isset($_POST["submit"])) {
                        //print("<h2>U wordt nu ingelogd</h2>");
                        echo '<script type="text/javascript">window.location = "index.php"</script>';
                        die();
                    }
                }
                ?>
                <form class="conversieform" action="register.php" method="post">
                    <label>
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="form-control" required placeholder="Naam">
                    </label>
                    <label>
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control" required placeholder="E-mailadres">
                    </label>
                    <label>
                        <i class="fas fa-road"></i>
                        <input type="text" name="address" class="form-control" required placeholder="Adres">
                    </label>
                    <label>
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" name="PostalCode" class="form-control" required placeholder="Postcode">
                    </label>
                    <p class="opmerking">Optioneel</p>
                    <label>
                        <i class="fas fa-mobile-alt"></i>
                        <input type="int" name="phone" class="form-control" placeholder="Telefoonnummer">
                    </label>
                    <label>
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" required placeholder="Wachtwoord">
                    </label>
                    <p class="inputrequirement">Het wachtwoord moet minimaal 8 tekens lang zijn, een hoofdletter, een getal en één van de
                        speciale tekens bevatten.</p>
                    <label>
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" class="form-control" required
                               placeholder="Herhaal wachtwoord">
                    </label>
                    <div class="form-group mt-3">
                        <input class="formulierknop" type="submit" class="btn btn-primary" value="Registreer"
                               name="submit">
                        <br>
                        <p class="mt-2 pnormaal">Heeft u al een account?</p>
                        <input class="formulierknop" class="btn btn-primary" value="Log in"
                               onclick="window.location='login.php';"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>