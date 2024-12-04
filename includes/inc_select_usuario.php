<?
isset($erro) ?: $erro = null;
isset($edit) ?: $edit = null;
$option_place = !isset($option_place) || $option_place == "" ? "o Usuário"                                   : $option_place;
$where_usuario  =  isset($where_usuario)  && $where_usuario        != "" ? $where_usuario . " AND login != 0"    : " WHERE login != 0";
?>

<option value="" selected>Selecione <? echo $option_place; ?></option>

<?
$query_usuario = new Query($bd);

$query_usuario->exec("SELECT login, nome FROM public.usuario " . $where_usuario . " ORDER BY nome");
$n__usuario = $query_usuario->rows();

while ($n__usuario--) {

    $query_usuario->proximo();

    $selected = "";

    if (($erro || $edit) && $query_usuario->record[0] == $form_elemento) {

        $selected = "selected";
    } else {

        if ($query_usuario->record[0] == $form_elemento) {

            $selected = "selected";
        }
    }

    echo "<option value='" . $query_usuario->record[0] . "' " . $selected . ">" . $query_usuario->record[1] . "</option>";
}

$option_place = $where_usuario = $form_elemento = "";