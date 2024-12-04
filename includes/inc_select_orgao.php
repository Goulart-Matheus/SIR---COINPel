<?
isset($erro) ?: $erro = null;
isset($edit) ?: $edit = null;
$option_place    = !isset($option_place)    || $option_place    == "" ? "um Órgão"        : $option_place;
$where_orgao     =  isset($where_orgao)     && $where_orgao     != "" ? $where_orgao      : "";
?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_orgao = new Query($bd);

$query_orgao->exec("SELECT id_orgao, sigla || ' - ' || descricao FROM orgao WHERE id_cliente = $_id_cliente " . $where_orgao . " ORDER BY descricao");
$n_orgao = $query_orgao->rows();

while ($n_orgao--) {

    $query_orgao->proximo();

    $selected = "";

    if (($erro || $edit) && $query_orgao->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_orgao->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_orgao->record[0] . "' " . $selected . ">" . $query_orgao->record[1] . "</option>";
}

$option_place = $where_orgao = $form_elemento = "";
?>