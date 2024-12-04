<?
$option_place    = !isset($option_place)    || $option_place    == "" ? " um responsavel"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_responsavel = new Query($bd);

$query_responsavel->exec("SELECT id_responsavel, nome FROM responsavel  ORDER BY nome");
$n_responsavel = $query_responsavel->rows();

while ($n_responsavel--) {

    $query_responsavel->proximo();

    $selected = "";

    if (($erro || $edit) && $query_responsavel->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_responsavel->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_responsavel->record[0] . "' " . $selected . ">" . $query_responsavel->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>