<?php 
include('components/header.php');
include("components/config.php");
?>
<div class="container">
    <div class="card shadow">
        <div class="row">
            <?php
                $item = $_GET['id'];
                $sql = "SELECT SG.StockGroupID, S.StockItemID, StockItemName, RecommendedRetailPrice, QuantityPerOuter, StockGroupName
                        FROM stockitems S 
                        JOIN stockitemstockgroups SIG 
                        ON S.StockitemID = SIG.StockitemID
                        JOIN stockgroups SG
                        ON SIG.StockGroupID = SG.StockGroupID
                        WHERE SIG.StockItemID = $item  
                        ";
                $result = $pdo->query($sql);
                while($row = $result->fetch()){
                    echo'<aside class="col-sm-5 border-right">';
                        echo'<article class="gallery-wrap">';
                            echo'<div class="img-big-wrap">';
                                echo'<div> <a href="#"><img src="..."></a></div>';
                            echo'</div>';
                        echo'</article>';
                    echo'</aside>';
                    echo'<aside class="col-sm-7 pb-3">';
                        echo'<article class="card-body p-5">';
                            echo'<h3 class="title mb-3">';
                                echo $row['StockItemName'];
                            echo'</h3>';
                            echo'<p class="price-detail-wrap">';
                                echo'<dl class="param param-inline">';
                                    echo'<dt>';
                                        while($categories = $result->fetch()){
                                            include "./categorie.php";
                                            echo "<a href='product.php?id=" .  $categories['StockGroupID'] . "'> ";
                                                echo $categories['StockGroupName'];
                                            echo '</a>';
                                        }
                                    echo'</dt>';
                                echo'</dl>';
                                echo'<span class="price h3 text-warning">';
                                    echo'<span class="currency">€</span><span class="num">';
                                        echo $row['RecommendedRetailPrice'];
                                    echo'</span>';
                                echo'</span>';
                            echo'</p>';
                        echo'</article>';
                        echo'<hr>';
                        echo'<div class="row">';
                            echo'<div class="col-sm-5">';
                                echo'<dl class="param param-inline">';
                                    echo'<dt> Voorraad: ';
                                        echo $row['QuantityPerOuter'];
                                    echo'</dt>';
                                echo'</dl>';
                            echo'</div>';
                        echo'</div>';
                        echo'<hr>';
                        echo'<a href="#" class="btn btn-lg btn-outline-primary text-uppercase"> <i class="fas fa-shopping-cart"></i> Toevoegen </a>';
                    echo'</aside>';

                }
            ?>
        </div>
    </div>
</div>
<br><br>
<?php include('components/footer.php') ?>