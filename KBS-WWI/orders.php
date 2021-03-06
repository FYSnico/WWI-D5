<?php
include "components/header.php";
include "components/config.php";
include "functions.php";

// Betaalgegevens ophalen
require "mollie/examples/initialize.php";

// Molly dingen weghalen die niet gebruikt worden
error_reporting(E_ALL ^ E_NOTICE);
$payment = $mollie->payments->page();

// Klantgegevens ophalen
$sentTo = $_SESSION["email"];
$stmtcustomer = $pdo->prepare('Select CustomerID, CustomerName, DeliveryLocation, DeliveryPostalCode FROM customers WHERE EmailAddress = ?');
$stmtcustomer->execute(array($sentTo));
$result = $stmtcustomer->fetch();
$customer = $result["CustomerID"];
$customerName = $result["CustomerName"];
$deliveryLocation = $result["DeliveryLocation"];
$deliveryPostalCode = $result["DeliveryPostalCode"];

// Ordergegevens ophalen
$sqlOrder = $pdo->prepare('SELECT OrderID, OrderDate FROM orders WHERE CustomerID = ?');
$sqlOrder->execute(array($customer));
$orderResult = $sqlOrder->fetchAll();
$orderID = $orderResult["OrderID"];
?>
    <div class="container">
        <div class="contentmiddel">
            <h1>Mijn bestellingen</h1>
            <?php
            // Bestelregels weergeven
            foreach ($orderResult

            as $orderWaarde) {
            $orderID = $orderWaarde["OrderID"];
            $totaalBestelling = 0;
            ?>
            <br>
            <div class="bestelregel">
                <div>
                    <?php
                    // Nederlandse besteldatum en bestelnummer weergeven
                    setlocale(LC_TIME, 'NL_nl');
                    $orderDatum = strftime('%e %B %Y', strtotime($orderWaarde["OrderDate"]));
                    echo $orderDatum . " | Bestelnummer " . $orderID;
                    ?>
                </div>
                <div class="besteldproducten">
                    <?php
                    // Producten in bestelregel weergeven
                    $sqlOrderLine = $pdo->prepare('SELECT StockItemID, Quantity FROM orderlines WHERE OrderID = ?');
                    $sqlOrderLine->execute(array($orderID));
                    $orderLineResult = $sqlOrderLine->fetchAll();
                    foreach ($orderLineResult

                    as $orderLineWaarde) {
                    // Productgegevens ophalen
                    $stockItemID = $orderLineWaarde["StockItemID"];
                    $hoeveelheid = $orderLineWaarde["Quantity"];
                    $sqlProduct = $pdo->prepare('SELECT StockItemName, UnitPrice, Photo FROM stockitems WHERE StockItemID = ?');
                    $sqlProduct->execute(array($stockItemID));
                    $productResult = $sqlProduct->fetch();

                    $productNaam = $productResult["StockItemName"];
                    $productPrijs = convertCurrency($productResult["UnitPrice"], 'USD', 'EUR');
                    $productAfbeelding = $productResult["Photo"];

                    $totaalProduct = $productPrijs * $hoeveelheid;
                    $totaalBestelling += $totaalProduct;
                    ?>
                    <hr>
                    <div class="besteldproduct">
                        <div class="productafbeelding">
                            <a href="http://localhost/WWI-D5/KBS-WWI/product_item.php?id='<?php echo $stockItemID ?>'">
                                <?php
                                if ($productAfbeelding) {
                                    echo '<img class="card-img-top kaartimg" src="data:image/jpeg;base64,' . base64_encode($productAfbeelding) . '"/>';
                                } else {
                                    echo '<img class="card-img-top kaartimg" src="images/default-product.png" alt="">';
                                }
                                ?>
                            </a>
                        </div>
                        <div class="inforechts">
                            <h5>
                                <a href="http://localhost/WWI-D5/KBS-WWI/product_item.php?id='<?php echo $stockItemID ?>'"><?php echo $productNaam ?></a>
                            </h5>
                            <div class="productinfo">
                                <table>
                                    <tr>
                                        <th>Prijs</th>
                                        <th>Hoeveelheid</th>
                                        <th>Totaal</th>
                                    </tr>
                                    <tr>
                                        <td>€ <?php echo number_format($productPrijs, 2, ",", ".") ?></td>
                                        <td><?php echo $hoeveelheid; ?></td>
                                        <td>€ <?php echo number_format($totaalProduct, 2, ",", ".") ?></td>
                                    </tr>
                                </table>
                                <p>Garantie: Fabrieksgarantie</p>
                                <?php
                                // Betaalstatus weergeven
                                for ($i = 0; $i <= sizeof($payment); $i++) {
                                    try {
                                        if ($payment[$i]->description == $orderWaarde["OrderID"]) {
                                            if ($payment[$i]->ispaid()) {
                                                echo "<p class='groen'>Betaald</p>";
                                            } else {
                                                echo "<p class='rood'>Niet betaald</p>";
                                            };
                                        }
                                    } catch (Exception $e) {
                                    }
                                }
                                ?>
                                <p>Bezorging: In behandeling</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php } ?><!-- Sluittag producten in bestelregel -->
                </div>
                <hr>
                <div class="flex">
                    <table id="bestellingoverzicht">
                        <tr>
                            <th>Bestellingoverzicht</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>Bestelnummer:</td>
                            <td><?php echo $orderID; ?></td>
                        </tr>
                        <tr>
                            <td>Besteldatum:</td>
                            <td><?php echo $orderDatum; ?></td>
                        </tr>
                        <tr>
                            <td>Totaalbedrag:</td>
                            <td>€ <?php echo number_format($totaalBestelling, 2, ",", ".") ?></td>
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
            </div>
            <?php } ?><!-- Sluittag bestelregels -->
        </div>
    </div>
    <br>
<?php include "components/footer.php";