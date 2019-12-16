<?php
include 'components/header.php';
include("components/config.php");

use PHPMailer\PHPMailer\PHPMailer;

require "mollie/examples/initialize.php";
?>
    <div class="container">
    <div class="contentmiddel">
        <?php
        // Verkrijg alle betalingen van Mollie
        $payment = $mollie->payments->page();

        // Check of er een order is en of deze betaald is
        if (!empty($_GET["order_id"]) && ($payment[0]->ispaid() && ($payment[0]->description) == $_GET["order_id"])) {

        // Klantgegevens ophalen
        $sentTo = $_SESSION["email"];
        $sqlcustomer = "Select CustomerID, CustomerName, DeliveryLocation, DeliveryPostalCode FROM customers WHERE EmailAddress = '$sentTo'";
        $result = $pdo->query($sqlcustomer)->fetch();
        $customer = $result["CustomerID"];
        $customerName = $result["CustomerName"];
        $deliveryLocation = $result["DeliveryLocation"];
        $deliveryPostalCode = $result["DeliveryPostalCode"];

        //                // Ordergegevens ophalen
        //                $order = $_GET["order_id"];
        //                $sqlorder = "Select StockItemID, Quantity FROM orderlines WHERE OrderID = '$order'";
        //                $result2 = $pdo->query($sqlorder)->fetch();
        //                $stockItemID = $result2["StockItemID"];
        //                $quantity = $result2["Quantity"];

        //Zet bestelling in Orders
        $order = $_GET["order_id"];
        $sql = "INSERT INTO orders (OrderID, CustomerID, Orderdate) Values ($order, $customer, CURRENT_TIMESTAMP )";
        $pdo->query($sql);
        $producten = ($_SESSION["shoppingcart"]);
        $totaalPrijs = ($_SESSION["shoppingcart_price"]);

        // Dit werkt helaas nog niet
        //                setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        //                $totaalPrijs = money_format('%(#1n', $prijzen);

        // Nederlandse besteldatum weergeven
        setlocale(LC_TIME, 'NL_nl');
        $datum = strftime('%e %B %Y');
        $bevestigingsmail = "Bedankt voor uw bestelling<br> U heeft besteld op " . $datum;
        ?>
        <img class="center" src="images/logo.png">
        <h1>Bedankt voor uw bestelling</h1>
        <p>We hebben je een bevestigingsmail verstuurd naar <?php echo $sentTo ?>.<br>Mochten er vragen zijn <a
                    href="contact.php">neem dan contact met ons op</a>.</p>
        <div class="flex">
            <table id="bestellingoverzicht">
                <tr>
                    <th>Bestellingoverzicht</th>
                    <th></th>
                </tr>
                <tr>
                    <td>Ordernummer:</td>
                    <td><?php echo $order; ?></td>
                </tr>
                <tr>
                    <td>Besteldatum:</td>
                    <td><?php echo $datum; ?></td>
                </tr>
                <tr>
                    <td>Totaalbedrag:</td>
                    <td>€ <?php echo $totaalPrijs; ?></td>
                </tr>
            </table>
            <table>
                <tr>
                    <th>Bezorgadres</th>
                </tr>
                <tr>
                    <td><?php echo $customerName; ?></td>
                </tr>
                <tr>
                    <td><?php echo $deliveryLocation; ?></td>
                </tr>
                <tr>
                    <td><?php echo $deliveryPostalCode; ?></td>
                </tr>
            </table>
        </div>
        <br>
        <div class="besteldproducten">
            <h4>Bestelde producten</h4>
            <?php foreach ($producten as $index => $waarde) { ?>
                <hr>
                <div class="besteldproduct">
                    <div class="productafbeelding">
                        <a href="http://localhost/WWI-D5/KBS-WWI/product_item.php?id='<?php echo $waarde[0] ?>'"><img
                                    src="images/logo.png"></a>
                    </div>
                    <div class="inforechts">
                        <a href="http://localhost/WWI-D5/KBS-WWI/product_item.php?id='<?php echo $waarde[0] ?>'">
                            <h5>
                                Productnaam</h5></a>
                        <div class="productinfo">
                            <p>Productnummer: <?php echo $waarde[0]; ?></p>
                            <p>Hoeveelheid: <?php echo $waarde[1]; ?></p>
                            <p>Prijs: Staat in een array in een array ofzo</p>
                        </div>
                    </div>
                </div>

                <?php
                $sql = "INSERT INTO orderlines (OrderID, StockItemId, Quantity) VALUES ($order, $waarde[0], $waarde[1])";
                $pdo->query($sql);
                //Voor ieder product het aantal en productnummer in de mail
                $bevestigingsmail .= "<br>Productnummer: " . $waarde[0] . " Aantal: " . $waarde[1];
                //update de voorraad
                $sql = "UPDATE stockitemholdings SET LastStocktakeQuantity = LastStocktakeQuantity-$waarde[1] Where StockItemID = $waarde[0]";
                $stmt = $pdo->query($sql);
                unset($stmt);
            }
            echo "</div>";
            // Hier stond een }, die heb ik weggehaald. Mocht er iets kapot zijn is het mijn schuld.
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
            //unset($_SESSION["shoppingcart"]);
            } else {
                print("Er is iets mis gegaan, probeer alstublieft opniew te bestellen.");
            }

            ?>
        </div>
    </div>
<?php include 'components/footer.php'; ?>