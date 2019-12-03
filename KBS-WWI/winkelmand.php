<!DOCTYPE html>
<html>
<head>
    <?php include 'components/header.php';
    include 'components/ddb_connect_mysqli.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
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
//    $shoppingcart = $_SESSION["shoppingcart"];
//
//    for ($i = 0; $i < sizeof($shoppingcart); $i++) {
//        if ($shoppingcart[$i][0] == $id) {
//            unset($shoppingcart[$i][1]);
//            unset($shoppingcart[$i]);
//        }
//    }
//    $_SESSION["shoppingcart"] = $shoppingcart;
//}

?>
<div class="container">
    <div class="row">
        <div class="col-12 shopping-cart">
            <table class="table my-4 ">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">WWI</th>
                    <th scope="col">Product</th>
                    <th scope="col">Beschrijving</th>
                    <th scope="col">Prijs</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                if (isset($_SESSION["shoppingcart"])) {
                    foreach ($_SESSION["shoppingcart"] as $cart) {

                        //data ophalen van de database
                        $count++;
                        $product_id = mysqli_real_escape_string($mysqli, $cart[0]);
                        $amount = $cart[1];
                        $result = $mysqli->query("SELECT * FROM stockitems WHERE StockItemID = {$product_id};");


                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $description = substr($row['MarketingComments'], 0, 64);
                            $total += ($amount * $row['RecommendedRetailPrice']);
                            $itemcount += $amount;

                            echo <<<EOT
                                        <tr>
                                            <th scope="col">{$count}</th> 
                                            <td>{$row['StockItemName']}</td>
                                            <td>{$description}</td>
                                            <td>{$row['RecommendedRetailPrice']}</td>
                                            <td>
                                                <form method="post" action="">
                                                    <input type="text" name="product_id" value="{$product_id}" class="d-none">
                                                    <input type="submit" name="Remove" value="Verwijder" class="btn btn-danger" style="float: right;">
                                                </form>
                                            </td>
                                        </tr>
EOT;

                        }
                    }
                } else {
                    echo "
                                <div class='alert alert-info' role='alert'>
                                Je hebt geen producten geselecteerd!
                                </div>";
                }
                ?>
                </tbody>
            </table>
            <table class="table mt-4 table-sm">
                <tbody>
                <?php
                if (isset($_SESSION["shoppingcart"])) {
                    foreach ($_SESSION["shoppingcart"] as $cart) {
                        $product_id = mysqli_real_escape_string($mysqli, $cart[0]);
                        $amount = $cart[1];

                        $result = $mysqli->query("SELECT * FROM stockitems WHERE StockItemID = {$product_id};");

                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalprice = $row['RecommendedRetailPrice'] * $amount;

                            echo <<<EOT
                                        <tr>
                                            <td>{$row['StockItemName']}</td>
                                            <td>
                                                <div style="width: 50%; float: left;">
                                                    <p style="float: right; margin-right: 2rem;">Aantal: {$amount}</p>
                                                </div>
                                                <div style="width: 50%; float: left;">
                                                    <form method="post" action="">
                                                        <input type="text" name="product_id" value="{$product_id}" class="d-none">
                                                        <button type="submit" name="+" value="+" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle" data-fa-transform="grow-2"></i></button>
                                                        <button type="submit" name="-" value="-" class="btn btn-primary btn-sm"><i class="fas fa-minus-circle" data-fa-transform="grow-2"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td style="text-align: right;">Prijs: &euro;{$totalprice}</td>
                                        </tr>
EOT;

                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-9">
            <div style="display:flex;align-items:flex-end; height: 250px;">
                <form method="post" action="">
                    <div class="form-group row">
                        <div class="col-9">
                            <input type="text" name="discount_code" class="form-control">
                        </div>
                        <div class="col-3">
                            <input type="submit" name="discount" value="Use Discount" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-3 py-4">
            <table>
                <tr>
                    <td>Prijs:</td>
                    <td>&euro;<?php echo $total ?></td>
                </tr>
                <tr>
                    <td>Verzending:</td>
                    <td>&euro;<?php
                        $shipping = $itemcount * 0;
                        $total += $shipping;
                        echo $shipping;
                        ?></td>
                </tr>
                <tr>
                    <td style="padding-right: 2rem;">Korting:</td>
                    <td>%<?php
                        $total -= $total * ($discount / 100);
                        echo $discount;
                        ?></td>
                </tr>
                <tr>
                    <td style="padding-right: 2rem;">Totaal voor BTW:</td>
                    <td>&euro;<?php
                        echo round($total, 2);
                        ?></td>
                </tr>
                <tr>
                    <td>BTW:</td>
                    <td>&euro;<?php
                        // TODO: ADD TAX DEPENDENT ON CATEGORY
                        $tax = $total * 0.21;
                        $total += $tax;
                        echo round($tax, 2);
                        ?></td>
                </tr>
                <tr>
                    <td>Totaal</td>
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
                echo '<li>';
                echo '<a class="btn btn-primary mt-3" href="afrekenen.php">Bestellen</a></i>';
            } elseif ($total != 0) {
                echo '<a class="fas fa-sign-in-alt" href="login.php">Login in om te bestellen</a>';
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
