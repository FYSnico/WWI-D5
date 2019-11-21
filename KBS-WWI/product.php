<?php 
include('components/header.php');
include("components/config.php");
?>
    <div class="container">
        <div class="content">
            <div class="row justify-content-around">
                <?php
                    $img = 'https://picsum.photos/200/300';

                    $item = $_GET['id'];
                    $sql = "SELECT StockItemName, S.StockItemID, RecommendedRetailPrice, QuantityPerOuter, StockGroupName 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            WHERE SIG.StockGroupID = $item  
                            ";
                    $result = $pdo->query($sql);
                //product soteren (moet nog gemaakt worden dit is mijn code, johan)
                //$result = $pdo->query($sql);
                /*            <form action="<?php echo($_SERVER["PHP_SELF"]);?>" method="get">*/
                //                <select name="Order" class="form-control">
                //                    <option value="createdDESC">Upload date</option>
                //                    <option value="priceASC">Price low - high</option>
                //                    <option value="priceDESC">Price high - low</option>
                //                    <option value="nameASC">Title A - Z</option>
                //                    <option value="nameDESC">Title Z - A</option>
                //                </select><br>
                //                <input type="submit" value="Order" class="btn btn-primary">
                //            </form>
                //        </div>
                //        <?php
                //        if(isset($_GET["Order"])){
                //            if($_GET["Order"] == "priceASC"){
                //                $result = $mysqli->query("SELECT * FROM products ORDER BY price ASC;");
                //            }
                //            elseif($_GET["Order"] == "priceDESC"){
                //                $result = $mysqli->query("SELECT * FROM products ORDER BY price DESC;");
                //            }
                //            elseif($_GET["Order"] == "nameASC"){
                //                $result = $mysqli->query("SELECT * FROM products ORDER BY name ASC;");
                //            }
                //            elseif($_GET["Order"] == "nameDESC"){
                //                $result = $mysqli->query("SELECT * FROM products ORDER BY name DESC;");
                //            }
                //            else{
                //                $result = $mysqli->query("SELECT * FROM products ORDER BY created_at DESC;");
                //            }
                //        }
                //        else{
                //            $result = $mysqli->query("SELECT * FROM products ORDER BY created_at DESC;");
                //        }

                    //random products weergegeven
                    if($result->rowCount() > 0){
                        while($row = $result->fetch()){
                            echo "<div class=' products mb-3'>";
                                echo "<div class='rand_products card shadow'>";
                                    echo "<img src='$img' class='card-img-top h-50' alt=''>";
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
                    } else{
                        echo "Geen producten gevonden.";
                    }
                ?>
            </div>
        </div>
    </div>

    <br><br>
<?php include('components/footer.php') ?>