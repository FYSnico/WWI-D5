<?php   include 'components/header.php';
        include 'components/config.php';
                                            ?>

<?php

//$relative_root = get_relative_root();
$total = 0;
$itemcount = 0;
$discount = 0;
if(isset($_SESSION["shopping_cart_discount"])) {
    $discount = $_SESSION["shopping_cart_discount"];
}
?>

<div class="container">
    <div class="row">
        <div class="col-12 shopping-cart">
            <table class="table my-4 ">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
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
                    foreach($_SESSION["shoppingcart"] as $cart) {
                        $count++;
                        $product_id = mysqli_real_escape_string($mysqli, $cart[0]);
                        $amount = $cart[1];

                        $result = $mysqli->query("SELECT * FROM products WHERE id = {$product_id};");

                        if ($result && mysqli_num_rows($result) > 0){
                            $row = mysqli_fetch_assoc($result);
                            $description = substr($row['description'], 0, 64);
                            $total += ($amount * $row['price']);
                            $itemcount += $amount;

                            echo <<<EOT
                                    <tr>
                                        <th scope="col">{$count}</th>
                                        <td>{$row['name']}</td>
                                        <td>{$description}</td>
                                        <td>{$row['price']}</td>
                                        <td>
                                            <form method="post" action="{$relative_root}/logic/shopping_cart/cart_update_product.php">
                                                <input type="text" name="product_id" value="{$product_id}" class="d-none">
                                                <input type="submit" name="Remove" value="Remove" class="btn btn-danger" style="float: right;">
                                            </form>
                                        </td>
                                    </tr>
EOT;

                        }
                    }
                } else {
                    echo "
                            <div class='alert alert-info' role='alert'>
                                You have no products in your cart!
                            </div>";
                }
                ?>
                </tbody>
            </table>
            <table class="table mt-4 table-sm">
                <tbody>
                <?php
                if (isset($_SESSION["shoppingcart"])) {
                    foreach($_SESSION["shoppingcart"] as $cart) {
                        $product_id = mysqli_real_escape_string($mysqli, $cart[0]);
                        $amount = $cart[1];

                        $result = $mysqli->query("SELECT * FROM products WHERE id = {$product_id};");

                        if (mysqli_num_rows($result) > 0){
                            $row = mysqli_fetch_assoc($result);
                            $totalprice = $row['price'] * $amount;

                            echo <<<EOT
                                    <tr>
                                        <td>{$row['name']}</td>
                                        <td>
                                            <div style="width: 50%; float: left;">
                                                <p style="float: right; margin-right: 2rem;">Amount: {$amount}</p>
                                            </div>
                                            <div style="width: 50%; float: left;">
                                                <form method="post" action="{$relative_root}/logic/shopping_cart/cart_update_product.php">
                                                    <input type="text" name="product_id" value="{$product_id}" class="d-none">
                                                    <button type="submit" name="+" value="+" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle" data-fa-transform="grow-2"></i></button>
                                                    <button type="submit" name="-" value="-" class="btn btn-primary btn-sm"><i class="fas fa-minus-circle" data-fa-transform="grow-2"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        <td style="text-align: right;">Price: &euro;{$totalprice}</td>
                                    </tr>
EOT;

                        }
                    }
                } else {

                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-9">
            <div style="display:flex;align-items:flex-end; height: 250px;">
                <form method="post" action="<?php echo $relative_root;?>/logic/shopping_cart/cart_set_discount.php">
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
                    <td>Price: </td>
                    <td>&euro;<?php echo $total ?></td>
                </tr>
                <tr>
                    <td>Shipping:</td>
                    <td>&euro;<?php
                        $shipping = $itemcount * 1.5;
                        $total += $shipping;
                        echo $shipping;
                        ?></td>
                </tr>
                <tr>
                    <td style="padding-right: 2rem;">Discount: </td>
                    <td>%<?php
                        $total -= $total * ($discount / 100);
                        echo $discount;
                        ?></td>
                </tr>
                <tr>
                    <td style="padding-right: 2rem;">Total before taxes: </td>
                    <td>&euro;<?php
                        echo round($total, 2);
                        ?></td>
                </tr>
                <tr>
                    <td>VAT: </td>
                    <td>&euro;<?php
                        // TODO: ADD TAX DEPENDENT ON CATEGORY
                        $tax = $total * 0.21;
                        $total += $tax;
                        echo round($tax, 2);
                        ?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>&euro;<?php
                        $total = round($total, 2);
                        $_SESSION["shoppingcart_price"] = $total;
                        echo $_SESSION["shoppingcart_price"];
                        ?></td>
                </tr>
            </table>
            <?php
            if (login_check()) {
                echo "<a href='{$relative_root}/pages/shopping_cart/finalize_order.php' class='btn btn-primary mt-3'>Order</a>";
            } else {
                echo "<a href='{$relative_root}/pages/login_form.php' class='btn btn-primary mt-3'>Please login to order</a>";
            }


            ?>


        </div>
    </div>
</div>


<?php   include 'components/footer.php';    ?>
