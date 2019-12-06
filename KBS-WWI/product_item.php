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
                $sql = "SELECT SG.StockGroupID, Barcode, IsChillerStock, Size, SearchDetails, S.StockItemID, StockItemName, RecommendedRetailPrice, LastStockTakeQuantity, StockGroupName
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
                $convertRate = @convertCurrency(1, 'USD', 'EUR');
                while ($row = $result->fetch()) {
                    echo '<aside class="col-sm-5 border-right">';
                    echo '<article class="gallery-wrap">';
                    echo '<div class="img-big-wrap">';
                    echo '<div> <a href="#"><img src="https://picsum.photos/460/600"></a></div>';
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
                    echo round($row['RecommendedRetailPrice'] * $convertRate, 2);
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

                    //product toevoegen in winkelmand - johan
                    echo <<<EOT
                    <div class="card-body pt-0 pr-5 pb-0 pl-5">
                        <form method="POST" action="" class="">
                        <input name="hoeveel" value="1" type="number" class="btn btn-lg btn-outline-primary text-uppercase">
                        <input name="id" type="text" class="d-none" value=$item>
                        <button type="submit" name="submit" value="submit" class="btn btn-lg btn-outline-primary text-uppercase"><i class="fas fa-shopping-cart"></i> Toevoegen</button></form>
                        <br>
                    </div>
EOT;

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