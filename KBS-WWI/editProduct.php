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
            $id = $_GET['id'];
            $sql = "SELECT *
                    FROM stockitems S
                    JOIN stockitemholdings SIH
                    ON S.stockitemID = SIH.stockitemID
                    JOIN stockitemstockgroups SIG 
                    ON S.StockitemID = SIG.StockitemID
                    JOIN stockgroups SG
                    ON SIG.StockGroupID = SG.StockGroupID
                    WHERE S.StockItemID = $id";  

            $result = $pdo->query($sql);
            if(isset($_POST['submit'])){
                $name = $_POST['name'];
                $size = $_POST['size'];

                $sql = "UPDATE stockitems SET StockItemName = '$name'";
                $update = $pdo->query($sql);

            }
            while($row = $result->fetch())
            {
                $name = $row['StockItemName'];
                $size = $row['Size'];
            }
            ?>
            <h1 style="margin-top: 10px">Product Bijwerken</h1>
            <p>Velden met <strong class="text-danger">(*)</strong> zijn verplicht</p>
            <form action="dashboard.php" method="POST">
                <div class="form-group">
                    <label for="name">Naam<strong class="text-danger">*</strong></label>
                    <input  class="form-control" type="text" name="name" id="name" placeholder="b.v. Shipping carton" value="<?php echo $name;?>" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="name">Maat</label>
                    <input  class="form-control" type="text" name="size" id="size" placeholder="b.v. 457x279x279mm" value="<?php echo $size;?>" maxlength="20">
                </div>
                <div class="form-group">
                    <label for="categories">Selecteer</label>
                    <select class="selectpicker w-25" name="categories[]"  multiple>
                        <?php
                        while ($categories = $result->fetch()) {
                            echo "<option value='".$categories['StockGroupID']."'>".$categories['StockGroupName']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <input class="btn btn-primary mb-2" type="submit" name="submit" value="submit">
            </form>
        </main>
    </div>
</div>


<br><br>
<?php include('components/footer.php') ?>