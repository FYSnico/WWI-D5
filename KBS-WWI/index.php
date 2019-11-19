<?php 
include('components/header.php');
include("components/config.php");
?>
    <div class="container">
        <div class="content">
            <div class="voorwoord card p-4 shadow">
                <h1>Welkom in onze webshop!</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor eligendi et exercitationem illo, ipsa
                    laborum, magnam maxime modi perspiciatis, praesentium quibusdam recusandae saepe ut! Dolorem error
                    expedita inventore iusto odio.</p>
            </div>
            <br>
            <div class="row justify-content-around">
                <?php
                    //random products genareren
                    $img = 'https://picsum.photos/200/300';
                    $sql = "SELECT SG.StockGroupID, S.StockItemID, StockItemName, RecommendedRetailPrice, QuantityPerOuter, StockGroupName 
                            FROM stockitems S 
                            JOIN stockitemstockgroups SIG 
                            ON S.StockitemID = SIG.StockitemID
                            JOIN stockgroups SG
                            ON SIG.StockGroupID = SG.StockGroupID
                            ORDER BY RAND()
                            LIMIT 3
                            ";
                    $result = $pdo->query($sql);
                    //random products weergegeven
                    if($result->rowCount() > 0){
                        while($row = $result->fetch()){
                            echo "<div class=' products mb-3'>";
                                echo "<div class='rand_products card shadow'>";
                                    echo "<img src='$img' class='card-img-top h-50' alt=''>";
                                    echo "<div class='card-body d-flex flex-column'>";
                                        echo "<h5 class='card-title'>";
                                            echo $row['StockItemName'];
                                        echo "</h5>";
                                        echo "<p class='card-title text-primary'><a href='product.php?id=" .  $row['StockGroupID'] . "'>" . $row['StockGroupName'] . "</a>";
                                        echo "<p class='card-title text-warning'>" . $row['QuantityPerOuter'] . " op voorraad</p>";
                                        echo "<h5 class='card-title text-danger mt-auto'>";
                                            echo $row['RecommendedRetailPrice'];
                                        echo "€</h5>";
                                        echo "<a href='product_item.php?id=" .  $row['StockItemID'] . "' class='btn btn-primary mt-auto'>Meer informatie</a>";
                                    echo "</div>";
                                echo "</div>";
                            echo "</div>";
                        }
                        unset($result);
                    } else{
                        echo "Geen producten gevonden.";
                    }
                ?>
            </div>
        </div>
    </div>

    <br><br>
<?php include('components/footer.php') ?>