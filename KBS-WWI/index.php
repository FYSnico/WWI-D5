<?php
include('components/header.php');
include("components/config.php");
include("functions.php");

// Random products genareren

$sql = "SELECT SG.StockGroupID, S.StockItemID, StockItemName, RecommendedRetailPrice, Photo, UnitPrice, QuantityPerOuter, StockGroupName, LastStockTakeQuantity 

                            FROM stockitems S 
                            JOIN stockitemholdings SIH
                            ON S.stockitemID = SIH.stockitemID
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            ORDER BY RAND()
                            LIMIT 5
                            ";
$result = $pdo->query($sql);
?>
    <div class="container">
        <div class="content">
            <h3>Ontdek onze winkel</h3>
            <br>
            <?php
            //Currency converter
            $convertRate = @convertCurrency2(1, 'USD', 'EUR');
            // Random products weergeven
            if ($result->rowCount() > 0) {

                echo "<div class=\"card-deck kaartdeck\">";
                    while ($row = $result->fetch()) { ?>
                        <div class="card w-25 kaartbreedte" style="width: 18rem;">
                            <a href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'>
                                <?php
                                if ($row['Photo']) {
                                    echo '<img class="card-img-top kaartimg" src="data:image/jpeg;base64,' . base64_encode($row['Photo']) . '"/>';
                                } else {
                                    echo '<img class="card-img-top kaartimg" src="images/default-product.png" alt="">';
                                }
                                ?>
                            </a>
                            <div class="card-body">
                                <h5 class="card-title kaarttitel"><a
                                            href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'><?php echo $row['StockItemName']; ?></a>
                                </h5>
                            </div>
                            <div class="card-footer kaartfooter">
                                <p class='card-text text-primary'><a
                                            href='product.php?id="<?php echo $row['StockGroupID'] ?>"'><?php echo $row['StockGroupName'] ?></a>
                                </p>
                                <p class='card-text text-warning'><?php echo $row['LastStockTakeQuantity'] ?> stocks op voorraad</p>
                                <p class="card-text">
                                    €<?php $UnitPrice = $row['UnitPrice'] * $convertRate;
                                echo number_format($UnitPrice,2,",",".") ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php
                unset($result);
            } else {
                echo "Geen producten gevonden.";
            }
            ?>
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>