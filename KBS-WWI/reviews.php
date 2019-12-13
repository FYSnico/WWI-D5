<?php
// include van ddb
include "components/ddb_connect_mysqli.php";

// review toeveogen in de database, de gebruiker en ook de
if (isset($_POST["Submittoevoegenreview"])) {
    if (isset($_POST["is_anonymous"])) {
        $naamreview = "Anoniem";
    } else {
        $naamreview = $_SESSION["naam"];
    }

    $sterren = $_POST["score"];
    $item = str_replace("\"", "", $item);
    $sql = "INSERT INTO reviews (Name_customer, Stars, StockItemID) VALUES (\"{$naamreview}\", {$sterren}, {$item})";
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
            if (isset($_SESSION["naam"])) {

            }
            ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12">
            <?php
            //kijken of je een reactie mag plaatsen, dit hangt af of je al eerder een reactie hebt geplaats bij de product en of je bent ingelogt.
                if (isset($_SESSION["naam"])) {
                    $gebruiker = $_SESSION["naam"];
                    $Magreactieplaatsen = TRUE;
                    $item = str_replace("\"", "", $item);
                    $result = $mysqli->query("SELECT * FROM Reviews WHERE StockItemID = {$item};");

                    if($result && mysqli_num_rows($result) > 0) {
                        WHILE ($row = mysqli_fetch_assoc($result)) {
                            $naam = $row["Name_customer"];
                            if ($naam == $gebruiker) {
                                $Magreactieplaatsen = FALSE;
                            }
                        }
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
                        $name = $ros"];
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
