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
            // ophalen producten gegevens
            $id = $_GET['id']; //id van huidige product
            $sql = "SELECT *
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
            $sql2 = "SELECT S.StockGroupName
                     FROM stockgroups S
                     WHERE NOT EXISTS (SELECT SIG.StockGroupID 
                                       FROM stockitemstockgroups SIG 
                                       WHERE S.StockGroupID = SIG.StockGroupID 
                                       AND SIG.StockItemID = $id)
                    ";
            $result2 = $pdo->query($sql2);
        
            $sql5 = "SELECT * FROM stockgroups";
            $stockgroups = $pdo->query($sql5);
            if(isset($_POST['submit'])){
                $name = $_POST['name'];
                $size = $_POST['size'];
                $status =  $_POST['status'];

                $sql = "UPDATE stockitems SET StockItemName = '$name', Status = '$status' WHERE StockItemID = '$id'";
                $update = $pdo->query($sql);
                
                $categories= $_POST['categories'];
                //categorieen bijwerken (multiple categorieen)
                foreach ($categories as $i) {
                    $categories = $i;
                    
                    //Insert 
                    $sql3 = "INSERT INTO `stockitemstockgroups` (StockItemID, StockGroupID) VALUES ('$id', '$i')";
                    $update = $pdo->query($sql3);
                    //Update  
                    // $sql4 = "UPDATE `stockitemstockgroups` SET StockGroupID = '$i' WHERE StockItemID = $id";
                    // $update2 = $pdo->query($sql4);
                    //Delete
        
                }
            }else if(isset($_POST['delete'])){
                $categories= $_POST['categories'];
                foreach ($categories as $i) {
                    $categories = $i;
                    $sql5 = "DELETE FROM stockitemstockgroups WHERE StockItemID = $id AND StockGroupID = $i";
                    $update2 = $pdo->query($sql5);
                }
            }
            //pre data van categorieen laten tonen
            while($row = $result->fetch())
            {
                $name = $row['StockItemName'];
                $size = $row['Size'];
                $status = $row['Status'];
            }
            $result = $pdo->query($sql);

            ?>
            <h1 style="margin-top: 10px">Product Bijwerken</h1>
            <p>Velden met <strong class="text-danger">(*)</strong> zijn verplicht</p>
            <!-- Producten bijwerken -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Naam<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="text" name="name" id="name" placeholder="b.v. Shipping carton" value="<?php echo $name;?>" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="name">Maat</label>
                    <input  class="form-control" type="text" name="size" id="size" placeholder="b.v. 457x279x279mm" value="<?php echo $size;?>" maxlength="20">
                </div>
                <div class="form-group d-flex align-items-center">
                    <label class="m-0" for="categories">Categorieën</label>
                    <select class="selectpicker w-25 "  id="select" name="categories[]" multiple title="Selecteer een categorie..." data-max-options="3"   required multiple>
                        <?php
                     
                            foreach($stockgroups as $categories){
                                foreach ($result as $selectedCategories) {
                                    $isSelected = "selected"; 
                                    echo "<option . $isSelected . value='".$selectedCategories['StockGroupID']."'>".$selectedCategories['StockGroupName']."</option>";
                                }
                                echo "<option . value='".$categories['StockGroupID']."'>".$categories['StockGroupName']."</option>";

                            }
                        ?>
                    </select>
                    <input type="submit" name="delete" value="Leeg maken" class="btn btn-outline-secondary">
                </div>
                <div class="form-group">
                <label for="status">Status</label>
                <select class="selectpicker" name="status" >
                    <option name="status" value="1">Actief</option>
                    <option name="status" value="0">Niet actief</option>
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