<?php
include('components/header.php');
include("components/config.php");
include("functions.php");

?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar menu -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <?php
            // Selecteren producten
            $sql = "SELECT *
            FROM stockgroups 
            ";
            $result = $pdo->query($sql);
            if(isset($_POST['submit'])){
                $name = $_POST['name'];
                $size = $_POST['size'];

                //Insert product
                $sql = ("INSERT INTO stockitems(StockItemName, Size) VALUES ('$name', '$size')");
                $insert = $pdo->query($sql);
                $id = $pdo->lastInsertId();
                print($id);
                $hoii = ("INSERT INTO stockitemholdings(StockItemID, LastStocktakeQuantity) VALUES ('$id', '1000')");
                $hoi = $pdo->query($hoii);
                $categoriess= $_POST['categories'];
                //Insert Category/ies
                foreach ($categoriess as $i) {
                    $categoriess = $i;
                    $sql2 = "INSERT INTO `stockitemstockgroups` (StockItemID, StockGroupID) VALUES ('$id', '$i')";
                    $insert = $pdo->query($sql2);

                }


            }
            ?>
            <h1 style="margin-top: 10px">Product toevoegen</h1>
            <p>Velden met <strong class="text-danger">(*)</strong> zijn verplicht</p>
            <form  method="POST">
                <div class="form-group">
                    <label for="name">Naam<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="text" name="name" id="name" placeholder="b.v. Shipping carton" value="" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="name">Maat</label>
                    <input  class="form-control" type="text" name="size" id="size" placeholder="b.v. 457x279x279mm" value="" maxlength="20">
                </div>
                <div class="form-group">
                    <label for="categories">Selecteer</label>
                    <select class="selectpicker w-25" name="categories[]" required multiple>
                        <?php
                        while ($categories = $result->fetch()) {
                            echo "<option value='".$categories['StockGroupID']."'>".$categories['StockGroupName']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <input class="btn btn-primary mb-2" type="submit" name="submit" value="Save">
            </form>
        </main>
    </div>
</div>


<br><br>
<?php include('components/footer.php') ?>