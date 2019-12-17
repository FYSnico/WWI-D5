<?php

// review toeveogen in de database, de gebruiker en ook de
if (isset($_POST["Submittoevoegenreview"])) {
    $IDreview = $_SESSION["naam"];


    $sterren = $_POST["score"];
    $item = str_replace("\"", "", $item);
    $sql = "INSERT INTO reviews (Name_customer, Stars, StockItemID) VALUES (\"{$IDreview}\", {$sterren}, {$item})";
    if (mysqli_query($mysqli, $sql)) {
        echo "<br>";
    } else {
        echo "Error: " . $sql . "" . mysqli_error($mysqli);
    }
}

?>
<br>
<div class="container">
    <div class="row">
        <div class="col-12">
            <?php
            //kijken of je een reactie mag plaatsen, dit hangt af of je al eerder een reactie hebt geplaats bij de product en of je bent ingelogt.
                if (isset($_SESSION["naam"])) {
                    $gebruiker = $_SESSION["naam"];
                    $Magreactieplaatsen = TRUE;
                    $heefteenreactie = FALSE;
                    $item = str_replace("\"", "", $item);
                    $result = $mysqli->query("SELECT * FROM Reviews WHERE StockItemID = {$item};");

                    if($result && mysqli_num_rows($result) > 0) {
                        WHILE ($row = mysqli_fetch_assoc($result)) {
                            $ID = $row["Name_customer"];
                            if ($ID == $gebruiker) {
                                $Magreactieplaatsen = FALSE;
                            }
                        }
                    }
                    $result2 = $mysqli->query("SELECT avg(Stars) FROM Reviews WHERE StockItemID = {$item};");

                    if($result2 && mysqli_num_rows($result2) > 0) {
                        $row2 = mysqli_fetch_assoc($result2);
                        $heefteenreactie = TRUE;
                    }

                    if($Magreactieplaatsen == TRUE){
                        //laten zit van invoer veld
                        ?>
                        <form method="post" action="">
                            <div class="form-group pt-1">
                                <label class="" for="inlineFormCustomSelect"></label>
                                <select class="custom-select" id="inlineFormCustomSelect" name="score">
                                    <option value="1">⭐</option>
                                    <option value="2">⭐⭐</option>
                                    <option value="3">⭐⭐⭐</option>
                                    <option value="4">⭐⭐⭐⭐</option>
                                    <option selected value="5">⭐⭐⭐⭐⭐</option>
                                </select>
                            </div>
                            <input name="product_id" type="text" class="d-none" value=<?php echo $item; ?>>
                            <input type="submit" name="Submittoevoegenreview" value="Plaats review"
                                   class="btn btn-primary">
                        </form>
                        <?php
                    }
                }

                // laten zien de reviews
                $result = $mysqli->query("SELECT Name_customer, Stars FROM reviews WHERE StockItemID = {$item};");

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row["Name_customer"];
                        $ster = $row["Stars"];
                        print "<br><h5>$name</h5>";
                        for ($i = 0; $i < $ster; $i++) {
                            print "⭐";
                        }
                        print "<br>";
                    }
                }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
