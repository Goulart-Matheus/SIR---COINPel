<?
$option_place    = !isset($option_place)    || $option_place    == "" ? "um tipo de Contato"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_tipo_contato = new Query($bd);

$query_tipo_contato->exec("SELECT id_tipo_contato, descricao FROM tipo_contato  ORDER BY descricao");
$n = $query_tipo_contato->rows();

while ($n--) {

    $query_tipo_contato->proximo();

    $selected = "";

    if (($erro || $edit) && $query_tipo_contato->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_tipo_contato->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_tipo_contato->record[0] . "' " . $selected . ">" . $query_tipo_contato->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>