<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar', 'fas fa-search', 'URM_view.php');

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

                            $valida = new Valida($form_valor, 'Valor');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_mes_referencia, 'mes_referencia');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_ano_referencia, 'ano_referencia');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'urm',
                                array(
                                    trim($form_valor),
                                    $form_ativo,
                                    $form_mes_referencia,
                                    $form_ano_referencia,
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
                        <label for="form_valor"><span class="text-danger">*</span> Valor</label>
                        <input type="text" class="form-control" name="form_valor" id="form_valor" maxlength="100" value="<? if ($erro) echo $form_valor; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_ativo"><span class="text-danger">*</span> Ativo</label>
                        <select class="form-control"name="form_ativo"id= "form_ativo"> 
                            <option value="S" selected >Sim</option>
                            <option value="N"           >Não</option>
                            
                        </select>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_mes_referencia"><span class="text-danger">*</span> Mês Referencia</label>
                        <input type="text" class="form-control" name="form_mes_referencia" id="form_mes_referencia" maxlength="100" value="<? if ($erro) echo $form_mes_referencia; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_ano_referencia"><span class="text-danger">*</span> Ano Referencia</label>
                        <input type="text" class="form-control" name="form_ano_referencia" id="form_ano_referencia" maxlength="100" value="<? if ($erro) echo $form_ano_referencia; ?>">
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