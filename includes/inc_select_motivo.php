<?
$option_place    = !isset($option_place)    || $option_place    == "" ? "um Motivo"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_motivo = new Query($bd);

$query_motivo->exec("SELECT id_motivo, descricao FROM motivo  ORDER BY descricao");
$n = $query_motivo->rows();

while ($n--) {

    $query_motivo->proximo();

    $selected = "";

    if (($erro || $edit) && $query_motivo->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_motivo->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_motivo->record[0] . "' " . $selected . ">" . $query_motivo->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>