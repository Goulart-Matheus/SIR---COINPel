

<?
$option_place    = !isset($option_place)    || $option_place    == "" ? "um Abrigo"       : $option_place;

?>

<option value="NULL" selected>Selecione <? echo $option_place; ?></option>

<?
$query_abrigo = new Query($bd);

$query_abrigo->exec("SELECT id_tutor, nome FROM canil.tutor WHERE tipo='A' $where_abrigo ORDER BY nome");
$n_abrigo = $query_abrigo->rows();

while ($n_abrigo--) {

    $query_abrigo->proximo();

    $selected = "";

    if (($erro || $edit) && $query_abrigo->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_abrigo->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_abrigo->record[0] . "' " . $selected . ">" . $query_abrigo->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>
