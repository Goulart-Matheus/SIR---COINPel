<?
$option_place    = !isset($option_place)    || $option_place    == "" ? " um proprietario"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_proprietario = new Query($bd);

$query_proprietario->exec("SELECT id_proprietario, nome FROM proprietario  ORDER BY nome");
$n_proprietario = $query_proprietario->rows();

while ($n_proprietario--) {

    $query_proprietario->proximo();

    $selected = "";

    if (($erro || $edit) && $query_proprietario->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_proprietario->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_proprietario->record[0] . "' " . $selected . ">" . $query_proprietario->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>