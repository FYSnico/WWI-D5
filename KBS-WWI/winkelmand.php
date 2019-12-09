
<?php
//includes doen
include 'components/header.php';
include 'components/ddb_connect_mysqli.php';
include("functions.php");
?>
<body>
<?php
//$fakedata = array(
//    array(1, 4),
//    array(17, 1),
//    array(16, 1)
//);
//$_SESSION["shoppingcart"] = array(array(1,4));
//$_SESSION["shoppingcart"] = $fakedata;
//$_SESSION["shoppingcart"] = $array;
//print_r(array_values($_SESSION["shoppingcart"]));

//waardes toevoegen en eventueel de korting - johan
$total = 0;
$itemcount = 0;
$discount = 0;
if (isset($_SESSION["shopping_cart_discount"])) {
    $discount = $_SESSION["shopping_cart_discount"];
}

//product verwijderen moet nog werkend worden gemaakt maar ik weet niet hoe ik de goede session verwijderd in de array van de array komt nog
if(isset($_POST["Remove"])) {
    $id = $_POST["product_id"];
    $shoppingcart = $_SESSION["shoppingcart"];

    for ($i = 0; $i < sizeof($shoppingcart); $i++) {
        if ($shoppingcart[$i][0] == $id) {
            unset($shoppingcart[$i]);
        }
    }
    $_SESSION["shoppingcart"] = array_values($shoppingcart);
}


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
                <?php
                $count = 0;
                if (isset($_SESSION["shoppingcart"])) {
                    if (isset($_SESSION["shoppingcart"][0][0])) {

                        // bijwerken van de aantal in winkelmand
                        if (isset($_POST["hoeveelheid"]) && isset($_POST["product_id"]) && $_POST["hoeveelheid"] > 0) {
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

//                    if (isset($_POST["hoeveelheid"]) && isset($_POST["product_id"])) {
////                        $result = $mysqli->query("SELECT LastStockTakeQuantity FROM stockitemholdings WHERE id = {$_POST["product_id"]};");
////                        if (mysqli_num_rows($result) > 0) {
////                            $row = mysqli_fetch_assoc($result);
//// < $rowT['LastStockTakeQuantity']
//                            if ($_POST["hoeveelheid"] > 0 && $_POST["hoeveelheid"] ) {
//                                $shoppingcart = $_SESSION["shoppingcart"];
//
//                                $productIsInCartIndex = 0;
//                                for ($i = 0; $i < sizeof($shoppingcart); $i++) {
//                                    if ($shoppingcart[$i][0] == $_POST["product_id"]) {
//                                        $productIsInCartIndex = $i;
//                                    }
//                                }
//                                $shoppingcart[$productIsInCartIndex][1] = $_POST["hoeveelheid"];
//                                $_SESSION["shoppingcart"] = $shoppingcart;
//                            }
//                        }
//                    }


                        //data ophalen van de database halen wat geselecteerd is in session
                        foreach ($_SESSION["shoppingcart"] as $cart) {
                            $count++;
                            $product_id = mysqli_real_escape_string($mysqli, $cart[0]);
                            $amount = $cart[1];
                            $result = $mysqli->query("SELECT * FROM stockitems WHERE StockItemID = {$product_id};");

                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $convertRate = @convertCurrency2(1, 'USD', 'EUR');
                            $prijs =  round(($row['UnitPrice'] * $convertRate), 2);


                                $total += ($amount * $prijs);
                                $itemcount += $amount;
                                $totalprice = $prijs * $amount;
                                $totalprice = number_format($totalprice,2,",",".");
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
                                <div class='alert alert-info'  role='alert'>
                                Je hebt geen producten geselecteerd!
                                </div>";
                    }
                } else {
                    echo "
                                <div class='alert alert-info' role='alert'>
                                Je hebt geen producten geselecteerd!
                                </div>";
                    }

                ?>

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
                    <td><?php
                        $total -= $total * ($discount / 100);
                        echo $discount;
                        ?>%</td>
                </tr>
                <tr>
                    <td style="padding-right: 2rem;">Prijs (excl):</td>
                    <td>&euro;<?php
                        $tax = $total * 0.21;
                        $totalexcl = $total-$tax;
                        echo number_format($totalexcl,2,",",".");
                        ?></td>
                </tr>
                <tr>
                    <td>Btw:</td>
                    <td>&euro;<?php
                        echo number_format($tax,2,",",".");
                        ?></td>
                </tr>
                <tr>
                    <td><strong>Totaalprijs (incl):</strong></td>
                    <td>&euro;<?php
                        $total = number_format($total,2,",",".");
                        $_SESSION["shoppingcart_price"] = $total;
                        echo $_SESSION["shoppingcart_price"];
                        ?></td>

                </tr>
            </table>
            <?php
// Geeft aan of je kunt bestelling afronden
            if (isset($_SESSION["email"]) && $total > 0) {
                echo '<a class="btn btn-primary mt-3 " href="examples/payments/create-payment.php">Bestellen</a>';
            } elseif ($total != 0) {
                echo '<a class="btn btn-primary fas fa-sign-in-alt p-3 mt-2" href="login.php"> Inloggen om te bestellen</a>';

            }
            ?>
        </div>
    </div>
</div>
</body>
    <?php include 'components/footer.php'; ?>

</html>
