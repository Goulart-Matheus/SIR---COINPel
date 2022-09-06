<?
$option_place    = !isset($option_place)    || $option_place    == "" ? "um URM"       : $option_place;
$mes = ["JANEIRO","FEVEREIRO","MARÇO","ABRIL","MAIO","JUNHO","JULHO","AGOSTO","SETEMBRO","OUTUBRO","NOVEMBRO","DEZEMBRO"];
?>

<option value="" selected>Selecione <? echo $option_place; ?></option>



<?
$query_urm = new Query($bd);

$query_urm->exec("SELECT id_urm, valor , ativo , mes_referencia , ano_referencia FROM urm  ORDER BY  (ano_referencia, mes_referencia) DESC");
$n_urm = $query_urm->rows();

while ($n_urm--) {

    $query_urm->proximo();

    $selected = "";

    if (($erro || $edit) && $query_urm->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_urm->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_urm->record[0] . "' " . $selected . ">" . $query_urm->record[4] . ' - ' . $mes[$query_urm->record[3]-1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>