<?

include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../includes/variaveisAmbiente.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', 'HOSPEDAGEM_form.php');
$tab->setTab('Pesquisar', 'fas fa-search', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

?>

<section class="content">

    <form method="POST" action="HOSPEDAGEM_viewDados.php">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <? if ($erro) echo callException($erro, 2); ?>
                    </div>
                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">

                    <div class="form-group col-12 col-md-3">
                        <label for="form_id_hospedagem"></span> Hospedagem</label>
                        <input type="text" class="form-control" name="form_id_hospedagem" id="form_id_hospedagem" maxlength="100" value="<? if ($erro) echo $form_id_hospedagem; ?>">
                    </div>

                    <div class="form-group col-12 col-md-3">
                        <label for="form_id_animal"></span>Animal</label>
                        <input type="text" class="form-control" name="form_id_animal" id="form_id_animal" maxlength="100" value="<? if ($erro) echo $form_id_animal; ?>">
                    </div>

                    <div class="form-group col-12 col-md-3">
                        <label for="form_id_urm"></span>Urm</label>
                        <input type="text" class="form-control" name="form_id_urm" id="form_id_urm" maxlength="100" value="<? if ($erro) echo $form_id_urm; ?>">
                    </div>

                    <div class="form-group col-12 col-md-3">
                        <label for="form_id_valor"></span>Valor</label>
                        <input type="text" class="form-control" name="form_valor" id="form_valor" maxlength="100" value="<? if ($erro) echo $form_valor; ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_endereco_recolhimento"></span> Endereço de Recolhimento</label>
                        <input type="text" class="form-control" name="form_endereco_recolhimento" id="form_endereco_recolhimento" maxlength="100" value="<? if ($erro) echo $form_endereco_recolhimento; ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_bairro"></span> Bairro</label>
                        <select name="form_id_bairro" id="form_id_bairro" class="form-control">
                            <?
                            $form_elemento = $erro ? $form_bairro : "";
                            include("../includes/inc_select_bairro.php"); ?>
                        </select>
                        
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_responsavel"></span> Responsável</label>
                        <input type="text" class="form-control" name="form_id_responsavel" id="form_id_responsavel" maxlength="100" value="<? if ($erro) echo $form_id_responsavel; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_entrada"></span>Data Entrada</label>
                        <input type="date" class="form-control" name="form_dt_entrada" id="form_dt_entrada" maxlength="100" value="<? if ($erro) echo $form_dt_entrada; ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_retirada"></span> Data Retirada</label>
                        <input type="date" class="form-control" name="form_dt_retirada" id="form_dt_retirada" maxlength="100" value="<? if ($erro) echo $form_dt_retirada; ?>">
                    </div>
               
                    <div class="form-group col-12 col-md-4">
                        <label for="form_situacao"></span> Situação</label>
                        <select class="form-control" name="form_situacao" id="form_situacao">
                            <option value="S" selected>Ativo</option>
                            <option value="N">Não Ativo</option>

                        </select>
                    </div>

                </div>

                <div class="card-footer border-top-0 bg-transparent">

                    <div class="text-center">
                        <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                        <input class="btn btn-info" type="submit" name="add" value="Buscar">
                    </div>

                </div>

            </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
?>