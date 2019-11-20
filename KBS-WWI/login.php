<?php
include("components/header.php");
include("functions.php");
include "components/config.php";

//Variabelen
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
        $stmt = $pdo->prepare("Select username, password FROM  users where username= :username");
        $stmt->execute(array("username" => $username));
        $user_dbarray = $stmt->fetch();
        // print_r($username_dbarray[0]);
        $username_db = $user_dbarray[0];
        if (($username_db == $username) && password_verify($password, $user_dbarray[1])) {
//                        print("Username: " . $username_db . " bestaat!<BR>");
//                        print("Password is " . $user_dbarray[1]);
            unset($stmt);
            $pdo = NULL;
            if (isset($_POST["login"])) {
                $_SESSION["username"] = $username;
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
                <label>Username</label>
                <input type="text" name="username" class="form-control">
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