<?
$option_place    = !isset($option_place)    || $option_place    == "" ? " um animal"       : $option_place;

?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_animal = new Query($bd);

$query_animal->exec("SELECT id_animal, nro_ficha, nro_chip, id_pelagem, id_especie, sexo, observacao FROM animal  ORDER BY nro_ficha");
$n_animal = $query_animal->rows();

while ($n_animal--) {

    $query_animal->proximo();

    $selected = "";

    if (($erro || $edit) && $query_animal->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_animal->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_animal->record[0] . "' " . $selected . ">" . $query_animal->record[1] . "</option>";
}

$option_place = $where = $form_elemento = "";
?>