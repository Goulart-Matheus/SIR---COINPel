<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar', 'fas fa-search', 'MOTIVO_view.php');

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
                                'motivo',
                                array(
                                    trim($form_descricao),
                                    $_habilitado,
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
                        <label for="form_descricao"><span class="text-danger">*</span> Nome do Solicitante</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" maxlength="100" value="<? if ($erro) echo $form_descricao; ?>">
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_dt_solicitacao"><span class="text-danger">*</span> Data da Solicitação</label>
                        <input type="date" class="form-control" name="form_dt_solicitacao" id="form_dt_solicitacao" required value="<? if ($erro) echo $form_dt_solitacao; ?>" />
                        <div class="invalid-feedback">
                            Preencha a data da Solicitação.
                        </div>
                    </div>

                </div>

            </div>

            <div class="card-footer border-top-0 bg-transparent">
                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info" type="submit" name="add" value="Salvar">
                </div>
            </div>

        </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
?>
