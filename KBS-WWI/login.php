<?php
include("components/header.php");
include("functions.php");
include "components/config.php";

//Variabelen
$email = "";
$password = "";
$login = false;

//set password
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    if(strpos($email, "'1'") !== FALSE || strpos($password,"'1'") !== FALSE) {
        echo "<img src='https://media.makeameme.org/created/sql-injection-sql.jpg' height=\"100%\" width=\"100%\">";
        die();
    }
    //check ifempty
    if (check2Empty($email, $password)) {
        print("Een of meerdere velden zijn leeg!");
    } else {
        //Kijk of user exists
        $stmt = $pdo->prepare("Select LogonName, HashedPassword FROM people where LogonName= :email");
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
//                            print("<h2>U wordt nu ingelogd</h2>");
                echo '<script type="text/javascript">window.location = "index.php"</script>';
                die();
            }
        } else {
            print("Deze gebruiker bestaat niet!");
        }
    }
}
?>
    <div class="container">
        <div class="content">
            <h3>Login</h3>
            <br>
            <form action="login.php" method="post">
                <label>email</label>
                <input type="text" name="email" class="form-control">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Inloggen" name="login">
                    <p>Heeft u nog geen account? <a href="register.php">Registreer hier</a>.</p>
                </div>
            </form>
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>