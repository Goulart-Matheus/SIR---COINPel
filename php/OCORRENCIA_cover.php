<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
include('../function/function.date.php');
$tab = new Tab();

$tab->setTab('Atendimentos', 'fa-sharp fa-solid fa-eye', 'OCORRENCIA_viewDados.php');
$tab->setTab($id_ocorrencia, $query_ocorrencia->record[5], $_SERVER['PHP_SELF'] . '?id_ocorrencia='   . $id_ocorrencia);
$tab->printTab($_SERVER['PHP_SELF'] . '?id_ocorrencia=' . $id_ocorrencia);


$query_ocorrencia = new Query($bd);

$query_ocorrencia->exec("SELECT o.id_ocorrencia, o.cpf, o.email, o.nome_denunciante,o.telefone_contato, o.data, o.hora, o.descricao as relato_denunciante, o.status,
                                ra.nome as regiao_administrativa, o.ponto_referencia, o.endereco, t.nome as tipo_ocorrencia,
                                 ot.protocolo, ot.visualizado, org.descricao, org.sigla, t.id_tipo_ocorrencia, ot.visualizado
                        FROM    denuncias.ocorrencia as o left join denuncias.regiao_administrativa as ra
                        ON      o.id_regiao_administrativa = ra.id_regiao_administrativa,
                                denuncias.ocorrencia_tramitacao as ot, denuncias.tipo_ocorrencia as t, orgao as org
                        WHERE   o.id_ocorrencia = ot.id_ocorrencia
                        AND     t.id_orgao = org.id_orgao
                        AND     ot.id_tipo_ocorrencia = t.id_tipo_ocorrencia
                        AND     o.id_ocorrencia = $id_ocorrencia order by ot.id_ocorrencia_tramitacao desc");


$query_ocorrencia->result($query->linha);


$query->exec("SELECT id_ocorrencia FROM denuncias.ocorrencia_orgao where id_orgao = $_id_orgao and id_ocorrencia = $id_ocorrencia");
if ($query->rows() == 0) {
    $dados = array(
        $id_ocorrencia,
        $_id_orgao,
        $_login,
        $_ip,
        $_data,
        $_hora,
    );

    $query->insertTupla('denuncias.ocorrencia_orgao', $dados);;
    $query->commitNotMessage();
}

if ($query_ocorrencia->record['visualizado'] == "N") {
    $query->exec("UPDATE denuncias.ocorrencia_tramitacao SET visualizado='A' WHERE id_ocorrencia = $id_ocorrencia");
    $query->commitNotMessage();
}

if ($query_ocorrencia->record['status'] == "N") {
    $query->exec("UPDATE denuncias.ocorrencia SET status='A' WHERE id_ocorrencia = $id_ocorrencia");
    $query->exec("UPDATE denuncias.ocorrencia_tramitacao SET status='A' WHERE id_ocorrencia = $id_ocorrencia");
    $query->commitNotMessage();
}

$id_ocorrencia               = $query_ocorrencia->record['id_ocorrencia'];
$cpf_denunciante             = $query_ocorrencia->record['cpf'];
$email_denunciante           = $query_ocorrencia->record['email'];
$nome_denunciante            = $query_ocorrencia->record['nome_denunciante'];
$telefone_denunciante        = $query_ocorrencia->record['telefone_contato'];
$data_ocorrencia             = date('d/m/Y', strtotime($query_ocorrencia->record['data']));
$hora_ocorrencia             = $query_ocorrencia->record['hora'];
$descricao_ocorrencia        = $query_ocorrencia->record['descricao'];
$status_ocorrencia           = $query_ocorrencia->record['status'];
$bairro                      = $query_ocorrencia->record['regiao_administrativa'];
$ponto_referencia            = $query_ocorrencia->record['ponto_referencia'];
$endereco_recolhimento       = $query_ocorrencia->record['endereco'];
// $nome_arquivo                = $query_ocorrencia->record[12];
// $imagem_arquivo              = $query_ocorrencia->record[13];
$nome_tipo_ocorrencia        = $query_ocorrencia->record['tipo_ocorrencia'];
$relato_denunciante          = $query_ocorrencia->record['relato_denunciante'];
$status_tramitacao           = $query_ocorrencia->record['status'];
$protocolo_tramitacao        = $query_ocorrencia->record['protocolo'];
$visualizado_tramitacao      = $query_ocorrencia->record['visualizado'];
$descricao_orgao             = $query_ocorrencia->record['descricao'];
$sigla_orgao                 = $query_ocorrencia->record['sigla'];
$_id_tipo_ocorrencia          = $query_ocorrencia->record['id_tipo_ocorrencia'];


?>


<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF'] . '?id_ocorrencia=' . $id_ocorrencia; ?>">
        <input type="hidden" name="id_ocorrencia" value="<? echo $id_ocorrencia; ?>">


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

                            if ($form_encaminhar == "on") {
                                $valida = new Valida($form_descricao, 'Descrição');
                                $valida->TamMinimo(1);
                                $erro .= $valida->PegaErros();
                                $status = 'A'; // Aguardando Atendimento
                            } elseif ($form_concluido == "on") {
                                $valida = new Valida($form_descricao, 'Descrição');
                                $valida->TamMinimo(1);
                                $erro .= $valida->PegaErros();
                                $status = 'F'; // Atendimento Finalizado
                            }
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            if ($status == "F") {

                                // $query->exec("UPDATE denuncias.ocorrencia SET status='" . $status . "' WHERE id_ocorrencia = $id_ocorrencia");


                                $itens = array(
                                    $id_ocorrencia,
                                    $id_tipo_ocorrencia,
                                    str_replace("'", "''", $form_descricao),
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,
                                    $status,
                                    $protocolo_tramitacao,
                                    'S',
                                );

                                $where = array(0 => array('id_ocorrencia_tramitacao', $id_ocorrencia_tramitacao));
                                $query->updateTupla('denuncias.ocorrencia_tramitacao', $itens, $where);
                                $query->exec("UPDATE denuncias.ocorrencia SET status='F' WHERE id_ocorrencia = $id_ocorrencia");

                                $query->commit();
                                // var_dump($query->sql);
                                // var_dump($itens);
                            }


                            if ($status == "A") {

                                $query->insertTupla(
                                    'denuncias.ocorrencia_tramitacao',
                                    array(
                                        $id_ocorrencia,
                                        $form_orgao_responsavel,
                                        str_replace("'", "''", $form_descricao),
                                        $_login,
                                        $_ip,
                                        $_data,
                                        $_hora,
                                        'A', // Aquardando início.
                                        $protocolo_tramitacao,
                                        'N',
                                    )
                                );
                                $query->commit();
                            }

                            if ($form_modal_imagem_imagem2) {
                                $c = 0;

                                foreach ($form_modal_imagem_descricao2 as $descricao) {


                                    $imagem_base64 = $form_modal_imagem_imagem2[$c];
                                    $tipo_arquivo = $form_modal_imagem_tipo2[$c];

                                    $dados = array(
                                        $id_ocorrencia_tramitacao,
                                        $descricao,
                                        $_login,
                                        $_ip,
                                        $_data,
                                        $_hora,
                                        $tipo_arquivo,
                                        $imagem_base64
                                    );

                                    $query->insertTupla('denuncias.arquivo_ocorrencia', $dados);
                                    $c++;
                                }
                            }
                        }

                        if (isset($reeditar)) {
                            $status_tramitacao           = "A";
                            echo $id_ocorrencia_tramitacao;
                            $query->exec("UPDATE denuncias.ocorrencia SET status='A' WHERE id_ocorrencia = $id_ocorrencia");
                            $query->exec("UPDATE denuncias.ocorrencia_tramitacao SET status='A' WHERE id_ocorrencia_tramitacao = $id_ocorrencia_tramitacao");

                            $query->commit();
                        }

                        if ($erro)

                            echo callException($erro, 2);

                        ?>

                    </div>

                </div>

                <div class="row">
                    <div class="col-1">
                        <h2 class="info-box-icon" id="status_ocorencia_cover" style="width: 30px;height:30px;"></h2>
                    </div>
                    <div class="col-12 col-md-3 px-3 ">
                        <div class="row">
                            <p class="mb-0"><b>Nome do denunciante:</b><?= $nome_denunciante ?></p>
                        </div>
                        <div class="row">
                            <p class="mb-0"><i class="fas fa-at"></i><?= $email_denunciante ?> </p>
                        </div>
                        <div class="row">
                            <p class="mb-0"><i class="fas fa-phone"></i><?= " " . formatarTelefone($telefone_denunciante) ?></p>
                        </div>

                        <div class="row">
                            <p id="cover_situacao"><b>Situação:</b>
                                <?
                                if ($status_ocorrencia  == "A" || $status_ocorrencia  == "X") {
                                    echo "<span class='text-yellow'>Em Atendimento</span>";
                                } elseif ($status_ocorrencia  == "N") {
                                    echo "<span class='text-green'>Atendimento Finalizado</span>";
                                } ?></p>
                        </div>

                        <div class="row">
                            <p class="mb-0"><b>Protocolo:</b><?= $protocolo_tramitacao ?></p>
                        </div>

                    </div>

                </div>

                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <? if ($erro) echo callException($erro, 2); ?>
                    </div>
                </div>

            </div>


            <div class="card-body pt-3">


                <div class="row">

                    <div class="col-12">

                        <?
                        include("../includes/cards/card_dashboard_OCORRENCIA/card_dashboard_OCORRENCIA_FICHA.php")
                        ?>

                    </div>

                </div>

                <div class="row">

                    <div class="col-12 col-md-12">
                        <?
                        include("../includes/cards/card_dashboard_OCORRENCIA/card_dashboard_OCORRENCIA_ATENDIMENTO.php");
                        ?>
                    </div>

                    <div class="col-12 col-md-12">
                        <?
                        include("../includes/cards/card_dashboard_OCORRENCIA/card_dashboard_OCORRENCIA_ANEXO.php");
                        ?>
                    </div>

                    <div class="col-12 col-md-12">

                        <?  ?>

                    </div>

                </div>

            </div>
        </div>
        <input type="hidden" name="id_ocorrencia_tramitacao" value="<? echo $id_ocorrencia_tramitacao; ?>">
    </form>

