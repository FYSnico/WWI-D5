<?php
include('components/header.php');
include("components/config.php");
include("functions.php");
if(isset($_POST["Order"])) {
    $_SESSION["Order"] = $_POST["Order"];
}
//systeem kijkt welke product volgorde is geselecteerd - johan
if (isset($_SESSION["Order"])) {
    if ($_SESSION["Order"] == "nameASC") {
        $volgorde = " StockItemName ASC";
        $dropdown = "Naam A - Z";
    } elseif ($_SESSION["Order"] == "nameDESC") {
        $volgorde = " StockItemName DESC";
        $dropdown = "Naam Z - A";
    } elseif ($_SESSION["Order"] == "priceASC") {
        $volgorde = " RecommendedRetailPrice ASC";
        $dropdown = "Prijs ↑";
    } elseif ($_SESSION["Order"] == "priceDESC") {
        $volgorde = " RecommendedRetailPrice DESC";
        $dropdown = "Prijs ↓";
    } elseif ($_SESSION["Order"] == "voorraadASC") {
        $volgorde = " LastStockTakeQuantity ASC";
        $dropdown = "Voorraad ↑";
    } elseif ($_SESSION["Order"] == "voorraadDESC") {
        $volgorde = " LastStockTakeQuantity DESC";
        $dropdown = "Voorraad ↓";
    }
} else {
    $volgorde = " StockItemName ASC";
}

$item = $_GET['id'];
if (isset($_GET['p'])) {
    $huidigepagina = $_GET['p'];
} else {
    $huidigepagina = 1;
}
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
            <br>
            <?php
            // Currency converter
            $convertRate = @convertCurrency(1, 'USD', 'EUR');
            // Kijk of er producten in de tabel staan
            if ($result->rowCount() > 0) {
                ?>
                <!--Filteren producten-->
                <form action="" method="post">
                    <select name="Order" class="form-control">
                        <?php
                        if($_SESSION["Order"] != NULL){echo "<option value=" . $_SESSION["Order"] . ">" . $dropdown . "</option>";}
                        if($_SESSION["Order"] != "nameASC"){echo "<option value=\"nameASC\">Naam A - Z</option>";}
                        if($_SESSION["Order"] != "nameDESC"){echo "<option value=\"nameDESC\">Naam Z - A</option>";}
                        if($_SESSION["Order"] != "priceASC"){echo "<option value=\"priceASC\">Prijs ↑</option>";}
                        if($_SESSION["Order"] != "priceDESC"){echo "<option value=\"priceDESC\">Prijs ↓</option>";}
                        if($_SESSION["Order"] != "voorraadASC"){echo "<option value=\"voorraadASC\">Voorraad ↑</option>";}
                        if($_SESSION["Order"] != "voorraadDESC"){echo "<option value=\"voorraadDESC\">Voorraad ↓</option>";}
                        ?>
                    </select>
                    <br>
                    <input type="submit" value="Sorteren" class="btn btn-primary">
                </form>
                <br>
                <div class="card-deck kaartdeck productkaartdeck">
                    <?php
                    // Producten weergeven
                    $productnummer = 1;
                    $productoffset = 1;
                    while (($row = $result->fetch()) && $productnummer <= 12) {
                        if ($productoffset <= (12 * $huidigepagina) - 12) {
                            $productoffset++;
                        } else {
                            ?>
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
                                    <p class='card-text text-warning'><?php echo $row['LastStockTakeQuantity'] ?> op
                                        voorraad</p>
                                    <p class="card-text">
                                        € <?php echo round($row['RecommendedRetailPrice'] * $convertRate, 2) ?></p>
                                </div>
                            </div>
                            <?php
                            $productnummer++;
                        }
                    }
                    ?>
                </div>
                <!-- Paginanavigatie -->
                <nav aria-label="...">
                    <ul class="pagination justify-content-center">
                        <?php
                        $paginanummer = 1;
                        $vorigePaginaNummer = $huidigepagina -1;
                        $volgendePaginaNummer = $huidigepagina + 1;
                        if ($huidigepagina == 1) {
                            print("<li class=\"page-item disabled\"><span class=\"page-link\">Vorige</span></li>");
                        } else {
                            print("<li class=\"page-item\"><a class='page-link' href='product.php?id=$item&p=$vorigePaginaNummer'>Vorige</a></li>");
                        }
                        while ($paginanummer <= ceil($result->rowCount() / 12)) {
                            if($huidigepagina == $paginanummer){
                                print("<li class=\"page-item active\"><span class=\"page-link\">$paginanummer<span class=\"sr-only\">(current)</span></span></li>");
                            } else {
                                print("<li class=\"page-item\"><a class='page-link' href='product.php?id=$item&p=$paginanummer'>$paginanummer</a></li>");
                            }
                            $paginanummer++;
                        }
                        if ($huidigepagina == $paginanummer - 1){
                            print("<li class=\"page-item disabled\"><span class=\"page-link\">Volgende</span></li>");
                        } else {
                            print("<li class=\"page-item\"><a class=\"page-link\" href='product.php?id=$item&p=$volgendePaginaNummer'>Volgende</a></li>");
                        }
                        ?>
                    </ul>
                </nav>
                <?php
                unset($result);
            } else {
                echo "<div class='w-100 mt-5 pt-5'><h2 class='text-center'>Geen producten gevonden. </h2></div>";
            }
            ?>
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>