<?
$option_place    = !isset($option_place)    || $option_place    == "" ? "um Bairro"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_bairro = new Query($bd);

$query_bairro->exec("SELECT id_bairro, descricao FROM bairro WHERE habilitado ='S' ORDER BY descricao");
$n_bairro = $query_bairro->rows();

while ($n_bairro--) {

    $query_bairro->proximo();

    $selected = "";

    if (($erro || $edit) && $query_bairro->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_bairro->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_bairro->record[0] . "' " . $selected . ">" . $query_bairro->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>