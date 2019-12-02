<?php
include('components/header.php');
include("components/config.php");
include("functions.php");

if(isset($_GET['p'])){
    $huidigepagina = $_GET['p'];
}
else{
    $huidigepagina = 1;
}
?>
    <div class="container">
        <div class="content">
            <h3>Zoekresultaten</h3>
            <br>
                <?php
                //$test = "usb cube";
                if (empty($_GET["query"])) {
                    print("Niks ingevoerd!");
                    die();
                } elseif(is_numeric($_GET["query"])){
                    $int = $_GET["query"];
                    $sql = "SELECT StockItemName, RecommendedRetailPrice, QuantityPerOuter, StockGroupName, S.StockItemID 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            WHERE S.StockItemID = $int
                            GROUP BY S.StockItemID";
                }else {
                    $eerste = $_GET["query"];
                    $query_array = explode(' ', $_GET["query"]);
                    //print_r($query_array);
                    //$sqla = array('0'); // Stop errors when $words is empty
                    $sqla[0] = "S.SearchDetails LIKE '%$eerste%'";
                    foreach ($query_array as $word) {
                        $sqla[] = "S.SearchDetails LIKE '%$word%'";
                        if($word == "'1'"){
                            echo "<img src='https://media.makeameme.org/created/sql-injection-sql.jpg' height=\"100%\" width=\"100%\">";
                            die();
                        }
                        if($word == "cuttherope"){
                            ?>
                            <h5>Als het niet werkt moet je je adblocker uitzetten</h5>
                            <iframe id="game-player" seamless="seamless" scrolling="no" src="https://games.gamepix.com/play/40072?sid=30064&amp;mp_assets=https%3A%2F%2Fs2.minijuegosgratis.com%2F&amp;mp_embed=0&amp;mp_game_id=209122&amp;mp_game_uid=cut-the-rope-time-travel&amp;mp_game_url=https%3A%2F%2Fwww.miniplay.com%2Fgame%2Fcut-the-rope-time-travel&amp;mp_int=1&amp;mp_locale=en_US&amp;mp_player_type=IFRAME&amp;mp_site_https_url=https%3A%2F%2Fwww.miniplay.com%2F&amp;mp_site_name=miniplay.com&amp;mp_site_url=https%3A%2F%2Fwww.miniplay.com%2F&amp;mp_timezone=Europe%2FMadrid&amp;mp_view_type=large&amp;utm_source=Flora&amp;utm_medium=medium&amp;utm_campaign=floraenglish" data-src="https://games.gamepix.com/play/40072?sid=30064&amp;mp_assets=https%3A%2F%2Fs2.minijuegosgratis.com%2F&amp;mp_embed=0&amp;mp_game_id=209122&amp;mp_game_uid=cut-the-rope-time-travel&amp;mp_game_url=https%3A%2F%2Fwww.miniplay.com%2Fgame%2Fcut-the-rope-time-travel&amp;mp_int=1&amp;mp_locale=en_US&amp;mp_player_type=IFRAME&amp;mp_site_https_url=https%3A%2F%2Fwww.miniplay.com%2F&amp;mp_site_name=miniplay.com&amp;mp_site_url=https%3A%2F%2Fwww.miniplay.com%2F&amp;mp_timezone=Europe%2FMadrid&amp;mp_view_type=large&amp;utm_source=Flora&amp;utm_medium=medium&amp;utm_campaign=floraenglish" width="980" height="553" allow="autoplay; fullscreen; camera" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" style="opacity: 1;"></iframe>
                            <?php
                            die();  
                        }
                    }
                    $sqlb = implode(" OR ", $sqla);
//                    print($sqlb);
                    $sql = "SELECT StockItemName, RecommendedRetailPrice, QuantityPerOuter, StockGroupName, S.StockItemID, LastStockTakeQuantity 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            JOIN stockitemholdings SIH
                            ON S.stockitemID = SIH.stockitemID
                            WHERE $sqlb";
                }
                    $result = $pdo->query($sql);
                //random products weergegeven
                if ($result->rowCount() > 0) {
                    ?>
                    <div class="card-deck kaartdeck">
                    <?php
                    $productnummer = 1;
                    $productoffset = 1;

                    while (($row = $result->fetch()) && $productnummer <= 12) {
                        if ($productoffset <= (12 * $huidigepagina) - 12) {
                            $productoffset ++;
                        }
                        else {
                            ?>
                            <div class="card w-25 kaartbreedte" style="width: 18rem;">
                                <a href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'><img class="card-img-top kaartimg" src="<?php echo randomPicture() ?>" alt="Productafbeelding"></a>
                                <div class="card-body">
                                    <h5 class="card-title kaarttitel"><a
                                                href='product_item.php?id="<?php echo $row['StockItemID'] ?>"'><?php echo $row['StockItemName']; ?></a>
                                    </h5>
                                </div>
                                <div class="card-footer kaartfooter">
                                    <p class='card-text text-primary'><a
                                                href='product.php?id="<?php echo $row['StockGroupID'] ?>"'><?php echo $row['StockGroupName'] ?></a>
                                    </p>
                                    <p class='card-text text-warning'><?php echo $row['LastStockTakeQuantity'] ?> op voorraad</p>
                                    <p class="card-text">
                                        € <?php echo str_replace(".", ",", $row['RecommendedRetailPrice']) ?></p>
                                </div>
                            </div>
                            <?php
                            $productnummer++;
                        }
                    }
                    ?>
                    </div>
                    <?php
                    $paginanummer = 1;
                    while($paginanummer <= ceil($result->rowCount() / 12)){
                        print("<a href='search.php?query=$_GET[query]&p=$paginanummer'>$paginanummer</a>");
                        $paginanummer ++;
                    }
                    unset($result);
                } else {
                    echo "Geen producten gevonden.";
                }

                ?>
        </div>
    </div>

    <br><br>
<?php include('components/footer.php') ?>