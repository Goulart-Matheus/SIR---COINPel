<?
isset($erro) ?: $erro = null;
isset($edit) ?: $edit = null;
$option_place = !isset($option_place) || $option_place == "" ? ""                                   : $option_place;
$where_tipo_ocorrencia  =  isset($where_tipo_ocorrencia)  && $where_tipo_ocorrencia        != "" ? $where_tipo_ocorrencia . " AND id_tipo_ocorrencia != 0"    : "AND id_tipo_ocorrencia != 0";
?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_tipo_ocorrencia = new Query($bd);

$query_tipo_ocorrencia->exec("SELECT id_tipo_ocorrencia, nome FROM denuncias.tipo_ocorrencia WHERE flag = '$_flag' " . $where_tipo_ocorrencia . " ORDER BY nome");
$n_tipo_ocorrencia = $query_tipo_ocorrencia->rows();

while ($n_tipo_ocorrencia--) {

    $query_tipo_ocorrencia->proximo();

    $selected = "";

    if (($erro || $edit) && $query_tipo_ocorrencia->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_tipo_ocorrencia->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_tipo_ocorrencia->record[0] . "' " . $selected . ">" . $query_tipo_ocorrencia->record[1] . "</option>";
}

$option_place = $where_tipo_ocorrencia = $form_elemento = "";
?>