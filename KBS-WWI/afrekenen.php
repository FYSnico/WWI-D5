<!DOCTYPE html>
<html>

<?php include 'components/header.php';
include("components/config.php");

use PHPMailer\PHPMailer\PHPMailer;

?>


<body>
<?php
require "mollie/examples/initialize.php";
//Verkrijg alle betalingen van Mollie
$payment = $mollie->payments->page();
//Check of er een order is en of deze betaald is
if (!empty($_GET["order_id"]) && ($payment[0]->ispaid() && ($payment[0]->description) == $_GET["order_id"])) {
    //Verkrijg CustomerID van diegene die besteld
    $order = $_GET["order_id"];
    $sentTo = $_SESSION["email"];
    $sqlcustomer = "Select CustomerID FROM customers WHERE EmailAddress = '$sentTo'";
    $result = $pdo->query($sqlcustomer)->fetch();
    $customer = $result["CustomerID"];
    //Zet bestelling in Orders
    $sql = "INSERT INTO orders (OrderID, CustomerID, Orderdate) Values ($order, $customer, CURRENT_TIMESTAMP )";
    $pdo->query($sql);
    print("Betaald, U ontvangt nu een bevestingsmail");
    $producten = ($_SESSION["shoppingcart"]);
    //Start van het maken van een mailtje
    $datum = date('d/m/y');
    $bevestigingsmail = "Bedankt voor uw bestelling<br> U heeft besteld op ". $datum;
    foreach ($producten as $index => $waarde) {
        print("<BR>");
        print("productnummer: " . $waarde[0] . " ");
        print("Aantal: " . $waarde[1]);
        $sql = "INSERT INTO orderlines (OrderID, StockItemId, Quantity) VALUES ($order, $waarde[0], $waarde[1])";
        $pdo->query($sql);
        //Voor ieder product het aantal en productnummer in de mail
        $bevestigingsmail .= "<br>Productnummer: " . $waarde[0] . " Aantal: " . $waarde[1];
        //update de voorraad
        $sql = "UPDATE stockitemholdings SET LastStocktakeQuantity = LastStocktakeQuantity-$waarde[1] Where StockItemID = $waarde[0]";
        $stmt = $pdo->query($sql);
        unset($stmt);
    }
    $bevestigingsmail .= "<br><br>Vriendelijke groeten WWI";
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "ssl://smtp.gmail.com";
    $mail->Port = 465;
    $mail->IsHTML(true);
    $mail->Username = "project.wwi.d5@gmail.com";
    $mail->Password = "moeilijkwachtwoord";
    $mail->SetFrom("project.wwi.d5@gmail.com");
    $mail->Subject = "Orderbevestiging " . $_GET["order_id"];
    $mail->Body = $bevestigingsmail;
    $mail->AddAddress($sentTo);

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
    unset($_SESSION["shoppingcart"]);
} else {
    print("Er is iets mis gegaan, probeer alstublieft opniew te bestellen.");
}

?>
</body>

<?php include 'components/footer.php'; ?>

</html>
