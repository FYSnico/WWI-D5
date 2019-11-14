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
                <div class="product mb-3">
                    <div class="card shadow" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Product #</h5>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur
                                dolorem facere fuga iusto labore praesentium sint soluta tenetur.</p>
                            <a href="#" class="btn btn-primary">Lees meer</a>
                        </div>
                    </div>
                </div>
                <div class="product mb-3">
                    <div class="card shadow" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Product #</h5>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur
                                dolorem facere fuga iusto labore praesentium sint soluta tenetur.</p>
                            <a href="#" class="btn btn-primary">Lees meer</a>
                        </div>
                    </div>
                </div>
                <div class="product mb-3">
                    <div class="card shadow" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Product #</h5>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur
                                dolorem facere fuga iusto labore praesentium sint soluta tenetur.</p>
                            <a href="#" class="btn btn-primary">Lees meer</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="temp">
        <?php
            $sql = "SELECT StockItemName FROM stockitems ORDER BY RAND() LIMIT 3";   
            $result = $pdo->query($sql);
            if($result->rowCount() > 0){
                echo "<table>";
                    echo "<tr>";
                        echo "<th>Name</th>";
                    echo "</tr>";
                while($row = $result->fetch()){
                    echo "<tr>";
                        echo "<td>" . $row['StockItemName'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                unset($result);
            } else{
                echo "No records matching your query were found.";
            }
        ?>
    </div>
    <br><br>
<?php include('components/footer.php') ?>