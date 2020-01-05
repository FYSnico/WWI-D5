<?php
// Functie voor connectie met database & mysqli
function connect_db() {
$mysqli = new mysqli("localhost", "root", "", "wideworldimporters");
if ($mysqli->connect_errno) {
return "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
return $mysqli;

}
$mysqli = connect_db();
?>