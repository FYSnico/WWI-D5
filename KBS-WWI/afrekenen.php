<?php
include 'components/header.php';
include("components/config.php");
include "functions.php";

use PHPMailer\PHPMailer\PHPMailer;

require "mollie/examples/initialize.php";
?>
    <div class="container">
    <div class="contentmiddel afrekenenmidden">
        <?php
        // Verkrijg alle betalingen van Mollie
        $payment = $mollie->payments->page();

        // Check of er een order is en of deze betaald is
        if (!empty($_GET["order_id"]) && ($payment[0]->ispaid() && ($payment[0]->description) == $_GET["order_id"])) {

        // Klantgegevens ophalen
        $subtotaal = 0;
        $sentTo = $_SESSION["email"];
        $sqlcustomer = "Select CustomerID, CustomerName, DeliveryLocation, DeliveryPostalCode FROM customers WHERE EmailAddress = '$sentTo'";
        $result = $pdo->query($sqlcustomer)->fetch();
        $customer = $result["CustomerID"];
        $customerName = $result["CustomerName"];
        $deliveryLocation = $result["DeliveryLocation"];
        $deliveryPostalCode = $result["DeliveryPostalCode"];

        //Zet bestelling in Orders
        $order = $_GET["order_id"];
        $sql = "INSERT INTO orders (OrderID, CustomerID, Orderdate) Values ($order, $customer, CURRENT_TIMESTAMP )";
        $pdo->query($sql);
        $producten = ($_SESSION["shoppingcart"]);
        $totaalPrijs = ($_SESSION["shoppingcart_price"]);

        // Nederlandse besteldatum weergeven
        setlocale(LC_TIME, 'NL_nl');
        $datum = strftime('%e %B %Y');
        $bevestigingsmail = "Bedankt voor uw bestelling<br> U heeft besteld op " . $datum . "<br>";
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
                    <td>€ <?php echo number_format($totaalPrijs, 2, ",", "."); ?></td>
                </tr>
                <!--Korting werkt nog niet
                <tr>
                    <td>Korting:</td>
                    <td><?php /*$_SESSION["discount"] */ ?></td>-->
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
            <?php foreach ($producten as $index => $waarde) {
                // Producten in bestelregels zetten
                $sql = "INSERT INTO orderlines (OrderID, StockItemId, Quantity) VALUES ($order, $waarde[0], $waarde[1])";
                $pdo->query($sql);

                // Bijwerken voorraad
                $sql = "UPDATE stockitemholdings SET LastStocktakeQuantity = LastStocktakeQuantity-$waarde[1] Where StockItemID = $waarde[0]";
                $stmt = $pdo->query($sql);
                unset($stmt);
                // Ophalen productinformatie
                $productinfo = ("SELECT StockItemName, S.UnitPrice FROM orderlines O JOIN StockItems S ON S.StockItemID = O.StockItemID WHERE OrderID = $order AND S.StockItemID = $waarde[0]");
                $info = $pdo->query($productinfo)->fetch();
                $bevestigingsmail .= "<br>Product " . $info[0] . " Aantal: " . $waarde[1] . " Bedrag: EUR: " . number_format($waarde[1] * convertCurrency($info["UnitPrice"], "USD", "EUR"), 2) . "<br>";
                $subtotaal += $waarde[1] * convertCurrency($info["UnitPrice"], "USD", "EUR");
                ?>
                <hr>
                <div class="besteldproduct">
                    <div class="productafbeelding">
                        <a href="http://localhost/WWI-D5/KBS-WWI/product_item.php?id='<?php echo $waarde[0] ?>'"><img
                                    src="images/logo.png"></a>
                    </div>
                    <div class="inforechts">
                        <a href="http://localhost/WWI-D5/KBS-WWI/product_item.php?id='<?php echo $waarde[0] ?>'">
                            <h5>
                                <?php echo $waarde[0] . " | " . $info["StockItemName"]; ?></h5></a>
                        <div class="productinfo">
                            <table>
                                <tr>
                                    <th>Prijs</th>
                                    <th>Hoeveelheid</th>
                                    <th>Totaal</th>
                                </tr>
                                <tr>
                                    <td>
                                        € <?php echo number_format(convertCurrency($info["UnitPrice"], 'USD', 'EUR'), 2, ",", ".") ?></td>
                                    <td><?php echo $waarde[1]; ?></td>
                                    <td>
                                        € <?php echo number_format($waarde[1] * convertCurrency($info["UnitPrice"], 'USD', 'EUR'), 2, ",", ".") ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                unset($info);
            }
            $korting = $subtotaal - $totaalPrijs;
            echo "</div>";
            $bevestigingsmail .= "<br> Uw korting is EUR: -" . number_format($korting, 2) . "<br>Totaal EUR: " . $totaalPrijs . "<br><br>Vriendelijke groeten WWI";
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
            } elseif (!empty($_GET["order_id"]) && (!$payment[0]->ispaid() && ($payment[0]->description)) && !empty($payment[0]->getCheckoutURL())) {
                echo "De betaling staat op open. Om alsnog te betalen<BR>";
                echo "<a href=" . $payment[0]->getCheckoutURL();
                echo ">Klik hier</a>";
                echo "<BR>De betalingsmogelijkheid verloopt binnen 15 minuten.";
            } else {
                print("Er is iets mis gegaan, probeer alstublieft opniew te bestellen.<br>");
                print("<a href='winkelmand.php'>Klik hier</a> om terug te gaan naar de winkelmand");
            }
            ?>
        </div>
    </div>
<?php include 'components/footer.php'; ?>