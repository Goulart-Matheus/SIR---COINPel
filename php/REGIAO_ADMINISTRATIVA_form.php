<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();


$tab->setTab('Região Administrativa', 'fas fa-file-code', 'REGIAO_ADMINISTRATIVA_viewDados.php');
$tab->setTab('Nova Região Administrativa', 'fas fa-plus', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

?>
<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">

                    <div class="col-12 col-sm-4 offset-sm-4">

                        <?
                        if (isset($add)) {
                            include "../class/class.valida.php";

                            $valida = new Valida($form_nome, 'Nome');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'denuncias.regiao_administrativa',
                                array(
                                    $form_nome,
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,
                                    $form_habilitado,
                                )
                            );

                            $query->commit();                      
                        }

                        if ($erro)

                            echo callException($erro, 2);

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">

                    <div class="form-group col-12 col-md-6">
                        <label for="form_nome"><span class="text-danger">*</span> Nome</label>
                        <input type="text" class="form-control" name="form_nome" id="form_nome" maxlength="100" value="<? if ($erro) echo $form_nome; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_habilitado"><span class="text-danger">*</span> Habilitado</label>
                        <select name="form_habilitado" id="form_habilitado" class="form-control">
                            <option value="S" <? if ($erro && $form_habilitado == 'S') echo 'selected'  ?>>Sim</option>
                            <option value="N" <? if ($erro && $form_habilitado == 'N') echo 'selected'; ?>>Não</option>
                        </select>
                    </div>

                </div>

            </div>

            <div class="card-footer bg-light-2">
                <?
                $btns = array('clean', 'save');
                include('../includes/dashboard/footer_forms.php');
                ?>
            </div>

        </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
?>