<?
isset($erro) ?: $erro = null;
isset($edit) ?: $edit = null;
$option_place = !isset($option_place) || $option_place == "" ? "a Região Administrativa"                                   : $option_place;
$where_regiao_administrativa  =  isset($where_regiao_administrativa)  && $where_regiao_administrativa        != "" ? $where_regiao_administrativa . " AND id_regiao_administrativa != 0"    : " WHERE id_regiao_administrativa != 0";
?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_regiao_administrativa = new Query($bd);

$query_regiao_administrativa->exec("SELECT id_regiao_administrativa, nome FROM denuncias.regiao_administrativa " . $where_regiao_administrativa . " ORDER BY nome");
$n__regiao_administrativa = $query_regiao_administrativa->rows();

while ($n__regiao_administrativa--) {

    $query_regiao_administrativa->proximo();

    $selected = "";

    if (($erro || $edit) && $query_regiao_administrativa->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_regiao_administrativa->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_regiao_administrativa->record[0] . "' " . $selected . ">" . $query_regiao_administrativa->record[1] . "</option>";
}

$option_place = $where_regiao_administrativa = $form_elemento = "";
?>