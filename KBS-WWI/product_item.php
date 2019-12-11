<?php
include('components/header.php');
include("components/config.php");
include("functions.php");
?>

<div class="container">
    <div class="card shadow">
        <div class="row">
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $item = $_GET['id'];
            $sql = "SELECT SG.StockGroupID, Barcode, IsChillerStock, Size, Photo, UnitPrice, SearchDetails, S.StockItemID, StockItemName, RecommendedRetailPrice, LastStockTakeQuantity, StockGroupName

                    FROM stockitems S 
                    JOIN stockitemholdings SIH
                    ON S.stockitemID = SIH.stockitemID
                    JOIN stockitemstockgroups SIG 
                    ON S.StockitemID = SIG.StockitemID
                    JOIN stockgroups SG
                    ON SIG.StockGroupID = SG.StockGroupID
                    WHERE SIG.StockItemID = $item  
                    ";
            $result = $pdo->query($sql);
            $convertRate = @convertCurrency2(1, 'USD', 'EUR');
            while ($row = $result->fetch()) {
                echo '<aside class="col-sm-5 border-right pr-0">';
                    echo '<article class="gallery-wrap">';
                        echo '<div class="img-big-wrap">';
                            echo '<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">';
                                echo '<ol class="carousel-indicators">';
//                                    echo '<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>';
                                echo '</ol>';
                                echo '<div class="carousel-inner">';
                                    echo '<div class="carousel-item active">';
                                        if ($row['Photo']){
                                            echo '<img class="product-img-size" src="data:image/jpeg;base64,'.base64_encode( $row['Photo'] ).'"/>';
                                        }else{
                                            echo '<img class="product-img-size" src="images/default-product.png" alt="">';
                                        }
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</article>';
                echo '</aside>';
                echo '<aside class="col-sm-7 pb-3">';
                    echo '<article class="card-body p-5">';
                        echo '<h3 class="title mb-3">';
                            echo $row['StockItemName'];
                        echo '</h3>';
                        echo '<p class="price-detail-wrap">';
                            echo '<dl class="param param-inline">';
                                echo '<dt>';
                                    $result = $pdo->query($sql);
                                    while ($categories = $result->fetch()) {
                                        echo "<a href='product.php?id=" . $categories['StockGroupID'] . "'> ";
                                        echo $categories['StockGroupName'] . " ";
                                        echo '</a>';
                                    }
                                echo '</dt>';
                            echo '</dl>';
                            echo '<span class="price h3 text-warning">';
                                echo '<span class="currency">€</span><span class="num">';
                                    $UnitPrice = $row['UnitPrice'] * $convertRate;
                                    echo number_format($UnitPrice,2,",",".");
                                echo '</span>';
                            echo '</span>';
                        echo '</p>';
                        echo '<span class="">';
                            echo '<span class=""><strong>Omschrijving: </strong>';
                                echo '<br>';
                                echo $row['SearchDetails'];
                            echo '</span>';
                        echo '</span>';
                    echo '</article>';
                    echo '<hr>';
                    echo '<div class="card-body pt-0 pr-5 pb-0 pl-5">';
                        echo '<div class="">';
                            echo '<dl class="param param-inline">';
                                echo '<dt> Voorraad: ';
                                    echo $row['LastStockTakeQuantity'];
                                echo '</dt>';
                            echo '</dl>';
                            echo '<dl class="param param-inline">';
                                echo '<dt> Gekoeld: ';
                                    if ($row['IsChillerStock'] == 1){
                                        echo "Ja";
                                    }else{
                                        echo "Nee";
                                    }
                                echo '</dt>';
                            echo '</dl>';
                            echo '<dl class="param param-inline">';
                                if ($row['Size']){
                                    echo '<dt> Grootte: ';
                                    echo $row['Size'];
                                    echo '</dt>';
                                }
                            echo '</dl>';
                        echo '</div>';
                    echo '</div>';
                    echo '<hr>';
                    //Afbeelding toevoegen
                    if (isset($_SESSION["IsSystemUser"]) && $_SESSION["IsSystemUser"] == 1) {
                        echo '<form method="POST" action="product_item.php?id=' . $row['StockItemID'] . '"enctype="multipart/form-data">';
                        echo '<input type="file" name="myimage">';
                        echo '<input type="submit" name="submitImage" value="Uploaden">';
                        echo '</form>';
                        echo '<br>';
                    }
                    if(isset($_POST['submitImage'])) {
                        $id = $row['StockItemID'];
                        if(!empty($_FILES['myimage']['tmp_name'])
                            && file_exists($_FILES['myimage']['tmp_name'])) {
                            $imagename = $_FILES["myimage"]["name"];
                            $imagetmp = addslashes(file_get_contents($_FILES['myimage']['tmp_name']));
                            $sql = "UPDATE stockitems SET Photo = '$imagetmp' WHERE StockItemID = $id";
                            $insert_image = $pdo->query($sql);

                            if( $insert_image) {
                                echo "Afbeelding uploaded";
                                echo '<br>';
                            }
                            $secondsWait = 0;
                            echo '<meta http-equiv="refresh" content="'.$secondsWait.'">';
                        }else{
                            echo "Er moet een bestand gekozen worden";
                            echo '<br>';
                        }

                    }
                    //product toevoegen in winkelmand - johan
                    echo '<div class="card-body pt-0 pr-5 pb-0 pl-0">';
                        echo '<form method="POST" action="" class="">';
                            echo '<input name="hoeveel" value="1" type="number" class="btn btn-lg btn-outline-primary text-uppercase">';
                            echo '<input name="id" type="text" class="d-none" value=$item>';
                            echo '<button type="submit" name="submit" value="submit" class="btn btn-lg btn-outline-primary text-uppercase"><i class="fas fa-shopping-cart"></i> Toevoegen</button></form>';
                            echo '<br>';
                    echo '</div>';

                    $lastStockTakeQuantity = $row['LastStockTakeQuantity'];
                    $productmagwordentoegevoegd = false;
                    //controleren of getal is ingevoerd
                    if (isset($_POST["submit"])) {
                        if ($_POST["hoeveel"] > 0 && $lastStockTakeQuantity >= $_POST["hoeveel"]) {
                            echo '<a class="alert alert-success"><strong>✓</strong> Toegevoegd</a>';
                            $productmagwordentoegevoegd = true;
                        } elseif (isset($_POST["submit"]) && $_POST["hoeveel"] <= 0) {
                            echo '<a class="alert alert-warning"><strong>!</strong> Aantal graag hoger dan 0.</a>';
                        } elseif ($row['LastStockTakeQuantity'] < $_POST["hoeveel"]) {
                            echo '<a class="alert alert-warning"><strong>!</strong> Aantal te hoog.</a>';
                        }
                    }
                echo '</aside>';
            }
            //submit is gedrukt
            if (isset($_POST["submit"])) {
                if ($_POST["hoeveel"] > 0 && $productmagwordentoegevoegd) {
                    //"" verwijderen dit komt door number type en hoeveel ophalen
                    $id = trim($item, "\"\"");
                    $hoeveel = $_POST["hoeveel"];

                    // starten session shoppincart
                    if(!isset($_SESSION["shoppingcart"])){
                        $_SESSION["shoppingcart"] = array();
                    }
                    $shoppingcart = $_SESSION["shoppingcart"];
                    $productIsInCart = false;
                    $productIsInCartIndex = 0;

                    //kijken of product in de shopping car zit
                    for ($i = 0; $i < sizeof($shoppingcart); $i++) {
                        if ($shoppingcart[$i][0] == $id) {
                            $productIsInCart = true;
                            $productIsInCartIndex = $i;
                        }
                    }
                    // als product al in shopping car zit word de hoeveelheid toegevoegd en anders een nieuwe array in de array $_SESSION["shoppingcart"] toevoegen
                    if ($productIsInCart) {
                        $shoppingcart[$productIsInCartIndex][1] += $hoeveel;
                    } else {
                        $shoppingcart[] = array($id, $hoeveel);
                    }
                    // in session zetten
                    $_SESSION["shoppingcart"] = $shoppingcart;
                }
            }
            ?>
        </div>
    </div>
</div>
<br><br>
<?php include('components/footer.php'); ?>