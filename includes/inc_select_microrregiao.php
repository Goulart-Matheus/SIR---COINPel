<?
isset($erro) ?: $erro = null;
isset($edit) ?: $edit = null;
$option_place = !isset($option_place) || $option_place == "" ? "a Microrregião"                                   : $option_place;
$where_microrregiao  =  isset($where_microrregiao)  && $where_microrregiao        != "" ? $where_microrregiao . " AND id_microrregiao != 0"    : " WHERE id_microrregiao != 0";
?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_microrregiao = new Query($bd);

$query_microrregiao->exec("SELECT id_microrregiao, nome FROM denuncias.microrregiao " . $where_microrregiao . " ORDER BY nome");
$n__microrregiao = $query_microrregiao->rows();

while ($n__microrregiao--) {

    $query_microrregiao->proximo();

    $selected = "";

    if (($erro || $edit) && $query_microrregiao->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_microrregiao->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_microrregiao->record[0] . "' " . $selected . ">" . $query_microrregiao->record[1] . "</option>";
}

$option_place = $where_microrregiao = $form_elemento = "";
?>