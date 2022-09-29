<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');



if ($id_animal != "") {


    $query->exec("SELECT ar.id_animal, ar.id_responsavel, nro_chip, nro_ficha, e.descricao, p.descricao, a.sexo
                  FROM animal as a, animal_responsavel as ar, pelagem as p, especie as e                       
                  WHERE a.id_animal = ar.id_animal AND
                        p.id_pelagem = a.id_pelagem AND
                        e.id_especie = a.id_especie AND
                        a.id_animal = $id_animal
                ");
    $query->proximo();
}


$tab = new Tab();

$tab->setTab('Atendimentos', 'fa-solid fa-house-chimney-medical', 'HOSPEDAGEM_viewDados.php');
$tab->setTab('Novo Atendimento', 'fas fa-plus', $_SERVER['PHP_SELF']);

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


                            $valida = new Valida($form_id_responsavel, 'Id_responsavel');
                            $valida->TamMinimo(0);
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

                            $valida = new Valida($form_situacao, 'Situacao');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) {

                            if ($form_dt_retirada == "") {
                                $form_dt_retirada = "NULL";
                            }
                            if ($form_nro_boleto == "") {
                                $form_nro_boleto = "NULL";
                            }
                            if ($form_observacao == "") {
                                $form_observacao = "NULL";
                            }
                            if ($form_id_responsavel == "") {
                                $form_id_responsavel =  NULL;
                            }
                            $query->begin();

                            $query->insertTupla(
                                'hospedagem',
                                array(

                                    $form_id_animal,
                                    $form_dt_entrada,
                                    $form_endereco_recolhimento,
                                    $form_id_bairro,
                                    $form_observacao,
                                    trim($form_dt_retirada),
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

                <div class="form-row mt-0">
                    <div class="form-group bg-green mb-3 col-12 col-md-12">

                        <p class="card-header p-2"><i class="fa-solid fa-file-invoice"></i> Cadastro Inicial</p>


                    </div>


                    <div class="form-group col-12 col-md-12">


                        <div class="row mx-0 ">
                            <div class="col-6 text-left px-0">
                                <a class=" btn btn-success mx-1 text-light bg-green" data-toggle="modal" data-target="#PESQUISA_ANIMAL_modal" title="Pesquisar Animal"><i class="fa fa-search"></i> Pesquisar Animal</a>
                            </div>

                            <div class="col-6 text-right px-0 ">
                                <a title='Adicionar Animal' class='btn btn-success text-light bg-green' data-toggle='modal' data-target='#ANIMAL_modal'>+ Adicionar animal <i class='fa fa-dog text-light'></i></a>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="form_id_animal" id="form_id_animal">

                    <!---
                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_ficha"><span class="text-danger">*</span> Espécie</label>
                        <input class="form-control-plaintext" type="text" name="" id="form_especie" disabled>
                    </div>
                    <div class="form-group col-12 col-md-4">
                    <label for="form_nro_ficha"><span class="text-danger">*</span> Pelagem</label>
                        <input class="form-control-plaintext" type="text" name="" id="form_pelagem" disabled>
                    </div>
                    <div class="form-group col-12 col-md-4">
                    <label for="form_nro_ficha"><span class="text-danger">*</span> Sexo</label>
                        <input class="form-control-plaintext" type="text" name="" id="form_sexo" disabled>
                    </div>
                    --->

                    <div class="form-group col-12 col-md-4">
                        <label for="form_especie"><span class="text-danger">*</span> Espécie</label>
                        <input class="form-control" type="text" name="form_especie" id="form_especie" value="<?php if ($erro) echo $form_especie;
                                                                                                                else echo $query->record[4] ?>" disabled>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_ficha"><span class="text-danger">*</span> Pelagem</label>
                        <input class="form-control" type="text" name="" id="form_pelagem" value="<?php if ($erro) echo $form_pelagem;
                                                                                                    else echo $query->record[5] ?>" disabled>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_ficha"><span class="text-danger">*</span> Sexo</label>
                        <input class="form-control" type="text" name="" id="form_sexo" value="<?php if ($erro) echo $form_sexo;
                                                                                                else echo ($query->record[6] == 'M' ? 'Macho' : 'Fêmea') ?>" disabled>
                    </div>


                    <div class="form-group col-12 col-md-6">

                        <label for="form_nro_ficha"><span class="text-danger">*</span> Ficha do Animal</label>
                        <input type="text" name="form_nro_ficha" id="form_nro_ficha" class="form-control col-12" value="<? if ($erro) echo $form_nro_ficha;
                                                                                                                        else echo $query->record[3]; ?>" required disabled>
                        <!--
        <select name="form_nro_ficha" id="form_nro_ficha" class="form-control select2_ficha_animal" required value="<? if ($erro) echo $form_nro_ficha; ?>">
            <?
            $where = $form_elemento = $erro ? $form_nro_ficha : $query->record[3];
            include("../includes/inc_select_ficha_animal.php");
            ?>
        </select>
        --->
                        <div class="invalid-feedback">
                            Escolha um Animal.
                        </div>

                    </div>

                    <div class="form-group col-12 col-md-6">

                        <label for="form_nro_chip"><span class="text-danger">*</span> Numero do Chip Animal</label>
                        <input type="text" name="form_nro_chip" id="form_nro_chip" class="form-control" value="<? if ($erro) echo $form_nro_chip;
                                                                                                                else echo $query->record[2]; ?>" required disabled>
                        <!--
        <select name="form_nro_chip" id="form_nro_chip" class="form-control select2_nro_chip" required value="<? if ($erro) echo $form_nro_chip;
                                                                                                                else echo $form_nro_chip; ?>">
            <?
            $where = $form_elemento = $erro ? $form_nro_chip : $query->record[2];
            include("../includes/inc_select_chip.php");
            ?>
        </select>
        --->

                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_endereco_recolhimento"><span class="text-danger">*</span> Endereço de Recolhimento</label>
                        <input type="text" class="form-control" name="form_endereco_recolhimento" id="form_endereco_recolhimento" maxlength="100" value="<? if ($erro) echo $form_endereco_recolhimento; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
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

                    <div class="form-group col-12 col-md-6">
                        <label for="form_dt_entrada"><span class="text-danger">*</span>Data Entrada</label>
                        <input type="date" class="form-control" name="form_dt_entrada" id="form_dt_entrada" maxlength="100" value="<? if ($erro) echo $form_dt_entrada;
                                                                                                                                    else echo $_data ?>">
                    </div>

                    <div class="form-group col-12 col-md-3">
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

                    <div class="form-group col-12 col-md-3">
                        <label for="form_situacao"><span class="text-danger">*</span> Situação</label>
                        <select class="form-control" name="form_situacao" id="form_situacao">
                            <option value="S" selected>Em Atendimento</option>
                            <option value="N">Atendimento Finalizado</option>

                        </select>
                    </div>



                </div>

                <div class="form-row mt-0">
                    <div class="form-group bg-green mb-3 col-12 col-md-12">

                        <p class="card-header p-2"><i class="fa-solid fa-file-invoice"></i> Cadastro Retirada</p>


                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="form_dt_retirada"><span class="text-danger">*</span> Data Retirada</label>
                        <input type="date" class="form-control" name="form_dt_retirada" id="form_dt_retirada" maxlength="100" value="<? if ($erro) echo $form_dt_retirada; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_responsavel"><span class="text-danger">*</span> Responsavel</label>
                        <select name="form_id_responsavel" id="form_id_responsavel" class="form-control select2_responsavel">
                            <?

                            $where = $form_elemento = $erro ? $form_id_responsavel : "";
                            include("../includes/inc_select_responsavel.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o Responsavel.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-2">
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


                    <div class="form-group col-12 col-md-2">
                        <label for="form_reincidencias"><span class="text-danger">*</span> Reincidências</label>
                        <input class="form-control" name="form_reincidencias" id="form_reincidencias" value="" type="text" disabled>
                    </div>
                    <div class="form-group col-12 col-md-2">
                        <label for="form_valor"><span class="text-danger">*</span> Valor R$</label>
                        <input type="text" class="form-control" name="form_valor" id="form_valor" maxlength="100" value="<? if ($erro) echo $form_valor; ?> " readonly>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_nro_boleto"><span class="text-danger">*</span> Numero Boleto</label>
                        <input type="text" class="form-control" name="form_nro_boleto" id="form_nro_boleto" maxlength="100" value="<? if ($erro) echo $form_nro_boleto; ?>">
                    </div>

                    <div class="form-group col-12 col-md-12">
                        <label for="form_observacao"></span> Observação</label>
                        <!---
        <input type="text" class="form-control" name="form_observacao" id="form_observacao" maxlength="200" value="<? if ($erro) echo $form_observacao; ?>">
        --->
                        <textarea name="form_observacao" class="col-12 col-md-12 form-control" id="form_observacao" rows="3"></textarea>
                    </div>
                </div>

            </div>

            <div class="card-footer bg-light-2">
                <?
                $btns = array('clean', 'save');
                include('../includes/dashboard/footer_forms.php');
                ?>
            </div>

    </form>

</section>

<?

include_once('../includes/dashboard/footer.php');
include('../includes/modal/modal_hospedaria_pesquisa_animal.php');
include('../includes/modal/modal_hospedaria_add_animal.php');

?>

<script type="text/javascript">
    $("#form_nro_ficha, #form_nro_chip").on('change', function() {

        var id_urm = $("#form_id_urm").val();
        var nro_ficha = $("#form_nro_ficha").val();
        var dt_entrada = $("#form_dt_entrada").val();
        var nro_chip = $("#form_nro_chip").val();
        var identificador = $(this).attr('id');

        $.ajax({
            type: 'POST',
            url: '../../../includes/ajax_atualiza_valor_urm.php',
            data: {

                "id_urm": id_urm,
                "nro_ficha": nro_ficha,
                "dt_entrada": dt_entrada,
                "nro_chip": nro_chip,
                "identificador": identificador
            },
            beforeSend: function() {

                console.log("Enviado ok");

            },
            success: function(response) {

                $("#form_valor").val(response['valor']);
                $("#form_nro_chip").prop("selectedIndex", 1).val(response['nro_chip']).select2();
                $("#form_nro_ficha").prop("selectedIndex", 1).val(response['nro_ficha']).select2();
                if (response['id_responsavel'] != 0) {
                    $("#form_id_responsavel").prop("selectedIndex", 1).val(response['id_responsavel']).select2();
                } else {
                    $("#form_id_responsavel").prop("selectedIndex", 0).select2();
                }
                $("#form_id_animal").val(response['id_animal']);

            },
            error: function(erro) {

                // console.log(erro);

            }
        });
    });

    $("#form_id_urm").on('change', function() {


        var id_urm = $("#form_id_urm").val();
        var nro_ficha = $("#form_nro_ficha").val();
        var nro_chip = $("#form_nro_chip").val();
        var dt_entrada = $("#form_dt_entrada").val();
        var identificador = 1;
        var reincidencias = $("#form_reincidencias").val();


        $.ajax({
            type: 'POST',
            url: '../../../includes/ajax_atualiza_valor_urm.php',
            data: {

                "nro_ficha": nro_ficha,
                "dt_entrada": dt_entrada,
                "nro_chip": nro_chip,
                "id_urm": id_urm,
                "identificador": identificador,
                "reincidencias": reincidencias

            },
            beforeSend: function() {

                console.log("Enviado ok");

            },
            success: function(response) {

                $("#form_valor").val(response['valor']);
                $("#form_reincidencias").val(response['quantidade']);                

            },
            error: function(erro) {

                // console.log(erro);

            }
        });
    });

    function atualizaValor() {
        var nro_chip = $("#form_nro_chip").val();        
        var id_urm = $("#form_id_urm").val();
        var nro_ficha = $("#form_nro_ficha").val();
        var dt_entrada = $("#form_dt_entrada").val();
        var reincidencias = $("#form_reincidencias").val();

        $.ajax({
            type: 'POST',
            url: '../../../includes/ajax_atualiza_valor_urm.php',
            data: {

                "id_urm": id_urm,
                "nro_ficha": nro_ficha,
                "dt_entrada": dt_entrada,
                "reincidencias": reincidencias,
                "nro_chip": nro_chip
            },
            beforeSend: function() {

                console.log("Enviado ok");


            },
            success: function(response) {

                $("#form_valor").val(response['valor']);
                $("#form_reincidencias").val(response['quantidade']); 

            },
            error: function(erro) {

                // console.log(erro);

            }
        });
    }



    $(document).ready(function() {

       

        $("#form_id_urm").prop("selectedIndex", 1).val();
        atualizaValor();
        if ($(".select2_responsavel").length > 0) {
            $(".select2_responsavel").attr('data-live-search', 'true');

            $(".select2_responsavel").select2({
                width: '100%'
            });
        }

    });
</script>