<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Pelagens', 'fas fa-horse', 'PELAGEM_viewDados.php');
$tab->setTab('Nova Pelagem', 'fas fa-plus', $_SERVER['PHP_SELF']);


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

                            $valida = new Valida($form_descricao, 'Descrição');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'pelagem',
                                array(
                                    trim($form_descricao),
                                    $form_habilitado,
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,
                                    
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
                        <label for="form_descricao"><span class="text-danger">*</span> Cadastro Pelagem</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" maxlength="100" value="<? if ($erro) echo $form_descricao; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_nome"><span class="text-danger">*</span> Habilitado</label>
                        <select class="form-control" name="form_habilitado " id= "form_habilitado "> 
                            <option value="S" <? if ($erro && $form_habilitado == "S") echo 'selected'; else echo 'selected'; ?>>Sim</option>
                            <option value="N" <? if ($erro && $form_habilitado == "N")?>>Não</option>
                            
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