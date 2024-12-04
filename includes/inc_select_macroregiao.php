<?
isset($erro) ?: $erro = null;
isset($edit) ?: $edit = null;
$option_place = !isset($option_place) || $option_place == "" ? "a Macrorregião"                                  : $option_place;
$where_macroregiao  =  isset($where_macroregiao)  && $where_macroregiao        != "" ? $where_macroregiao . " AND id_macroregiao != 0"    : " WHERE id_macroregiao != 0";
?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_macroregiao = new Query($bd);

$query_macroregiao->exec("SELECT id_macroregiao, descricao FROM public.macroregiao " . $where_macroregiao . " ORDER BY descricao");
$n__macroregiao = $query_macroregiao->rows();

while ($n__macroregiao--) {

    $query_macroregiao->proximo();

    $selected = "";

    if (($erro || $edit) && $query_macroregiao->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_macroregiao->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_macroregiao->record[0] . "' " . $selected . ">" . $query_macroregiao->record[1] . "</option>";
}

$option_place = $where_macroregiao = $form_elemento = "";
?>