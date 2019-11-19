<?php
include('components/header.php');
include("components/config.php");
include("functions.php");
?>
    <div class="container">
        <div class="content">
            <h3>Zoekresultaten</h3>
            <br>
                <?php
                //$test = "usb cube";
                if (empty($_GET["query"])) {
                    print("Niks ingevoerd!");
                    die();
                } elseif(is_numeric($_GET["query"])){
                    $int = $_GET["query"];
                    $sql = "SELECT StockItemName, RecommendedRetailPrice, QuantityPerOuter, StockGroupName, S.StockItemID 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            WHERE S.StockItemID = $int
                            GROUP BY S.StockItemID";
                }else {
                    $eerste = $_GET["query"];
                    $query_array = explode(' ', $_GET["query"]);
                    //print_r($query_array);
                    //$sqla = array('0'); // Stop errors when $words is empty
                    $sqla[0] = "S.SearchDetails LIKE '%$eerste%'";
                    foreach ($query_array as $word) {
                        $sqla[] = "S.SearchDetails LIKE '%$word%'";
                    }
                    $sqlb = implode(" OR ", $sqla);
//                    print($sqlb);
                    $sql = "SELECT StockItemName, RecommendedRetailPrice, QuantityPerOuter, StockGroupName, S.StockItemID 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            WHERE $sqlb";
                }
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