<?
$tipo_de_marca = array(
    'z' => 'Não especificado',
    's' => 'Símbolos',
    'a' => 'Animais',
    'n' => 'Números',
    'p' => 'Letras - Iniciais do proprietário',
    'l' => 'Letras - Iniciais da propriedade',
    'o' => 'Letras - Outros',
);

function print_options_for_tipo_de_marca($selected = 'z')
{
    global $tipo_de_marca;

    foreach($tipo_de_marca as $sigla => $tipo)
    {
        $selected_str = ($sigla == $selected) ? " selected" : "";
        echo "<option value=\"{$sigla}\"{$selected_str}>{$tipo}</option>";
    }
}
?>