<?php
include('components/header.php');
include("components/config.php");
include("functions.php");
//checken of user een admin is
if (isset($_SESSION["IsSystemUser"]) && $_SESSION["IsSystemUser"] == 1) {
?>

<div class="container">
    <div class="row">
        <!-- Sidebar menu -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-12 px-4">
            <?php
            // ophalen producten gegevens
            $id = $_GET['id']; //id van huidige product
            $sql = "SELECT S.StockItemName, Size, UnitPrice, SearchDetails, IsChillerStock, LastStocktakeQuantity, S.Status,  SG.StockGroupID, StockGroupName
                    FROM stockitems S
                    JOIN stockitemholdings SIH
                    ON S.stockitemID = SIH.stockitemID
                    JOIN stockitemstockgroups SIG 
                    ON S.StockitemID = SIG.StockitemID
                    JOIN stockgroups SG
                    ON SIG.StockGroupID = SG.StockGroupID
                    WHERE S.StockItemID = $id
                    ";  
            $result = $pdo->query($sql);

            $sql5 = "SELECT StockGroupID, StockGroupName FROM stockgroups";
            $stockgroups = $pdo->query($sql5);

            if(isset($_POST['submit'])){
                $name = $_POST['name'];
                $size = $_POST['size'];
                $status =  $_POST['status'];
                $quantity = $_POST['quantity'];
                $price = $_POST['prijs'];
                $description = $_POST['omschrijving'];
                $cold = $_POST['gekoeld'];
                //Product gegevens bijwerken
                $sql = "UPDATE stockitems SET StockItemName = '$name', Size = '$size', Status = '$status', UnitPrice = '$price', SearchDetails = '$description', IsChillerStock = '$cold'  WHERE StockItemID = '$id'";
                $updateProduct = $pdo->query($sql);
                //Product voorraad bijwerken
                $sql2 = "UPDATE stockitemholdings SET StockItemID = '$id',  LastStocktakeQuantity = '$quantity' WHERE StockItemID = '$id'";
                $updateQuantity = $pdo->query($sql2);

                //categorieen bijwerken (multiple categorieen)
                $categories= $_POST['categories'];
                foreach ($categories as $i) {
                    $categories = $i;
                    //Aanmaken categorieen 
                    $sql3 = "INSERT INTO `stockitemstockgroups` (StockItemID, StockGroupID) VALUES ('$id', '$i')";
                    $insertCategories = $pdo->query($sql3);
                }
            }else if(isset($_POST['delete'])){
                //Verwijderen categorieen
                $categories= $_POST['categories'];
                foreach ($categories as $i) {
                    $categories = $i;
                    $sql4 = "DELETE FROM stockitemstockgroups WHERE StockItemID = $id AND StockGroupID = $i";
                    $updateCategories = $pdo->query($sql4);
                }
            }
            //Product data uit de database ophalen
            while($row = $result->fetch())
            {
                $name = $row['StockItemName'];
                $size = $row['Size'];
                $price = $row['UnitPrice'];
                $status = $row['Status'];
                $quantity = $row['LastStocktakeQuantity'];
                $description = $row['SearchDetails'];
                $cold = $row['IsChillerStock'];

            }
            $result = $pdo->query($sql);

            ?>
            <h1 style="margin-top: 10px">Product Bijwerken</h1>
            <p>Velden met <strong class="text-danger">(*)</strong> zijn verplicht</p>
            <!-- Producten bijwerken form -->
            <form id="form" method="POST">
                <div class="form-group">
                    <label for="name">Naam<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="text" name="name" id="name" placeholder="b.v. Shipping carton" value="<?php echo $name;?>" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="name">Maat</label>
                    <input  class="form-control" type="text" name="size" id="size" placeholder="b.v. 457x279x279mm" value="<?php echo $size;?>" maxlength="20">
                </div>
                <div class="form-group">
                    <label for="quantity">Voorraad<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="number" name="quantity" id="quantity" placeholder="b.v. 11540" value="<?php echo $quantity;?>"  min="0"  required>
                </div>
                <div class="form-group">
                    <label for="prijs">Prijs<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="number" name="prijs" id="prijs" placeholder="b.v. 14,50" value="<?php echo $price;?>" step=".01" min="0" required>
                </div>
                <div class="form-group d-flex align-items-center mb-0">
                    <label class="m-0" for="categories">Categorieën</label>
                    <select class="selectpicker w-25 "  id="select" name="categories[]" multiple title="Selecteer een categorie..." data-max-options="3"   required multiple>
                        <?php
                        echo '<optgroup label="Huidige categorieën">';
                            //geselecteerde categorieen weergegeven
                            foreach ($result as $selectedCategories) {
                                $isSelected = "selected"; 
                                echo "<option class='text-primary' . $isSelected . value='".$selectedCategories['StockGroupID']."'>".$selectedCategories['StockGroupName']."</option>";
                            }
                        echo '<option data-divider="true"></option>';                            
                        echo '<optgroup label="Alle categorieën">';
                            //alle categorieen weergeven
                            foreach($stockgroups as $categories){

                                echo "<option . value='".$categories['StockGroupID']."'>".$categories['StockGroupName']."</option>";
                            }
                        echo '</optgroup>';
                        ?>
                    </select>
                    <input type="submit" name="delete" value="Leeg maken" class="btn btn-outline-secondary">
                </div>
                <p id="small-text">Bij het bijwerken of verwijderen van de huidige categorieën, graag eerst op de knop "leeg maken" klikken en daarna vervolgens uw wijzigingen invoeren.</p>
                <div class="form-group">
                    <label for="omschrijving">Omschrijving<strong class="text-danger">*</strong></label>
                    <textarea class="form-control" name="omschrijving" id="omschrijving" rows="3" required><?php echo $description;?></textarea>
                </div>
                <div class="form-group">
                    <label for="gekoeld">Gekoeld:<strong class="text-danger">*</strong><strong class="blockquote-footer">(Ja of Nee)</strong></label>
                    
                    <select class="form-control" id="gekoeld" name="gekoeld" required>
                        <?php 
                        //gekoeld of niet gekoeld selecteren
                        if($cold == 1){
                            echo '<option name="gekoeld" value="1">Ja</option>';
                            echo '<option name="gekoeld" value="0">Nee</option>';
                        }else{
                            echo '<option name="gekoeld" value="0">Nee</option>';
                            echo '<option name="gekoeld" value="1">Ja</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="selectpicker" name="status" >
                        <?php
                        //actief of niet actief selecteren
                        if ($status == 1){
                            echo '<option name="status" value="1">Actief</option>';
                            echo '<option name="status" value="0">Niet actief</option>';
                        }else{
                            echo '<option name="status" value="0">Niet actief</option>';
                            echo '<option name="status" value="1">Actief</option>';

                        }
                        ?>
                    </select>
                </div>
                <input class="btn btn-primary mb-2" type="submit" name="submit" value="Bijwerken">
            </form>
        </main>
    </div>
</div>

<?php
}
?>
<br><br>
<?php include('components/footer.php') ?>