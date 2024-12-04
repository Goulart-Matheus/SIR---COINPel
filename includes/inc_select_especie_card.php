<?
$option_place    = !isset($option_place)    || $option_place    == "" ? " uma especie"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_especie = new Query($bd);

$query_especie->exec("SELECT id_especie, descricao FROM especie WHERE habilitado = 'S'  ORDER BY descricao");
$n_especie = $query_especie->rows();

while ($n_especie--) {

    $query_especie->proximo();

    $selected = "";

    if (($erro || $edit) && $query_especie->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_especie->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_especie->record[1] . "' " . $selected . ">" . $query_especie->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>