
    <?
    $option_place    = !isset($option_place)    || $option_place    == "" ? " um numero de chip"       : $option_place;
    
    ?>
    
    <option value="" selected>Selecione <? echo $option_place; ?></option>

<?
    $query_chip = new Query($bd);

    $query_chip->exec("SELECT id_animal, nro_ficha, nro_chip, id_pelagem, id_especie, sexo, observacao FROM animal  ORDER BY nro_ficha");
    $n_chip = $query_chip->rows();
    
    while ($n_chip--) {
    
        $query_chip->proximo();
    
        $selected = "";
    
        if (($erro || $edit) && $query_chip->record[2] == $form_elemento) {
    
            $selected = "selected";
        } else {
    
            if ($query_chip->record[2] == $form_elemento) {
    
                $selected = "selected";
            }
        }
    
        echo "<option value='" . $query_chip->record[2] . "' " . $selected . ">" . $query_chip->record[2] . "</option>";
    }
    
    $option_place = $where = $form_elemento = "";
    ?>


?>

