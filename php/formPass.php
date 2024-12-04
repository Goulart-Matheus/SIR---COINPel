<?
$dispensa_validacao_senha = true;
include('../includes/session.php');
include_once('../includes/dashboard/header.php');

function senhaValida($senha) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{8,}$/', $senha);
    // return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&;*!])[\w$@]{7,}$/', $senha);
}

if(isset($add)){
    include "../class/class.valida.php";

	$valida_atual=new Valida($form_atual,'Senha Atual');
	$valida_atual->TamMinimo(1);
	$erro.=$valida_atual->PegaErros();
 
	$valida_nova=new Valida($form_nova,'Nova Senha');
	$valida_nova->TamMinimo(8);
	$erro.=$valida_nova->PegaErros();
 
	$valida_confirm=new Valida($form_confirm,'Confirmação');
	$valida_confirm->TamMinimo(8);
	$erro.=$valida_confirm->PegaErros();
}

if (!$erro && isset($add)) {
    $query->exec("SELECT login, senha, nome FROM usuario WHERE login='".$auth->getUser()."'");
    if(!$query->rows())
        echo callException('Usuário inválido', 1);
    else {
        $query->proximo();
        if(sha1(trim($form_atual)) != trim($query->record[1])){
            echo callException('Senha atual inválida', 1);
        }else{
            if(senhaValida($form_nova)){
                if(trim($form_nova) != trim($form_confirm))
                    echo callException('Nova senha diferente de confirmação de senha', 1);
                else {
                    $query->begin();
                    $nova_senha = sha1(trim($form_nova));
                    $query->exec("UPDATE usuario SET senha='$nova_senha' , alterou_senha = 'S'  WHERE login='".$auth->getUser()."'");
                    $query->commit();
                    $_SESSION['PHP_AUTH_PW2'] = sha1(trim($form_nova));
                }
            }else{
                echo callException('A senha deve conter os caracteres obrigatórios.', 1);
            }
        }
    }
}

$query->exec("SELECT descricao FROM mensagem");
$query->proximo();
?>
<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
    <div class="card p-1">
        <div class="card-header border-bottom-0">
            <div class="text-center">
                <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
            </div>
            <div class="row text-center">
                <div class="col-12 col-sm-4 offset-sm-4">
                    <?if($erro) echo callException($erro, 2);?>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="form-group">
                <label for="form_atual">Senha Atual</label>
                <input type="password" class="form-control input-focus" name="form_atual" value="<? if($erro) echo $form_atual;?>">
            </div>
            <div class="form-group">
                <label for="form_nova">Nova Senha</label>
                <input type="password" class="form-control" id="form_nova" name="form_nova" onkeyup="validarSenhaForca()" value="<? if($erro) echo $form_nova;?>">
                <small id="passwordHelpBlock" class="form-text text-muted">
                    Sua senha precisa ter 8 caracteres ou mais com letras minusculas, letras maiusculas e números.
                </small>
            </div>
            <div class="form-group row">
                <div class="col-sm-5">
                    <div id="erroSenhaForca"></div>
                </div>
                <label class="col-sm-2 col-form-label"></label>
            </div>
            <div class="form-group">
                <label for="form_confirm">Confirmação</label>
                <input type="password" class="form-control" name="form_confirm" value="<? if($erro) echo $form_confirm;?>">
            </div>
        </div>
        <div class="card-footer border-top-0 bg-transparent">
            <div class="text-right">
                <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                <input class="btn btn-info" type="submit" name="add" value="Salvar">
            </div>
        </div>
    </form>
</section>

<script>
    function validarSenhaForca(){
      var senha = document.getElementById('form_nova').value;
      var forca = 0;

      if(senha.length >= 8){

        forca += 10;
      }

      if((senha.length >= 8) && (senha.match(/[a-z]+/))){

        forca += 25;
      }

      if((senha.length >= 8) && (senha.match(/[A-Z]+/))){

        forca += 25;
      }

      // if((senha.length >= 8) && (senha.match(/[@#$%&;*!]/))){
      //   forca += 25;
      // }

      if(senha.match(/[0-9]+/g)){
        forca += 40;
      }
      mostrarForca(forca);
    }

    function mostrarForca(forca){

      if(forca < 30 ){
        document.getElementById("erroSenhaForca").innerHTML = '<div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>';
      }else if((forca >= 30) && (forca < 50)){
        document.getElementById("erroSenhaForca").innerHTML = '<div class="progress"><div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>';
      }else if((forca >= 50) && (forca < 70)){
        document.getElementById("erroSenhaForca").innerHTML = '<div class="progress"><div class="progress-bar bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div></div>';
      }else if((forca >= 70) && (forca <= 100)){
        document.getElementById("erroSenhaForca").innerHTML = '<div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Forte</div></div>';
      }
    }
</script>
<? include_once('../includes/dashboard/footer.php'); ?>
