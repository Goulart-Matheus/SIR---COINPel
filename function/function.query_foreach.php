<?
require_once "../includes/connection.php";

function query_foreach($query_str, $func, $db = NULL) {
    if(is_null($db)) $db = $GLOBALS['bd'];

    $query = new Query($db);
    $query->exec($query_str);
    $n = $query->rows();
    while($n--)
    {
        $query->proximo();
        $func($query);
    }
}
?>