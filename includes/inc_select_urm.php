<?
$option_place    = !isset($option_place)    || $option_place    == "" ? "um URM"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_urm = new Query($bd);

$query_urm->exec("SELECT id_urm, valor , ativo , mes_referencia , ano_referencia FROM urm  ORDER BY valor");
$n = $query_urm->rows();

while ($n--) {

    $query_urm->proximo();

    $selected = "";

    if (($erro || $edit) && $query_urm->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_urm->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_urm->record[0] . "' " . $selected . ">" . $query_urm->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>