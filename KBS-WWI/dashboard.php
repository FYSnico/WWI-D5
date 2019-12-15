<?php
include('components/header.php');
include("components/config.php");
include("functions.php");
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-1">
            <div class="panel panel-default panel-table">
              <div class="panel-heading">
                <div class="row mb-2">
                  <div class="col col-xs-12">
                    <h3 class="panel-title">Producten
                        <a href="addProduct.php"><button type="button" class="btn btn-primary btn-create"><i class="fa fa-plus" aria-hidden="true"></i></button></a>
                    </h3>
                  </div>
                  <div class="col col-xs-12 text-right">
                  </div>
                </div>
              </div>
              <div class="panel-body">
                <table class="table table-striped table-bordered table-list">
                    <thead>
                        <tr>
                            <th class="hidden-xs">ID</th>
                            <th>Naam</th>
                            <th>Prijs</th>
                            <th class="text-center"><i class="fa fa-cog" aria-hidden="true"></i></th>

                        </tr> 
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT S.StockItemID, StockItemName, UnitPrice 
                                FROM stockitems S
                                JOIN stockitemholdings SIH
                                ON S.stockitemID = SIH.stockitemID  
                                ORDER BY S.StockItemID DESC
                        ";
                        $result = $pdo->query($sql);
                        $convertRate = @convertCurrency2(1, 'USD', 'EUR');
                        while ($row = $result->fetch()) {

                        echo '<tr>';
                            echo '<td class="hidden-xs">'. $row['StockItemID'] .'</td>';
                            echo '<td>'. $row['StockItemName'] .'</td>';
                            echo '<td>€'. $UnitPrice = $row['UnitPrice'] * $convertRate; number_format($UnitPrice,2,",",".") .'</td>';
                            echo '<td align="center" class="">';
                                echo '<a href="editProduct.php?id='.$row['StockItemID'].'" class="btn btn-warning mr-1"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                                echo '<a class="btn btn-danger ml-1"><i class="fa fa-trash"></i></a>';
                            echo '</td>';
                        echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            
              </div>
              <!-- <div class="panel-footer">
                <div class="row">
                  <div class="col col-xs-4">Page 1 of 5
                  </div>
                  <div class="col col-xs-8">
                    <ul class="pagination hidden-xs pull-right">
                      <li><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">4</a></li>
                      <li><a href="#">5</a></li>
                    </ul>
                    <ul class="pagination visible-xs pull-right">
                        <li><a href="#">«</a></li>
                        <li><a href="#">»</a></li>
                    </ul>
                  </div>
                </div>
              </div> -->
            </div>

</div></div></div>





<br><br>
<?php include('components/footer.php'); ?>