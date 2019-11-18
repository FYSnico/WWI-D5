<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product</title>
    <?php include 'components/header.php'; ?>
</head>
<body>
<?php
include "components/config.php";
$dropdownitem = $_GET['id'];
$stmt = $pdo->prepare("SELECT `StockItemName` FROM `stockitems` WHERE `StockItemID` IN (SELECT `StockItemID` FROM `stockitemstockgroups` WHERE `StockGroupID` = $dropdownitem);");
$stmt->execute();

$i=1;
$naam = array();
$cat=array();

while ($row = $stmt->fetch()) {
    $naam[$i] = $row["StockItemName"];
//    print($naam[$i] . "<br>");
    $i ++;
}



foreach($naam AS $index => $c){
    print($c . "<br>");
}
$pdo = NULL;
?>
</body>
    <footer>
    <?php include 'components/footer.php'; ?>
    </footer>
</html>