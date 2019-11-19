<?php
include('components/header.php');
include "components/config.php";
?>
    <div class="container">
        <div class="content">
            <div class="row justify-content-around">
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
                    print($sqlb);
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
                        while ($row = $result->fetch()) {
                            echo "<div class=' products mb-3'>";
                            echo "<div class='rand_products card shadow'>";
                            echo "<img src='" . randomPicture() . "' class='card-img-top h-50' alt=''>";
                            echo "<div class='card-body d-flex flex-column'>";
                            echo "<h5 class='card-title'>";
                            echo $row['StockItemName'];
                            echo "</h5>";
                            echo "<p class='card-title text-primary'><strong>Categorie: </strong>";
                            echo $row['StockGroupName'];
                            echo "</p>";
                            echo "<p class='card-title text-warning'><strong>Voorraad: </strong>";
                            echo $row['QuantityPerOuter'];
                            echo "</p>";
                            echo "<h5 class='card-title text-danger'>";
                            echo $row['RecommendedRetailPrice'];
                            echo "€</h5>";
                            echo "<a href='product_item.php?id=" .  $row['StockItemID'] . "' class='btn btn-primary mt-auto'>Meer informatie</a>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        unset($result);
                    } else {
                        echo "Geen producten gevonden.";
                    }

                ?>
            </div>
        </div>
    </div>

    <br><br>
<?php include('components/footer.php') ?>