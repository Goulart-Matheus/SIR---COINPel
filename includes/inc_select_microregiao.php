<?
isset($erro) ?: $erro = null;
isset($edit) ?: $edit = null;
$option_place = !isset($option_place) || $option_place == "" ? "a Microregião"                                   : $option_place;
$where_microregiao  =  isset($where_microregiao)  && $where_microregiao        != "" ? $where_microregiao . " AND id_microregiao != 0"    : " WHERE id_microregiao != 0";
?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_microregiao = new Query($bd);

$query_microregiao->exec("SELECT id_microregiao, descricao FROM public.microregiao " . $where_microregiao . " ORDER BY descricao");
$n__microregiao = $query_microregiao->rows();

while ($n__microregiao--) {

    $query_microregiao->proximo();

    $selected = "";

    if (($erro || $edit) && $query_microregiao->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_microregiao->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_microregiao->record[0] . "' " . $selected . ">" . $query_microregiao->record[1] . "</option>";
}

$option_place = $where_microregiao = $form_elemento = "";
?>