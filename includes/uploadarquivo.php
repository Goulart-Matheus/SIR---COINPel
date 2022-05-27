<?
$config = array();
$arquivo = isset($_FILES["form_imagem"]) ? $_FILES["form_imagem"] : FALSE;
$config["tamanho"] = 900000;
$config["largura"] = 3000;
$config["altura"]  = 3000;
if ($arquivo) {
    if (!preg_match("@^image/(jpg|jpeg|bmp|git|png)$@i", $arquivo["type"])) {
        $erro .= "Arquivo em formato inválido! A imagem deve ser jpg, jpeg, bmp, gif ou png.<br>";
    }
	else {
        if ($arquivo["size"] > $config["tamanho"]) $erro .= "Arquivo em tamanho muito grande! A imagem deve ser de no máximo " . $config["tamanho"] . " bytes.<br>";
        $tamanhos = getimagesize($arquivo["tmp_name"]);
        if ($tamanhos[0] > $config["largura"]) $erro .= "Largura da imagem não deve ultrapassar " . $config["largura"] . " pixels<br>";
        if ($tamanhos[1] > $config["altura"])  $erro .= "Altura da imagem não deve ultrapassar " . $config["altura"] . " pixels";
    }

    if (!$erro) {
        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);
        $imagem_nome = md5(uniqid(time())) . "." . $ext[1];
        $imagem_dir = "../$diretorio/" . $imagem_nome;
	    if (!move_uploaded_file($arquivo["tmp_name"], $imagem_dir)) $erro .= "Erro ao enviar o arquivo !<br>";
    }
}
?>
