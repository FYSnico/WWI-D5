<html>
<body>
<?php
include "components/config.php";
$cat=array();

$stmt= $pdo->prepare("SELECT StockGroupName FROM stockgroups ORDER BY StockGroupName;");

$stmt->execute();
$naam=array();
$i=1;
while ($row = $stmt->fetch()) {

    $naam[$i]    = $row["StockGroupName"];
//    print($naam[$i] . "<br>");
    $i ++;
}


//foreach($cat AS $index => $naam){
//    print($naam);
//}

$pdo = NULL;

?>
</body>
</html>
