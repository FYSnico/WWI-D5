<!--<div class="row">-->
<!--    <div class="col-3">-->
<!--        <div>-->
<!--            <h3>Amount of reviews:<br/>-->
<!--                --><?php
//                    $product_id = mysqli_real_escape_string($mysqli, $_GET["id"]);
//
//                    $result = $mysqli->query("SELECT COUNT(*) as reviewcount FROM reviews WHERE products_id = {$product_id};");
//
//                    if ($result && mysqli_num_rows($result) > 0){
//                        $row = mysqli_fetch_assoc($result);
//                        echo $row["reviewcount"];
//                    } else {
//                        echo "0";
//                    }
//                ?>
<!--            </h3>-->
<!--        </div>-->
<!--        <div style="padding-top: 5rem;">-->
<!--            <h3>Average Score:<br/>-->
<!--                --><?php
//                    $result = $mysqli->query("SELECT AVG(score) as average FROM reviews WHERE products_id = {$product_id};");
//
//                    if ($result && mysqli_num_rows($result) > 0){
//                        $row = mysqli_fetch_assoc($result);
//                        echo round($row["average"], 1);
//                    } else {
//                        echo "0";
//                    }
//                ?>
<!--            </h3>-->
<!--        </div>-->
<!--        --><?php //
//        if (login_check()) {
//            echo "<a href='". get_relative_root() . "/pages/product/reviews/add_review.php?id=" . $product_id . "' class='btn btn-primary mt-3'>Review Plaatsen</a>";
//        }
//        ?>
<!--        -->
<!--    </div>-->
<!--    <div class="col-9">-->
<!--        --><?php
//             $result = $mysqli->query("SELECT first_name, last_name, score, content, is_anonymous FROM users, reviews WHERE products_id = {$product_id} AND reviews.users_id = users.id ORDER BY reviews.created_at DESC;");
//
//             if ($result && mysqli_num_rows($result) > 0){
//                while($row = mysqli_fetch_assoc($result)) {
//                    $name = "";
//                    if ($row["is_anonymous"]) {
//                        $name = "Anonymous";
//                    } else {
//                        $name = $row["first_name"] . " " . $row["last_name"];
//                    }
//
//                    echo <<<EOT
//                    <div class="review-item">
//                        <div style="width: 20%;">
//                            <p>Customer name: {$name}</p>
//                            <p>Given Score: {$row["score"]}</p>
//                        </div>
//                        <div style="width: 10%;"></div>
//                        <div style="width: 70%;">
//                            <p>{$row["content"]}</p>
//                        </div>
//                    </div>
//EOT;
//                }
//            } else {
//                echo "<div class='alert alert-info' role='alert'><p>This product has no reviews!</p></div>";
//            }
//        ?>
<!--    </div>-->
<!--</div>-->