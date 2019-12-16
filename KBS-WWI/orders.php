<?php
include "components/header.php";
include "components/config.php";
require "mollie/examples/initialize.php";

error_reporting(E_ALL ^ E_NOTICE);
$payment = $mollie->payments->page();
$mail = $_SESSION["email"];
$sqlcustomer = "Select CustomerID FROM customers WHERE EmailAddress = '$mail'";
$result = $pdo->query($sqlcustomer)->fetch();
$customer = $result["CustomerID"];
$sqlorder = "SELECT OrderID, OrderDate FROM orders WHERE CustomerID = $customer";
$order = $pdo->query($sqlorder)->fetchAll();
?>
    <div class="container">
        <div class="contentmiddel">
            <h1>Mijn bestellingen</h1>
            <?php
            foreach ($order as $index => $value) {
                print("Uw ordernummer: " . $value[0] . ". U heeft het besteld op " . $value[1] . "<BR>");
                for ($i = 0; $i <= sizeof($payment); $i++) {
                    try {
                        if ($payment[$i]->description == $value[0]) {
                            if ($payment[$i]->ispaid()) {
                                print(" Het product is betaald.<BR>");
                            } else {
                                print("Product is niet betaald.");
                            };
                        }
                    } catch (Exception $e) {
                    }
                }
                $sql = "SELECT StockItemID, Quantity FROM orderlines WHERE OrderID = $value[0]";
                $result1 = $pdo->query($sql)->fetchAll();
                foreach ($result1 as $index1 => $value1) {
                    print("productnummer: " . $value1[0] . " besteld. Aantal: " . $value1[1] . "<BR>");
                }
                print("<BR>");
            }
            ?>
        </div>
    </div>
<?php include "components/footer.php";