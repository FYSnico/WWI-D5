<!DOCTYPE html>
<html>
<head>
    <?php
//includes doen
    include 'components/header.php';
    include 'components/ddb_connect_mysqli.php';
    include("functions.php");
    ?>
</head>
<body>
<?php
//$fakedata = array(
//    array(1, 4),
//    array(17, 1),
//    array(16, 1)
//);
//$_SESSION["shoppingcart"] = array(array(1,4));
//$_SESSION["shoppingcart"] = $fakedata;

//waardes toevoegen en eventueel de korting - johan
$total = 0;
$itemcount = 0;
$discount = 0;
if (isset($_SESSION["shopping_cart_discount"])) {
    $discount = $_SESSION["shopping_cart_discount"];
}

//product verwijderen moet nog werkend worden gemaakt maar ik weet niet hoe ik de goede session verwijderd in de array van de array komt nog
//if(isset($_POST["Remove"])) {
//    $id = $_POST["product_id"];
//    $hoeveel = $_POST["hoeveel"];
//    $shoppingcart = $_SESSION["shoppingcart"];
//
//    $shoppingcart = unset(array($id, $hoeveel));
////    for ($i = 0; $i < sizeof($shoppingcart); $i++) {
////        if ($shoppingcart[$i][0] == $id) {
////            $shoppingcart = array_diff_key($shoppingcart, [$id]);
////        }
////    }
//    $_SESSION["shoppingcart"] = $shoppingcart;
//}



?>
<div class="container border rounded shadow p-3">
    <h2>Winkelmand</h2>
    <div class="row">
        <div class="col-12 shopping-cart">
            <table class="table my-4 ">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">WWI</th>
                    <th scope="col">Product</th>
                    <th scope="col">Prijs</th>
                    <th scope="col">Aantal</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                if (isset($_SESSION["shoppingcart"])) {
// bijwerken van de aantal in winkelmand
                    if(isset($_POST["hoeveelheid"]) && isset($_POST["product_id"]) && $_POST["hoeveelheid"] > 0) {
                        $shoppingcart = $_SESSION["shoppingcart"];

                        $productIsInCartIndex = 0;
                        for ($i = 0; $i < sizeof($shoppingcart); $i++) {
                            if ($shoppingcart[$i][0] == $_POST["product_id"]) {
                                $productIsInCartIndex = $i;
                            }
                        }
                        $shoppingcart[$productIsInCartIndex][1] = $_POST["hoeveelheid"];
                        $_SESSION["shoppingcart"] = $shoppingcart;
                    }

                    foreach ($_SESSION["shoppingcart"] as $cart) {

//data ophalen van de database halen wat geselecteerd is in session
                        $count++;
                        $product_id = mysqli_real_escape_string($mysqli, $cart[0]);
                        $amount = $cart[1];
                        $result = $mysqli->query("SELECT * FROM stockitems WHERE StockItemID = {$product_id};");

                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $convertRate = convertCurrency2(1, 'USD', 'EUR');
                            $prijs =  round($row['RecommendedRetailPrice'] * $convertRate, 2);

                            $total += ($amount * $prijs);
                            $itemcount += $amount;
                            $totalprice = $prijs * $amount;
                            $StockItemName = $row['StockItemName'];

// De waardens in tabbellen zetten
                            echo <<<EOT
                                        <tr xmlns="http://www.w3.org/1999/html">
                                            <th scope="col">{$count}</th> 
                                            <td>{$StockItemName}</td>
                                            <td>&euro;{$totalprice}</td>
                                            <td>
                                                <form method="post" action="" style="display: inline">
                                                    <input type="text" name="product_id" value="{$product_id}" class="d-none">
                                                    <input type="number" name="hoeveelheid" value="$amount" class="btn btn-outline-primary text-uppercase">
                                            </td>
                                            <td>
                                                    <input type="submit" name="Bewerk" value="Bewerk" class="btn btn-primary" >
                                                </form>
                                                <form method="post" action="" style="display: inline">
                                                    <input type="text" name="product_id" value="{$product_id}" class="d-none">
                                                    <input type="hidden" name="hoeveel" value="{$amount}">
                                                    <input type="submit" name="Remove" value="Verwijder" class="btn btn-danger" style="float: right">
                                                </form>                                           
                                            </td>
                                        </tr>
EOT;
                        }
                    }
                } else {
// als je geen producten heb geselecteerd
                    echo "
                                <div class='alert alert-info' role='alert'>
                                Je hebt geen producten geselecteerd!
                                </div>";
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
// hier is de input voor je korting
        ?>
        <div class="col-9">
            <div style="display:flex;align-items:flex-end; height: 250px;">
                <form method="post" action="">
                    <div class="form-group row">
                        <div class="col-9">
                            <input type="text" name="discount_code" class="form-control">
                        </div>
                        <div class="col-3">
                            <input type="submit" name="discount" value="Kortingscode" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
// hier is krijg je een overzicht van alle kosten
        ?>
        <div class="col-3 py-4">
            <table>
                <tr>
                    <td>Prijs:</td>
                    <td>&euro;<?php echo $total ?></td>
                </tr>
                <tr>
                    <td>Verzending:</td>
<!--                    <td>&euro;--><?php
//                        $shipping = $itemcount * 0;
//                        $total += $shipping;
//                        echo $shipping;
//                        ?><!--</td>-->
                    <td><strong class="text-success h6">gratis</strong></td>
                </tr>
                <tr>
                    <td style="padding-right: 2rem;">Korting:</td>
                    <td>%<?php
                        $total -= $total * ($discount / 100);
                        echo $discount;
                        ?></td>
                </tr>
                <tr>
                    <td style="padding-right: 2rem;">Totaal (excl):</td>
                    <td>&euro;<?php
                        echo round($total, 2);
                        ?></td>
                </tr>
                <tr>
                    <td>Btw:</td>
                    <td>&euro;<?php
                        $tax = $total * 0.21;
                        $total += $tax;
                        echo round($tax, 2);
                        ?></td>
                </tr>
                <tr>
                    <td>Totaal (incl):</td>
                    <td>&euro;<?php
                        $total = round($total, 2);
                        $_SESSION["shoppingcart_price"] = $total;
                        echo $_SESSION["shoppingcart_price"];
                        ?></td>

                </tr>
            </table>
            <?php
//Geeft aan of je kunt bestelling afronden
            if (isset($_SESSION["email"]) && $total > 0) {
                echo '<a class="btn btn-primary mt-3 " href="afrekenen.php">Bestellen</a>';
            } elseif ($total != 0) {
                echo '<a class="btn btn-primary fas fa-sign-in-alt p-3 mt-2" href="login.php"> Inloggen om te bestellen</a>';

            }
            ?>
        </div>
    </div>
</div>
</body>
<footer>
    <?php include 'components/footer.php'; ?>
</footer>
</html>
