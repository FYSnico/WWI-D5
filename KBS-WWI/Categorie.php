<?php
include "components/config.php";
//Functie om alle categorieën te verkrijgen uit de database
$cat=array();

$stmt= $pdo->prepare("SELECT StockGroupName FROM stockgroups ORDER BY StockGroupID;");
$stmt->execute();
$naam=array();
$i=1;
while ($row = $stmt->fetch()) {
    $naam[$i]    = $row["StockGroupName"];
    $i ++;
}

$pdo = NULL;
