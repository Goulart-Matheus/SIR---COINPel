<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab ('Hospedaria', 'fas fa-heading','HOSPEDAGEM_viewDados.php');
$tab->setTab('Nova Hospedaria', 'fas fa-plus', $_SERVER['PHP_SELF']);
//$tab->setTab('Pesquisar', 'fas fa-search', 'HOSPEDAGEM_view.php');

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

                            $valida = new Valida($form_id_hospedagem, 'Id_hospedagem');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_id_animal, 'Id_animal');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_dt_entrada, 'Dt_entrada');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_endereco_recolhimento, 'Endereco_recolhimento');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_id_bairro, 'Id_bairro');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_observacao, 'Observacao');
                            $valida->TamMinimo(0);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_dt_retirada, 'Dt_retirada');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_id_responsavel, 'Id_responsavel');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_id_motivo, 'Id_motivo');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_id_urm, 'Id_urm');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_valor, 'Valor');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_nro_boleto, 'nro_boleto');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_situacao, 'Situacao');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'hospedagem',
                                array(
                                   // trim($form_id_hospedagem),
                                    $form_id_animal,
                                    $form_dt_entrada,
                                    $form_endereco_recolhimento,
                                    $form_id_bairro,
                                    $form_observacao,
                                    $form_dt_retirada,
                                    $form_id_responsavel,
                                    $form_id_motivo,
                                    $form_id_urm,
                                    $form_valor,
                                    $form_nro_boleto,
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,
                                    $form_situacao,

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
                        <label for="form_id_hospedagem"><span class="text-danger">*</span> Hospedagem</label>
                        <input type="text" class="form-control" name="form_id_hospedagem" id="form_id_hospedagem" maxlength="100" value="<? if ($erro) echo $form_id_hospedagem; ?>">
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_animal"><span class="text-danger">*</span> Ficha do Animal</label>
                        <select name="form_id_animal" id="form_id_animal" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_id_animal : "";
                            include("../includes/inc_select_animal.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha um Animal.
                        </div>


                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_endereco_recolhimento"><span class="text-danger">*</span> Endereço de Recolhimento</label>
                        <input type="text" class="form-control" name="form_endereco_recolhimento" id="form_endereco_recolhimento" maxlength="100" value="<? if ($erro) echo $form_endereco_recolhimento; ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_bairro"><span class="text-danger">*</span> Bairro</label>
                        <select name="form_id_bairro" id="form_id_bairro" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_id_bairro : "";
                            include("../includes/inc_select_bairro.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o bairro.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_responsavel"><span class="text-danger">*</span> Responsavel</label>
                        <select name="form_id_responsavel" id="form_id_responsavel" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_id_responsavel : "";
                            include("../includes/inc_select_responsavel.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o Responsavel.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_entrada"><span class="text-danger">*</span>Data Entrada</label>
                        <input type="date" class="form-control" name="form_dt_entrada" id="form_dt_entrada" maxlength="100" value="<? if ($erro) echo $form_dt_entrada; ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_retirada"><span class="text-danger">*</span> Data Retirada</label>
                        <input type="date" class="form-control" name="form_dt_retirada" id="form_dt_retirada" maxlength="100" value="<? if ($erro) echo $form_dt_retirada; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_motivo"><span class="text-danger">*</span> Motivo</label>
                        <select name="form_id_motivo" id="form_id_motivo" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_id_motivo : "";
                            include("../includes/inc_select_motivo.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o Motivo.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_urm"><span class="text-danger">*</span>URM</label>
                        <select name="form_id_urm" id="form_id_urm" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_id_urm : "";
                            include("../includes/inc_select_urm.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha a URM.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_valor"><span class="text-danger">*</span> Valor</label>
                        <input type="text" class="form-control" name="form_valor" id="form_valor" maxlength="100" value="<? if ($erro) echo $form_valor; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_boleto"><span class="text-danger">*</span> Numero Boleto</label>
                        <input type="text" class="form-control" name="form_nro_boleto" id="form_nro_boleto" maxlength="100" value="<? if ($erro) echo $form_nro_boleto; ?>">
                    </div>

                    <div class="form-group col-12 col-md-8">
                        <label for="form_observacao"></span> Observação</label>
                        <input type="text" class="form-control" name="form_observacao" id="form_observacao" maxlength="200" value="<? if ($erro) echo $form_observacao; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_situacao"><span class="text-danger">*</span> Situação</label>
                        <select class="form-control" name="form_situacao" id="form_situacao">
                            <option value="S" selected>Ativo</option>
                            <option value="N">Não Ativo</option>

                        </select>
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

<script src="../assets/js/jquery.js"></script>
<script type="text/javascript">

$("#form_id_urm").on('change',function(){

    var id_urm = $("#form_id_urm").val();    

    $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_atualiza_valor_urm.php',
                data: {
                   
                   "id_urm":id_urm                                    
                   
                },
                beforeSend: function() {

                    console.log("Enviado ok");
                                 
                },
                success: function(response) {
                   
                   $("#form_valor").val(response['valor']);                 

                 },
                error: function(erro) {

                    // console.log(erro);

                }
            });       
});

</script>
