<?php
include('components/header.php');
include("components/config.php");
include("functions.php");
if(isset($_POST["Order"])) {
    $_SESSION["Order"] = $_POST["Order"];
}

//Checken of er een sortering is toegepast, die dan weer herbruiken
if (isset($_SESSION["Order"])) {
    if ($_SESSION["Order"] == "nameASC") {
        $volgorde = " StockItemName ASC";
        $dropdown = "Naam A - Z";
    } elseif ($_SESSION["Order"] == "nameDESC") {
        $volgorde = " StockItemName DESC";
        $dropdown = "Naam Z - A";
    } elseif ($_SESSION["Order"] == "priceASC") {
        $volgorde = " UnitPrice ASC";
        $dropdown = "Prijs ↑";
    } elseif ($_SESSION["Order"] == "priceDESC") {
        $volgorde = " UnitPrice DESC";
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

//Escapen van problematieke tekens
$_GET["query"] = str_replace('&','\&', $_GET["query"]);
$_GET["query"] = str_replace('\'','\'\'', $_GET["query"]);
$_GET["query"] = str_replace('%','\%', $_GET["query"]);
$_GET["query"] = str_replace('_','\_', $_GET["query"]);
$_GET["query"] = str_replace('\\','\\\\', $_GET["query"]);

$zoekterm = "";

if (isset($_GET['p'])) {
    $huidigepagina = $_GET['p'];
} else {
    $huidigepagina = 1;
}

// Controleren of invoer leeg is
if (empty($_GET["query"])) {
    echo "<div class='w-100 mt-5 pt-5'><h2 class='text-center'>Niks ingevoerd. </h2></div>";
    die();
// Artikelcode zoeken als er alleen een nummer is ingevoerd
} elseif (is_numeric($_GET["query"])) {
    $int = $_GET["query"];
    print($int);
    //Query wordt uitgevoerd
    $sql = "SELECT StockItemName, UnitPrice, QuantityPerOuter, Photo, StockGroupName, S.StockItemID, SIH.LastStockTakeQuantity 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            JOIN stockitemholdings SIH
                            ON S.stockitemID = SIH.stockitemID
                            WHERE S.StockItemID = $int
                            GROUP BY S.StockItemID
                            ORDER BY $volgorde";
//Query aanmaken door gehele tekst van invoer te specialiseren en door daarna ieder woord uit elkaar te trekken en weer samenvoegen met OR's
} else {
    //Gehele tekst
    $eerste = $_GET["query"];
    //Uitelkaar trekken van invoer
    $query_array = explode(' ', $_GET["query"]);
    //Gehele tekst krijgt definitie van de productnaam in de database ervoor met sql syntax
    $sqla[0] = "S.StockItemName = '$eerste'";
    //Alle woorden krijgen definitie van de productnaam in de database ervoor met sql syntax
    foreach ($query_array as $word) {
        $sqla[] = "S.SearchDetails LIKE '%$word%'";
    }
    //Array van sql compatibele syntax woorden wordt samengevoegd
    $zoekterm = implode(" OR ", $sqla);
    //Query wordt uitgevoerd
    $sql = "SELECT StockItemName, UnitPrice, QuantityPerOuter, Photo, StockGroupName, S.StockItemID, SIH.LastStockTakeQuantity 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG   
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            JOIN stockitemholdings SIH
                            ON S.stockitemID = SIH.stockitemID
                            WHERE $zoekterm
                            ORDER BY (CASE WHEN StockItemName = '$eerste' THEN 0 ELSE 1 END),$volgorde";
}
$result = $pdo->query($sql);
?>
    <div class="container">
        <div class="content">
            <h3>Zoekresultaten</h3>
            <br>
            <?php
            // Currency converter
            $convertRate = @convertCurrency2(1, 'USD', 'EUR');
            // Kijk of er producten in de tabel staan
            if ($result->rowCount() > 0) { ?>
                <!--Filteren producten-->
                <form action="" method="post">
                    <select name="Order" class="form-control"><?php
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
                            <div class="card w-25 kaartbreedte" style="width: 18rem;">
                                <a href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'>
                                    <?php
                                    if ($row['Photo']){
                                        echo '<img class="card-img-top kaartimg"  src="data:image/jpeg;base64,'.base64_encode( $row['Photo'] ).'"/>';
                                    }else{
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
                                    <p class='card-text text-warning'><?php echo $row['LastStockTakeQuantity'] ?> op
                                        voorraad</p>
                                    <p class="card-text">
                                        €<?php $UnitPrice = $row['UnitPrice'] * $convertRate;
                                        echo number_format($UnitPrice,2,",",".") ?></p>
                                </div>
                            </div>
                            <?php
                            $productnummer++;
                        }
                    }
                    ?>
                </div>
                <!-- Paginanavigatie -->
                <?php if ($zoekterm != "") { ?>
                    <nav aria-label="...">
                        <ul class="pagination justify-content-center">
                            <?php
                            $paginanummer = 1;
                            $vorigePaginaNummer = $huidigepagina - 1;
                            $volgendePaginaNummer = $huidigepagina + 1;
                            if ($huidigepagina == 1) {
                                print("<li class=\"page-item disabled\"><span class=\"page-link\">Vorige</span></li>");
                            } else {
                                print("<li class=\"page-item\"><a class='page-link' href='search.php?query=$eerste&p=$vorigePaginaNummer'>Vorige</a></li>");
                            }
                            while ($paginanummer <= ceil($result->rowCount() / 12)) {
                                if ($huidigepagina == $paginanummer) {
                                    print("<li class=\"page-item active\"><span class=\"page-link\">$paginanummer<span class=\"sr-only\">(current)</span></span></li>");
                                } else {
                                    print("<li class=\"page-item\"><a class='page-link' href='search.php?query=$eerste&p=$paginanummer'>$paginanummer</a></li>");
                                }
                                $paginanummer++;
                            }
                            if ($huidigepagina == $paginanummer - 1) {
                                print("<li class=\"page-item disabled\"><span class=\"page-link\">Volgende</span></li>");
                            } else {
                                print("<li class=\"page-item\"><a class=\"page-link\" href='search.php?query=$eerste&p=$volgendePaginaNummer'>Volgende</a></li>");
                            }
                            ?>
                        </ul>
                    </nav>
                    <?php
                }
                unset($result);
            } else {
                echo "<div class='w-100 mt-5 pt-5'><h2 class='text-center'>Geen producten gevonden. </h2></div>";
            }
            ?>
        </div>
    </div>
    <br><br>
<?php include('components/footer.php') ?>