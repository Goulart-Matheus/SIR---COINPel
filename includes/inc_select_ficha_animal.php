
    <?
    $option_place    = !isset($option_place)    || $option_place    == "" ? " uma ficha"       : $option_place;
    
    ?>
    
    <option value="" selected>Selecione <? echo $option_place; ?></option>

<?
    $query_ficha_animal = new Query($bd);

    $query_ficha_animal->exec("SELECT nro_ficha FROM animal  ORDER BY nro_ficha");
    $n_ficha_animal = $query_ficha_animal->rows();
    
    while ($n_ficha_animal--) {
    
        $query_ficha_animal->proximo();
    
        $selected = "";
    
        if (($erro || $edit) && $query_ficha_animal->record[0] == $form_elemento) {
    
            $selected = "selected";
        } else {
    
            if ($query_ficha_animal->record[0] == $form_elemento) {
    
                $selected = "selected";
            }
        }
    
        echo "<option value='" . $query_ficha_animal->record[0] . "' " . $selected . ">" . $query_ficha_animal->record[0] . "</option>";
    }
    
    $option_place = $where = $form_elemento = "";
    ?>


?>

