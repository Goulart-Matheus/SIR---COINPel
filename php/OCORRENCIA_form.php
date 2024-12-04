<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
include('../function/function.mensagens.php');

$tab = new Tab();

$tab->setTab('Ocorrências', 'fa-solid fa-address-book', 'OCORRENCIA_viewDados.php');
$tab->setTab('Novo Ocorrência', 'fas fa-eye', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

?>

<style>
    .imagem-container {
        position: relative;
        display: inline-block;
    }

    .imagem-container:hover img {
        transform: scale(1.5);
        /* Ampliar a imagem em 20% quando o mouse estiver sobre ela */
        transition: transform 0.3s ease-in-out;
        /* Adicionar transição suave */
    }
</style>

<section class="content">    

    <form method="POST" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; ?>">

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

                            $_enviar_email = [];

                            $query->begin();

                            $_cpf = preg_replace("/[^0-9]/", "", $form_cpf);

                            $query->insertTupla(
                                'denuncias.ocorrencia',
                                array(
                                    $_cpf,
                                    $form_email,
                                    $form_comunicante,
                                    $form_telefone_contato,
                                    $form_data,
                                    $form_hora,
                                    $form_descricao,
                                    $_ip,
                                    $_data,
                                    $_hora,
                                    'A', // Aberto
                                    $form_regiao_administrativa != NULL ? $form_regiao_administrativa : 'NULL',
                                    $form_microrregiao != NULL ? $form_microrregiao : 'NULL',
                                    $form_ponto_referencia,
                                    $form_endereco_numero == "" ? $form_endereco : $form_endereco  . " " . $form_endereco_numero

                                )
                            );

                            //Salva o email do comunicante.
                            array_push($_enviar_email, trim($form_email));

                            $query_orgao = new Query($bd);

                            $query_orgao->exec("SELECT   o.key_orgao as key, email
                                                  FROM orgao as o , denuncias.tipo_ocorrencia as tp
                                                  WHERE tp.id_orgao = o.id_orgao AND
                                                        tp.id_tipo_ocorrencia = $form_tipo_ocorrencia");


                            $query_orgao->proximo();
                            $_key_orgao = $query_orgao->record['key'];

                            //Salva o email do órgão.
                            array_push($_enviar_email, trim($query_orgao->record['email']));

                            $_id_ocorrencia = $query->last_insert[0];

                            if (isset($_id_ocorrencia)) {

                                //Protocola da Ocorrência
                                $_protocolo_ocorrencia = "DEN" . $_key_orgao . preg_replace("/[^0-9]/", "", date("y-m-d H:i:s"));

                                $query->insertTupla(
                                    'denuncias.ocorrencia_tramitacao',
                                    array(
                                        $_id_ocorrencia,
                                        $form_tipo_ocorrencia,
                                        $form_descricao,
                                        $_login,
                                        $_ip,
                                        $_data,
                                        $_hora,
                                        'A', // Aquardando início.
                                        $_protocolo_ocorrencia,
                                        'N',
                                        "NULL"
                                    )
                                );

                                $_id_ocorrencia_tramitacao = $query->last_insert[0];

                                // Se existir tramitação.

                                if ($_id_ocorrencia_tramitacao) {

                                    // Se existir imagem.

                                    if ($form_modal_imagem_imagem2) {
                                        $c = 0;

                                        foreach ($form_modal_imagem_descricao2 as $descricao) {


                                            $imagem_base64 = $form_modal_imagem_imagem2[$c];
                                            $tipo_arquivo = $form_modal_imagem_tipo2[$c];

                                            $dados = array(
                                                $_id_ocorrencia_tramitacao,
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

                                    foreach ($_enviar_email as $email) {

                                        $assunto = 'Comunicação protocolo: ' . $_protocolo_ocorrencia;
                                        $mensagem_comunicado = 'Agradecemos seu contato. Para acompanhar o processo
                                        acesse: https://sistema.pelotas.com.br/sir/public/index.php e informe o cpf:' . $_cpf . ' e protocolo:' . $_protocolo_ocorrencia . '.';

                                        enviaEmail($email, $assunto, $mensagem_comunicado);
                                    }
                                }
                            }

                            $query->commit();
                        }


                        if ($erro)

                            echo callException($erro, 2);

                        ?>

                    </div>
                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row ">
                    <div class="form-group bg-green my-3 col-12 col-md-12 ">
                        <p class="card-header p-2"><i class="fas fa-clipboard-list"></i> Descrição da Ocorrência</p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12 col-md-12">
                        <label for="form_descricao"><span class="text-danger">*</span> Descrição do Fato</label>
                        <textarea class="form-control" name="form_descricao" id="form_descricao" cols="30" rows="2" required><? if ($erro) echo $form_descricao; ?></textarea>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_data"><span class="text-danger">*</span> Data</label>
                        <input type="date" class="form-control" name="form_data" id="form_data" value="<? if ($erro) echo $form_data; ?>" required>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_hora"><span class="text-danger">*</span> Hora</label>
                        <input type="time" class="form-control" name="form_hora" id="form_hora" value="<? if ($erro) echo $form_descricao; ?>" required>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_tipo_ocorrencia"><span class="text-danger">*</span>Tipo de Ocorrência</label>
                        <select class="form-control" name="form_tipo_ocorrencia" id="form_tipo_ocorrencia" required>
                            <?
                            $_flag = 'P'; //P == Pública.
                            include "../includes/inc_select_tipo_ocorrencia.php";
                            ?>
                        </select>
                    </div>

                    <!-- Imagens -->

                    <div class="form-group bg-green col-12 col-md-12 p-0 m-0">

                        <div class="card-header">
                            <div class="form-row">
                                <div class="col-md-6 text-left"><i class="fas fa-images"></i> Imagens</div>

                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn bg-green btn-light btn-sm text-light btn_modal_imagem" data-toggle="modal" data-target="#MODAL_IMAGEM" data-modal="VT"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 p-0">
                        <table class="table" id="table_imagem">
                            <thead>
                                <tr>
                                    <th colspan="1">Descrição</th>
                                    <th colspan="1">Imagem</th>
                                    <th class="d-none">Inputs</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr class='nenhum_imagem2'>
                                    <td class='py-5' colspan='12'>
                                        <h4 class=' text-light text-center'>
                                            Nenhuma imagem adicionada
                                        </h4>
                                    </td>
                                </tr>

                                <?
                                if ($erro) {
                                    for ($i = 0; $i < sizeof($form_modal_imagem_imagem2); $i++) {
                                ?>
                                        <tr>
                                            <td colspan="1"><?= $form_modal_imagem_descricao2[$i]  ?></td>
                                            <td colspan="1"><?= $form_modal_imagem_imagem2[$i] ?></td>
                                            <td class="text-right">
                                                <i class='fas fa-trash text-danger cursor-pointer remove_item_table'></i>
                                            </td>
                                            <td class="d-none">
                                                <input type='hidden' name='form_modal_imagem_descricao2[]' value='<?= $form_modal_imagem_descricao2[$i] ?>'>
                                                <input type='hidden' name='form_modal_imagem_imagem2[]' value='<?= $form_modal_imagem_imagem2[$i] ?>'>
                                                <button type="reset" name="clear" class="btn bg-green btn-light btn-sm text-light btn_modal_imagem" data-toggle="modal" data-target="#MODAL_IMAGEM" data-modal="VT"><i class="fas fa-plus"></i></button>
                                            </td>
                                        </tr>
                                <?
                                    }
                                }

                                ?>

                            </tbody>

                        </table>

                    </div>


                    <div class="modal fade" id="MODAL_IMAGEM" tabindex="-1" aria-labelledby="MODAL_IMAGEM">

                        <div class="modal-dialog modal-xl">

                            <div class="modal-content">

                                <div class="modal-header bg-green">

                                    <h5 class="modal-title">
                                        <i class="fas fa-plus"></i>
                                        Adicionar <span class="type_local"></span> Imagem
                                    </h5>

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <div class="modal-body">
                                        <div class="form-group col-12 col-md-12">
                                            <label for="form_modal_add_imagem">Imagem</label>
                                            <input type="file" class="form-control" id="form_modal_imagem_imagem" name="form_modal_imagem_imagem">
                                        </div>

                                        <div class="form-group col-12 col-md-12">
                                            <label for="form_modal_add_imagem">Desrição</label>
                                            <input type="text" class="form-control" id="form_modal_descricao" name="form_modal_descricao">
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light-2 text-center">
                                        <input class="btn btn-light" type="reset" name="clear" value="Limpar">

                                        <button type="button" id="add_imagem" class="btn btn-light">
                                            <i class="fa-solid fa-filter text-green"></i>
                                            Salvar
                                        </button>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-group bg-green my-3 col-12 col-md-12 ">
                        <p class="card-header p-2"><i class="fas fa-map-marked-alt"></i> Local da Ocorrência</p>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_regiao_administrativa"><span class="text-danger">*</span>Região Administrativa</label>
                        <select class="form-control" name="form_regiao_administrativa" id="form_regiao_administrativa" required>
                            <?
                            include "../includes/inc_select_regiao_administrativa.php";
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_microrregiao"><span class="text-danger">*</span>Microrregião</label>
                        <select class="form-control" name="form_microrregiao" id="form_microrregiao" readonly>
                            <?
                            include "../includes/inc_select_microrregiao.php";
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-8">
                        <label for="form_endereco"><span class="text-danger">*</span> Endereço</label>
                        <input type="text" class="form-control" name="form_endereco" id="form_endereco" value="<? if ($erro) echo $form_endereco; ?>" required>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_endereco_numero"><span class="text-danger">*</span> Número</label>
                        <input type="text" class="form-control" name="form_endereco_numero" id="form_endereco_numero" value="<? if ($erro) echo $form_endereco; ?>" required>
                    </div>

                    <div class="form-group col-12 col-md-12">
                        <label for="form_ponto_referencia"><span class="text-danger">*</span> Ponto de Referência</label>
                        <input type="text" class="form-control" name="form_ponto_referencia" id="form_ponto_referencia" value="<? if ($erro) echo $form_ponto_referencia; ?>" required>
                    </div>


                    <div class="form-group bg-green my-3 col-12 col-md-12 ">
                        <p class="card-header p-2"><i class="fas fa-user-shield"></i> Identificação do Comunicante</p>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_comunicante"><span class="text-danger">*</span> Nome</label>
                        <input type="text" class="form-control" name="form_comunicante" id="form_comunicante" value="<? if ($erro) echo $form_comunicante; ?>" required>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_cpf"><span class="text-danger">*</span> CPF</label>
                        <input type="text" class="form-control form_cpf" name="form_cpf" id="form_cpf" value="<? if ($erro) echo $form_cpf; ?>" required>
                        <span id="form_cpf_span"></span>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_email"><span class="text-danger">*</span> E-mail</label>
                        <input type="mail" class="form-control" name="form_email" id="form_email" value="<? if ($erro) echo $form_email; ?>" required>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_telefone"><span class="text-danger">*</span> Telefone</label>
                        <input type="text" class="form-control telefone" maxlength="15" name="form_telefone" id="form_telefone" placeholder="(xx) xxxxx - xxxx" value="<? if ($erro) echo $form_telefone; ?>" required>
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



<script src="../assets/js/jquery.mask.js"></script>
<script src="../assets/js/jquery.multi-select.js"></script>
<script src="../assets/js/jquery.quicksearch.js"></script>
<link href="../assets/css/multi-select.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
    $(document).ready(function() {

        // $('#form_cpf').mask('000.000.000-00');

        $('#form_cpf').mask('000.000.000-00', {
            reverse: false

        });

        $('#form_cpf').on('keyup', function() {

            // if (this.textLength == 14) {
            //     console.log("cpf válido");
            // }
            if (this.textLength == 14) {

                var strCPF;
                strCPF = this.value.replace('.', '').replace('.', '').replace('-', '')
                var Soma;
                var Resto;
                Soma = 0;
                if (strCPF == "00000000000") {
                    $("#form_cpf_span").html("CPF inválido, por favor, informe um documento válido!").addClass("text-danger");
                } else {

                    if (strCPF == "") {
                        $("#form_cpf_span").html("").removeClass("text-danger");
                    }


                    for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
                    Resto = (Soma * 10) % 11;

                    if ((Resto == 10) || (Resto == 11)) Resto = 0;
                    if (Resto != parseInt(strCPF.substring(9, 10))) {
                        $("#form_cpf_span").html("CPF inválido, por favor, informe um documento válido!").addClass("text-danger");
                    } else {
                        $("#form_cpf_span").html("").removeClass("text-danger");
                    }

                    Soma = 0;
                    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
                    Resto = (Soma * 10) % 11;

                    if ((Resto == 10) || (Resto == 11)) Resto = 0;

                    if (Resto != parseInt(strCPF.substring(10, 11))) {
                        $("#form_cpf_span").html("CPF inválido, por favor, informe um documento válido!").addClass("text-danger");
                    } else {
                        $("#form_cpf_span").html("").removeClass("text-danger");
                    }
                }

            }

            $('#form_cpf').on('keyup', function() {
                if (this.textLength == 0) {
                    $("#form_cpf_span").html("").removeClass("text-danger");
                }
            });

        });


        $('.telefone').on('input', function() {
            var telefone = $(this).val().replace(/\D/g, '');
            var tamanho = telefone.length;

            if (tamanho === 10) {
                $(this).val(telefone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3'));
            } else if (tamanho === 11) {
                $(this).val(telefone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3'));
            }
        });




        //Regiao Administrativa // Microregiao
        $("#form_regiao_administrativa").on('change', function() {

            var grupo_regiao = $("#form_regiao_administrativa").val();
            var controle = "atualiza_microrregiao";

            $.ajax({
                type: "POST",
                url: "../includes/ajax_add_imagem_ocorrencia.php",
                data: {
                    "grupo_regiao": grupo_regiao,
                    "controle": controle
                },
                dataType: "JSON",
                beforeSend: function() {
                    $("#modal_loading").modal('show');
                    $('#form_microrregiao').attr("readonly", true);
                    $('#form_microrregiao').html('');
                    $('.logradouro_disabled').removeAttr("disabled");
                },
                success: function(response) {
                    $("#modal_loading").modal('hide');

                    $('#form_microrregiao').removeAttr("readonly");

                    var option = "<option value=''> Selecione a Microrregião </option>";
                    $.each(response, function(i, obj) {
                        option += '<option value="' + obj["id_microrregiao"] + '">' + obj["nome"] + '</option>';
                    })
                    $("#form_microrregiao").html(option).show();

                },
                error: function(response) {
                    $("#modal_loading").modal('hide');
                    console.log("error");
                }
            });
        });

    });


    $(document).on('click', '.remove_item_table', function() {
        $(this).parents('tr').remove();
    });



    $("#add_imagem").on('click', function() {

        var table_body = "";

        var imagem_imagem = $("#form_modal_imagem_imagem").prop('files')[0];
        var imagem_descricao = $('[name="form_modal_imagem_descricao"]').val();


        $.each($('[name="form_modal_descricao"]'), function(index, value) {

            var imagem_descricao = $(value).val();
            var imagem_imagem = $("#form_modal_imagem_imagem").prop('files')[0];

            var reader = new FileReader();

            reader.onload = function() {
                var base64data = reader.result.split(',')[1];

                // Criar elementos HTML e atribuir os valores
                var tr = $("<tr>");
                var tdDescricao = $("<td>").attr("colspan", "1").text(imagem_descricao);
                var tdImagem = $("<td>").attr("colspan", "1").html("<div class='imagem-container p-2'><img class='img-responsive' src='" + URL.createObjectURL(imagem_imagem) + "' style='display: inline-block; max-width: 120px; vertical-align: top;' /></div>");
                var tdDelete = $("<td>").addClass("text-right").html("<i class='fas fa-trash text-danger cursor-pointer remove_item_table'></i>");
                var tdHiddenDescricao = $("<td>").addClass("d-none").html("<input type='hidden' name='form_modal_imagem_descricao2[]' value='" + imagem_descricao + "'>");
                var tdHiddenImagem = $("<td>").addClass("d-none").html("<input type='hidden' name='form_modal_imagem_imagem2[]' value='" + base64data + "'>");
                var tdHiddenTipo = $("<td>").addClass("d-none").html("<input type='hidden' name='form_modal_imagem_tipo2[]' value='" + imagem_imagem.type + "'>");

                // Anexar os elementos à tabela ou aonde você precisar
                tr.append(tdDescricao, tdImagem, tdDelete, tdHiddenDescricao, tdHiddenImagem, tdHiddenTipo);
                $("#table_imagem").append(tr);
            };

            reader.readAsDataURL(imagem_imagem);

        });


        $("#table_imagem").find('tbody').append(table_body);
        $(".nenhum_imagem").find('td').parent().remove();
        $(".nenhum_imagem2").find('td').parent().remove();
        $("#MODAL_IMAGEM").modal('hide');

    });
</script>