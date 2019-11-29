<?php
include('components/header.php');
include("components/config.php");
include("functions.php");

if(isset($_POST["Order"])){
    if($_POST["Order"] == "nameASC"){
        $volgorde = " StockItemName ASC";
    }
    elseif($_POST["Order"] == "nameDESC"){
        $volgorde = " StockItemName DESC";
    }
    elseif($_POST["Order"] == "priceASC"){
        $volgorde = " RecommendedRetailPrice ASC";
    }
    elseif($_POST["Order"] == "priceDESC"){
        $volgorde = " RecommendedRetailPrice DESC";
    }
    elseif($_POST["Order"] == "voorraadASC"){
        $volgorde = " LastStockTakeQuantity ASC";
    }
    elseif($_POST["Order"] == "voorraadDESC"){
        $volgorde = " LastStockTakeQuantity DESC";
    }
}
else{
    $volgorde = " StockItemName DESC";
    print "hoi";
}

$item = $_GET['id'];
$sql = "SELECT StockItemName, S.StockItemID, RecommendedRetailPrice, QuantityPerOuter, StockGroupName, LastStockTakeQuantity
                            FROM stockitems S 
                            JOIN stockitemholdings SIH
                            ON S.stockitemID = SIH.stockitemID
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            WHERE SIG.StockGroupID = $item
                            ORDER BY $volgorde
                            ";

$result = $pdo->query($sql);
$stmt2 = $pdo->prepare("SELECT StockGroupName FROM stockgroups WHERE StockGroupID = " . $item);
$stmt2->execute();
$categorienaam = $stmt2->fetch();
?>

    <div class="container">
        <div class="content">
            <h3><?php echo $categorienaam["StockGroupName"]; ?></h3>
            <?php
            //random products weergegeven
            if ($result->rowCount() > 0) {
                ?>
                <form action="" method="post">
                    <select name="Order" class="form-control">
                        <option value="nameASC">Naam A - Z</option>
                        <option value="nameDESC">Naam Z - A</option>
                        <option value="priceASC">Prijs ↑</option>
                        <option value="priceDESC">Prijs ↓</option>
                        <option value="voorraadASC">Voorraad ↑</option>
                        <option value="voorraadDESC">Voorraad ↓</option>
                    </select><br>
                    <input type="submit" value="Order" class="btn btn-primary">
                </form>
                <br>
                <div class="card-deck kaartdeck productkaartdeck">
                    <?php while ($row = $result->fetch()) { ?>
                        <div class="card w-25 kaartbreedte">
                            <a href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'><img
                                        class="card-img-top kaartimg"
                                        src="<?php echo randomPicture() ?>"
                                        alt="Productafbeelding"></a>
                            <div class="card-body">
                                <h5 class="card-title kaarttitel"><a
                                            href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'><?php echo $row['StockItemName']; ?></a>
                                </h5>
                            </div>
                            <div class="card-footer kaartfooter">
                                <p class='card-text text-primary'><a
                                            href='product.php?id="<?php echo $row['StockGroupID'] ?>"'><?php echo $row['StockGroupName'] ?></a>
                                </p>
                                <p class='card-text text-warning'><?php echo $row['LastStockTakeQuantity'] ?> op voorraad</p>
                                <p class="card-text">
                                    € <?php echo str_replace(".", ",", $row['RecommendedRetailPrice']) ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php
                unset($result);
            } else {
                echo "<div class='w-100 mt-5 pt-5'>";
                    echo " <h2 class='text-center'>Geen producten gevonden. </h2>";
                echo "</div>";
            }
            ?>
<!--            <nav aria-label="...">-->
<!--                <ul class="pagination justify-content-center">-->
<!--                    <li class="page-item disabled">-->
<!--                        <span class="page-link">Previous</span>-->
<!--                    </li>-->
<!--                    <li class="page-item"><a class="page-link" href="#">1</a></li>-->
<!--                    <li class="page-item active">-->
<!--                      <span class="page-link">-->
<!--                        2-->
<!--                        <span class="sr-only">(current)</span>-->
<!--                      </span>-->
<!--                    </li>-->
<!--                    <li class="page-item"><a class="page-link" href="#">3</a></li>-->
<!--                    <li class="page-item">-->
<!--                        <a class="page-link" href="#">Next</a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </nav>-->
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>