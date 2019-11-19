<?php
include('components/header.php');
include("components/config.php");
include("functions.php");
?>
    <div class="container">
        <div class="content">
            <h3>Ontdek onze winkel</h3>
            <br>
            <?php
            //random products genareren
            $sql = "SELECT SG.StockGroupID, S.StockItemID, StockItemName, RecommendedRetailPrice, QuantityPerOuter, StockGroupName 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            ORDER BY RAND()
                            LIMIT 5
                            ";
            $result = $pdo->query($sql);
            //random products weergegeven
            if ($result->rowCount() > 0) {
                ?>
                <div class="card-deck kaartdeck">
                    <?php while ($row = $result->fetch()) { ?>
                        <div class="card w-25 kaartbreedte" style="width: 18rem;">
                            <a href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'><img class="card-img-top kaartimg" src="<?php echo randomPicture() ?>" alt="Productafbeelding"></a>
                            <div class="card-body">
                                <h5 class="card-title kaarttitel"><a
                                            href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'><?php echo $row['StockItemName']; ?></a>
                                </h5>
                            </div>
                            <div class="card-footer kaartfooter">
                                <p class='card-text text-primary'><a
                                            href='product.php?id="<?php echo $row['StockGroupID'] ?>"'><?php echo $row['StockGroupName'] ?></a>
                                </p>
                                <p class='card-text text-warning'><?php echo $row['QuantityPerOuter'] ?> op voorraad</p>
                                <p class="card-text">
                                    € <?php echo str_replace(".", ",", $row['RecommendedRetailPrice']) ?></p>
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