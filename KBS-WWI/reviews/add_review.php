<?php
//	require_once("../../../document_root.php");
//	require_once(get_document_root() . '/includes/ddb_connect.php');
//
//	require_once(get_document_root() . "/includes/header.php");
//    get_header('kaasch', '');
//    if (!login_check()) {
//        header("Location: " . get_relative_root() . "/pages/product/product.php?id=" . $_GET["id"]);
//    }
//?>
<!---->
<?php
//?>
<!---->
<!--<div class="container">-->
<!--    <div class="row">-->
<!--        <div class="col-12">-->
<!--            <h2>Add review for: -->
<!--                --><?php
//                        $product_id = mysqli_real_escape_string($mysqli, $_GET["id"]);
//                        $result = $mysqli->query("SELECT name FROM products WHERE id = {$product_id};");
//
//                        if (mysqli_num_rows($result) > 0){
//                            $row = mysqli_fetch_assoc($result);
//                            echo $row["name"];
//                        }
//                ?>
<!--            </h2>-->
<!--            <form method="post" action="--><?php //echo get_relative_root();?><!--/logic/product/reviews/add_review.php">-->
<!--                <div class="form-check">-->
<!--                    <input class="form-check-input" type="checkbox" value="" name="is_anonymous">-->
<!--                    <label class="form-check-label">-->
<!--                        Place as Anonymous?-->
<!--                    </label>-->
<!--                </div>-->
<!--                <div class="form-group pt-1">-->
<!--                    <label class="" for="inlineFormCustomSelect">Score</label>-->
<!--                    <select class="custom-select" id="inlineFormCustomSelect" name="score">-->
<!--                        <option value="1">One out of Five</option>-->
<!--                        <option value="2">Two out of Five</option>-->
<!--                        <option value="3">Three out of Five</option>-->
<!--                        <option value="4">Four out of Five</option>-->
<!--                        <option selected value="5">Five out of Five</option>-->
<!--                    </select>-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    <label>Review Text:</label>-->
<!--                    <textarea name="content" type="text-multiline" class="form-control" rows="5"></textarea>-->
<!--                </div>-->
<!--                <input name="product_id" type="text" class="d-none" value=--><?php //echo $product_id; ?><!-->-->
<!--                <input type="submit"  name="Submit" value="Save Review" class="btn btn-primary">-->
<!--            </form>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<?php //require_once(get_document_root() . "/includes/footer.php"); ?>