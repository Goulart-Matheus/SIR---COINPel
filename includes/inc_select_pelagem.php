<?
$option_place    = !isset($option_place)    || $option_place    == "" ? " uma pelagem"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_pelagem = new Query($bd);

$query_pelagem->exec("SELECT id_pelagem, descricao FROM pelagem  ORDER BY descricao");
$n = $query_pelagem->rows();

while ($n--) {

    $query_pelagem->proximo();

    $selected = "";

    if (($erro || $edit) && $query_pelagem->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_pelagem->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_pelagem->record[0] . "' " . $selected . ">" . $query_pelagem->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>