<?php
error_reporting (1);
require_once ("../class/class.connection.php");
$bd                   =new DataBase("192.168.0.56", "5432", "sir", "postgres", "postgres");
$query                =new Query($bd);
$queryauth            =new Query($bd);
?>
