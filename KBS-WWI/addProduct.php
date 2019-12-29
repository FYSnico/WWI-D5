<?php
include('components/header.php');
include("components/config.php");
include("functions.php");

if (isset($_SESSION["IsSystemUser"]) && $_SESSION["IsSystemUser"] == 1) {
?>
<div class="container">
    <div class="row">
        <!-- Sidebar menu -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-12 px-4">
            <?php
            // Selecteren producten
            $sql = "SELECT *
            FROM stockgroups 
            ";
            $result = $pdo->query($sql);
            if(isset($_POST['submit'])){
                $name = $_POST['name'];
                $size = $_POST['size'];
                $quantity = $_POST['quantity'];
                $price = $_POST['prijs'];
                $description = $_POST['omschrijving'];
                $cold = $_POST['gekoeld'];
                //Insert product
                $sql = ("INSERT INTO stockitems(StockItemName, Size, UnitPrice, SearchDetails, IsChillerStock, Status) 
                         VALUES ('$name', '$size', '$price', '$description', '$cold', 1)
                         ");
                $insert = $pdo->query($sql);
                $id = $pdo->lastInsertId();
                $sql2 = ("INSERT INTO stockitemholdings(StockItemID, LastStocktakeQuantity) VALUES ('$id', '$quantity')");
                $insert2 = $pdo->query($sql2);
                $categoriess= $_POST['categories'];
                //Insert Category/ies
                foreach ($categoriess as $i) {
                    $categoriess = $i;
                    $sql3 = "INSERT INTO `stockitemstockgroups` (StockItemID, StockGroupID) VALUES ('$id', '$i')";
                    $insert = $pdo->query($sql3);
                }
            }
            ?>
            <h1 style="margin-top: 10px">Product toevoegen</h1>
            <p>Velden met <strong class="text-danger">(*)</strong> zijn verplicht</p>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Naam<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="text" name="name" id="name" placeholder="b.v. Shipping carton" value="" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="size">Maat</label>
                    <input  class="form-control" type="text" name="size" id="size" placeholder="b.v. 457x279x279mm" value="" maxlength="20">
                </div>
                <div class="form-group">
                    <label for="quantity">Voorraad<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="number" name="quantity" id="quantity" placeholder="b.v. 11540" value="" step=".01" min="0"  required>
                </div>
                <div class="form-group">
                    <label for="prijs">Prijs<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="number" name="prijs" id="prijs" placeholder="b.v. 14,50" value="" min="0" required>
                </div>
                <div class="form-group">
                    <label for="categories">Categorieën<strong class="text-danger">*</strong></label>
                    <select class="selectpicker w-25" name="categories[]" data-max-options="3" required multiple>
                        <?php
                        while ($categories = $result->fetch()) {
                            echo "<option value='".$categories['StockGroupID']."'>".$categories['StockGroupName']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="omschrijving">Omschrijving<strong class="text-danger">*</strong></label>
                    <textarea class="form-control"  name="omschrijving" id="omschrijving" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="gekoeld">Gekoeld:<strong class="text-danger">*</strong><strong class="blockquote-footer">(Ja of Nee)</strong></label>
                    
                    <select class="form-control" id="gekoeld" name="gekoeld" required>
                        <option name="gekoeld" value="1">Ja</option>
                        <option name="gekoeld" value="2">Nee</option>
                    </select>
                </div>
                <button class="btn btn-primary mb-2" type="submit" name="submit" value="Save">Toevoegen</button>
            </form>
        </main>
    </div>
</div>


<br><br>
<?php } ?>
<?php include('components/footer.php') ?>