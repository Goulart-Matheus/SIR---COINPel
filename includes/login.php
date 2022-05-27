<?
#VARIAVEIS DE CONTOLE DA SECAO
$secao_name = $system->getSession(); //Define o nome da seção que será utilizada pelo sistema

session_start();

// Se o usuário ja está logado.
if ($_SESSION['c01npel'] == "permitido")
{
    include('../class/class.authentication.php');
    $auth = new Authentication($queryauth,$secao_name);
    $auth->startSession();

    if(!$auth->verifyUser($_SESSION['PHP_AUTH_USER2'], $_SESSION['PHP_AUTH_PW2']))
    {
        $auth->destroySession();
        header("location: index.php");
        exit;
    }

    if($auth->getAltPass() == 'N' && !isset($dispensa_validacao_senha))
    {
        header("location: formPass.php");
        exit;
    }

    if (!$auth->verifyApplication($_SERVER['PHP_SELF']))
    {
        header("location: 401.php");
        exit;
    }
}

// Se o usuário esta tentando logar.
if (isset($_POST['submit']))
{
    include_once('../class/class.authentication.php');
    $auth = new Authentication($queryauth,$secao_name);
    $auth->startSession();

    $login_success = $auth->verifyUser($_POST['PHP_AUTH_USER'], $_POST['PHP_AUTH_PW']);

    if ($login_success)
    {
        $_SESSION['c01npel'] = "permitido";
        $_SESSION['PHP_AUTH_USER2'] = $_POST['PHP_AUTH_USER'];
        $_SESSION['PHP_AUTH_PW2'] = $_POST['PHP_AUTH_PW'];
        $_SESSION['_login'] = $_POST['PHP_AUTH_USER'];

        if($auth->getAltPass() == 'N' && !isset($dispensa_validacao_senha))
        {
            header("location: formPass.php");
            exit;
        }

        header('location: index.php');
        exit;
    }
}

if($_SESSION['c01npel'] != "permitido"){

    include_once('auth/header.php');

?>
    <div class="text-center title mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-4 offset-md-4">
                    <img src="../assets/images/logo-company-light.png" class="img-fluid" alt="Logo <? echo $system->getTitulo(); ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 offset-md-3 col-md-6 offset-lg-4 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="p-3">
                                <h4 class="text-muted font-18 m-b-5 text-center">Sistema SIR</h4>
                                <p class="text-muted text-center">Entre para iniciar uma nova sessão</p>

                                <form class="form-horizontal m-t-30" method="POST" action="<? echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="PHP_AUTH_USER" class="col-form-label">Usuário:</label>
                                            <input type="text" id="PHP_AUTH_USER" name="PHP_AUTH_USER" value="<?=$PHP_AUTH_USER?>" class="form-control">
                                            <? if(! (isset($login_success) ? $login_success : true)) { ?>
                                                <span style="color: red !important;">
                                                    <strong>Essas credenciais não foram encontradas em nossos registros.</strong>
                                                </span>
                                            <? } ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="PHP_AUTH_PW" class="col-form-label">Senha:</label>
                                            <input type="password" id="PHP_AUTH_PW" name="PHP_AUTH_PW" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row m-t-20">
                                        <div class="col-12 text-right">
                                            <button class="btn btn-primary w-md waves-effect waves-light" name="submit" type="submit">Entrar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?
    include_once('auth/footer.php');?>
    <script>
        $( "#PHP_AUTH_USER" ).focus();
        //document.PHP_AUTH_USER.focus();
    </script>
    <? exit;} ?>