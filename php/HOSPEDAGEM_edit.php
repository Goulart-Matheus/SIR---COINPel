<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Hospedaria', 'fas fa-heading', 'HOSPEDAGEM_viewDados.php');
$tab->setTab('Hospegem', 'fa-solid fa-heading', 'HOSPEDAGEM_cover.php?id_hospedagem=' . $id_hospedagem);
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

/*
$query->exec("SELECT id_hospedagem , id_animal , valor , endereco_recolhimento , id_bairro , id_responsavel , dt_entrada , dt_retirada , situacao

FROM hospedagem

WHERE id_hospedagem = " . $id_hospedagem);
*/

$query->exec("SELECT  h.id_hospedagem , h.id_animal  , endereco_recolhimento , b.id_bairro , r.id_responsavel , h.dt_entrada , h.dt_retirada ,m.id_motivo ,h.id_urm ,h.nro_boleto,h.situacao, h.valor

FROM hospedagem as h, bairro as b , responsavel as r, motivo as m , urm as u WHERE b.id_bairro = h.id_bairro AND r.id_responsavel = h.id_responsavel AND h.id_motivo = m.id_motivo ");

$query->result($query->linha);

?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

        <input type="hidden" name="id_hospedagem" value="<? echo $query->record[0]; ?>">

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

                        if (!$erro && isset($edit)) {

                            $query->begin();

                            if (!$erro && isset($edit)) {

                                $query->begin();

                                $itens = array(
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
                                    $form_situacao

                                );

                                $where = array(0 => array('id_hospedagem', $id_hospedagem));
                                $query->updateTupla('hospedagem', $itens, $where);

                                $query->commit();
                            }
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
                        <input type="text" class="form-control" name="form_id_hospedagem" id="form_id_hospedagem" maxlength="100" value="<? if ($edit) echo $form_id_hospedagem;
                                                                                                                                            else echo trim($query->record[0]) ?>">
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_animal"><span class="text-danger">*</span> Ficha do Animal</label>
                        <select name="form_id_animal" id="form_id_animal" class="form-control" required>
                            <?
                            $form_elemento = $edit ? $form_id_animal : $query->record[1] . "";
                            include("../includes/inc_select_animal.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha um Animal.
                        </div>


                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_endereco_recolhimento"><span class="text-danger">*</span> Endereço de Recolhimento</label>
                        <input type="text" class="form-control" name="form_endereco_recolhimento" id="form_endereco_recolhimento" maxlength="100" value="<? if ($edit) echo $form_endereco_recolhimento;
                                                                                                                                                            else echo trim($query->record[2]) ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_bairro"><span class="text-danger">*</span> Bairro</label>
                        <select name="form_id_bairro" id="form_id_bairro" class="form-control" required>
                            <?
                            $form_elemento = $edit ? $form_id_bairro : $query->record[3];
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
                            $form_elemento = $edit ? $form_id_responsavel : $query->record[4];
                            include("../includes/inc_select_responsavel.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o Responsavel.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_entrada"><span class="text-danger">*</span>Data Entrada</label>
                        <input type="date" class="form-control" name="form_dt_entrada" id="form_dt_entrada" maxlength="100" value="<? if ($erro) echo $form_dt_entrada;
                                                                                                                                    else echo trim($query->record[5]); ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_retirada"><span class="text-danger">*</span> Data Retirada</label>
                        <input type="date" class="form-control" name="form_dt_retirada" id="form_dt_retirada" maxlength="100" value="<? if ($erro) echo $form_dt_retirada;
                                                                                                                                        else echo trim($query->record[6]); ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_motivo"><span class="text-danger">*</span> Motivo</label>
                        <select name="form_id_motivo" id="form_id_motivo" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_id_motivo : $query->record[7];
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
                            $form_elemento = $edit ? $form_id_urm : $query->record[8];
                            include("../includes/inc_select_urm.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha a URM.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-4">

                        <label for="form_valor"><span class="text-danger">*</span> Valor</label>
                        <input type="text" class="form-control" name="form_valor" id="form_valor" maxlength="100" value="<? if ($edit) echo $form_valor;
                                                                                                                            else echo trim($query->record[11]) ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_boleto"><span class="text-danger">*</span> Numero Boleto</label>
                        <input type="text" class="form-control" name="form_nro_boleto" id="form_nro_boleto" maxlength="100" value="<? if ($edit) echo $form_nro_boleto;
                                                                                                                                    else echo trim($query->record[9]) ?>">
                    </div>

                    <div class="form-group col-12 col-md-8">
                        <label for="form_observacao"></span> Observação</label>
                        <input type="text" class="form-control" name="form_observacao" id="form_observacao" maxlength="200" value="<? if ($edit) echo $form_observacao; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_situacao"><span class="text-danger">*</span> Situação</label>
                        <select class="form-control" name="form_situacao" id="form_situacao">
                            <option value="S" <? if ($edit && $form_situacao == 'S') echo 'selected';
                                                else {
                                                    if (!$edit && $query->record[10] == "S") {
                                                        echo 'selected';
                                                    }
                                                }  ?>>Disponível</option>
                            <option value="N" <? if ($edit && $form_situacao == 'N') echo 'selected';
                                                else {
                                                    if (!$edit && $query->record[10] == "N") {
                                                        echo 'selected';
                                                    }
                                                }  ?>>Não Disponível</option>

                        </select>
                    </div>

                </div>

            </div>

            <div class="card-footer border-top-0 bg-transparent">

                <div class="card-footer bg-light-2">
                    <?
                    $btns = array('reload', 'edit');
                    include('../includes/dashboard/footer_forms.php');
                    ?>
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
    $("#form_id_urm").on('change', function() {

        var id_urm = $("#form_id_urm").val();

        $.ajax({
            type: 'POST',
            url: '../../../includes/ajax_atualiza_valor_urm.php',
            data: {

                "id_urm": id_urm

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