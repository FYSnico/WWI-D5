<?php
include('components/header.php');
include("components/config.php");
include("functions.php");


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
$stmt2 = $pdo->prepare("SELECT StockGroupName FROM stockgroups WHERE StockGroupID = " . $item);
$stmt2->execute();
$categorienaam = $stmt2->fetch();
?>
    <div class="container">
        <div class="content">
            <h3><?php echo $categorienaam["StockGroupName"]; ?></h3>
            <?php

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
            if ($result->rowCount() > 0) {
                ?>
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