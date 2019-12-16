<?php
include("components/header.php");
include("functions.php");
include "components/config.php";

//Variabelen
$email = "";
$password = "";
$login = false;
$naam = "";

//set password
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    if (strpos($email, "'1'") !== FALSE || strpos($password, "'1'") !== FALSE) {
        echo "<img src='https://media.makeameme.org/created/sql-injection-sql.jpg' height=\"100%\" width=\"100%\">";
        die();
    }
    //check ifempty
    if (check2Empty($email, $password)) {
        print("<p class=\"mt-2 pnormaal\">Een of meerdere velden zijn leeg.</p>");
    } else {
        //Kijk of user exists
        $stmt = $pdo->prepare("Select Emailaddress, HashedPassword, CustomerName, IsSystemUser FROM customers where Emailaddress= :email");
        $stmt->execute(array("email" => $email));
        $email_dbarray = $stmt->fetch();
        $email_db = $email_dbarray[0];
        if (($email_db == $email) && password_verify($password, $email_dbarray[1])) {
//                     print("email: " . $email_db . " bestaat!<BR>");
//                        print("Password is " . $user_dbarray[1]);
            unset($stmt);
            $pdo = NULL;
            if (isset($_POST["login"])) {
                $_SESSION["email"] = $email;
                $_SESSION["naam"] = $email_dbarray[2];
                $_SESSION["IsSystemUser"] = $email_dbarray[3];

//                            print("<h2>U wordt nu ingelogd</h2>");
                echo '<script type="text/javascript">window.location = "index.php"</script>';
                die();
            }
        } else {
                print("<p class=\"mt-2 pnormaal\">De combinatie van e-mailadres en wachtwoord is niet geldig.</p>");

            }

        }
    }
}
?>
    <div class="container">
        <div class="content">
            <div class="invoerform">
                <h3>Login</h3>
                <br>
                <form class="conversieform" action="login.php" method="post">
                    <label>
                        <i class="fas fa-user"></i>
                        <input type="text" name="email" class="form-control" required placeholder="E-mailadres">
                    </label>
                    <label>
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" required placeholder="Wachtwoord">
                    </label>
                    <div class="form-group mt-3">
                        <input class="formulierknop" type="submit" class="btn btn-primary" value="Login" name="login">
                        <br>
                        <p class="mt-2 pnormaal">Heeft nog geen account?</p>
                        <input class="formulierknop" class="btn btn-primary" value="Registreren"
                               onclick="window.location='Register.php';"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>