</section>


<?
include_once('../includes/dashboard/footer.php');
function isNum($val)
{
    if (is_numeric($val)) {
        if (strtoupper($val) != "NAN") {
            return intval($val);
        }
    }
    return 0;
}

function formatarTelefone($telefone)
{
    // Remover caracteres indesejados
    $telefone = preg_replace("/[^0-9]/", "", $telefone);

    // Aplicar a máscara
    if (strlen($telefone) == 11) {
        // Formato para números de telefone com DDD (11 dígitos)
        $telefoneFormatado = preg_replace("/(\d{2})(\d{4,5})(\d{4})/", "$1 $2-$3", $telefone);
    } else {
        // Formato para números de telefone sem DDD (10 dígitos)
        $telefoneFormatado = preg_replace("/(\d{2})(\d{4})(\d{4})/", "$1 $2-$3", $telefone);
    }

    return $telefoneFormatado;
}
?>

<script src="../assets/js/jquery.js"></script>



<script type="text/javascript">
    $(document).ready(function() {

        // var id_ocorrencia = <?= $id_ocorrencia ?>;
        // var identificador = "atualiza_status_cover"

        // $.ajax({
        //     type: "post",
        //     url: "../includes/AJAX_ATUALIZA_COVER.php",
        //     data: {
        //         "id_ocorrencia": id_ocorrencia,
        //         "identificador": identificador
        //     },
        //     dataType: "json",
        //     success: function(response) {
        //         alert("ok")

        //         document.getElementById("result").innerHTML = ret;
        //     },
        //     error: function(response) {

        //     }
        // });

        $('input[name=form_concluido]').on('click', function() {

            if ($('input[name=form_concluido]:checked').val() == 'on') {

                // $('#div_form_form_encaminhar').addClass('d-none');
                $('.div_secretaria_encaminhar').addClass('d-none');
                $('#form_encaminhar').removeAttr('required');
                $('#form_orgao_responsavel').removeAttr('required');
                $('#div_form_concluido').attr('required');
                // $('#form_encaminhar').val(null);

            } else {

            }

        });

        $('input[name=form_encaminhar]').on('click', function() {

            if ($('input[name=form_encaminhar]:checked').val() == 'on') {
                // $('.div_form_concluido').addClass('d-none');
                $('.div_secretaria_encaminhar').removeClass('d-none');
                $('#div_form_concluido').removeAttr('required');
                $('#form_encaminhar').attr('required');
                $('#form_orgao_responsavel').attr('required');
                $('#form_concluido').removeAttr('required');
                $('#form_concluido').val(null);
                // $('#form_concluido_label').removeClass('active');
                // $('input[name=form_concluido]').prop( "checked", false );
                // console.log($( "#form_concluido" ).val());
                // $('input[name=form_concluido]').checkboxradio("refresh");  
                // console.log($( "#form_concluido" ).val());

            } else {
                $('.div_secretaria_encaminhar').addClass('d-none');
                $('.div_form_concluido').removeClass('d-none');

            }

        });


        $("#add_arquivos_ocorrencia").on('click', function() {
            var table_body = "";

            var imagem_imagem = $("#form_modal_add_arquivos_ocorrencia").prop('files')[0];
            var imagem_descricao = $('[name="form_modal_descricao_arquivos_ocorrencia"]').val();

            $.each($('[name="form_modal_descricao_arquivos_ocorrencia"]'), function(index, value) {
                var imagem_descricao = $(value).val();
                var imagem_imagem = $("#form_modal_add_arquivos_ocorrencia").prop('files')[0];

                var reader = new FileReader();

                reader.onload = function() {
                    var base64data = reader.result.split(',')[1];

                    // Criar elementos HTML e atribuir os valores
                    var tr = $("<tr>");
                    if (imagem_imagem.type == "application/pdf") {
                        var tdImagem = $("<td>").html("<div class='imagem-container align-baseline'><img class='img-responsive img-thumbnail' src='../assets/images/pdf.png' style='display: inline-block; max-width: 35px; vertical-align: top;' /></div>");

                        var downloadLink = $("<a>").attr("href", URL.createObjectURL(imagem_imagem)).attr("download", imagem_descricao + ".pdf").append('<i class="fas fa-download"></i>');
                        var tdDescricao = $("<td class='align-middle'>").text(imagem_descricao).append(downloadLink);

                    } else {
                        var tdImagem = $("<td>").html("<div class='imagem-container align-baseline'><img class='img-responsive img-thumbnail' src='" + URL.createObjectURL(imagem_imagem) + "' style='display: inline-block; max-width: 40px; vertical-align: top;' /></div>");
                        var downloadLink = $("<a>").attr("href", URL.createObjectURL(imagem_imagem)).attr("download", imagem_descricao + ".pdf").append('<i class="fas fa-download"></i>');
                        var tdDescricao = $("<td class='align-middle'>").text(imagem_descricao).append(downloadLink);
                    }

                    var tdDelete = $("<td class='align-middle'>").addClass("text-right").html("<i class='fas fa-trash text-danger cursor-pointer remove_item_table'></i>");
                    var tdHiddenDescricao = $("<td>").addClass("d-none").html("<input type='hidden' name='form_modal_imagem_descricao2[]' value='" + imagem_descricao + "'>");
                    var tdHiddenImagem = $("<td>").addClass("d-none").html("<input type='hidden' name='form_modal_imagem_imagem2[]' value='" + base64data + "'>");
                    var tdHiddenTipo = $("<td>").addClass("d-none").html("<inpuMODAL_ADD_IMAGEM_TRAMITACAOt type='hidden' name='form_modal_imagem_tipo2[]' value='" + imagem_imagem.type + "'>");

                    tr.append(tdImagem, tdDescricao, tdDelete, tdHiddenDescricao, tdHiddenImagem, tdHiddenTipo);
                    $(".arquivos_ocorrencia_atendimento").append(tr);
                    console.log(tr);
                };

                reader.readAsDataURL(imagem_imagem);
            });

            // $("#MODAL_ADD_IMAGEM_TRAMITACAO").modal('hide');
        });

        $("#add_arquivos_ocorrencia").on('click', function() {
            $("#MODAL_ADD_IMAGEM_TRAMITACAO").modal('hide');
        });


        $(document).on('click', '.remove_item_table', function() {
            $(this).parents('tr').remove();
        });

        function atualiza_status_cover() {

            var id_ocorrencia = <?= $id_ocorrencia ?>;
            var identificador = "atualiza_status_cover"

            $.ajax({
                type: "post",
                url: "../includes/AJAX_ATUALIZA_COVER.php",
                data: {
                    "id_ocorrencia": id_ocorrencia,
                    "identificador": identificador
                },
                dataType: "json",
                success: function(response) {

                    $.each(response, function(i, obj) {

                        var ret_status = ""
                        var ret_situacao = ""

                        if (obj.status == "A") {
                            ret_status = "<i class='fas fa-circle text-yellow'></i>";
                            ret_situacao = "<b > Situação: </b><span class='text-yellow'>Em Atendimento</span>"
                        }
                        if (obj.status == "N") {
                            ret_status = "<i class='fas fa-circle text-dark'></i>";
                            ret_situacao = "<b > Situação: </b><span class='text-dark'>Nova Ocorrência</span>";
                        }
                        if (obj.status == "F") {
                            ret_status = "<i class='fas fa-circle text-green'></i>";
                            ret_situacao = "<b > Situação: </b><span class='text-green'>Atendimento Finalizado</span>";
                        }
                        if (obj.status == "X") {
                            ret_status = "<i class='fas fa-circle text-dark'></i>";
                            ret_situacao = "<b > Situação: </b><span class='text-dark'>Nova Ocorrência</span>";
                        }
                        console.log(obj.status);

                        document.getElementById("status_ocorencia_cover").innerHTML = ret_status;
                        document.getElementById("cover_situacao").innerHTML = ret_situacao;

                    });

                },
                error: function(response) {

                }
            });

        };

        atualiza_status_cover();

    });
</script>