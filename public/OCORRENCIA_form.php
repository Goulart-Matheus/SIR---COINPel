<?

require_once('../class/Layout.php');

$layout = new Layout('SDR - Denúncias', "Secretaria Municipal de Desenvolvimento Rural > Denúncias", "Nova Denúncia");

extract($_GET);
extract($_POST);


include("../includes/connection.php");

include('../class/class.sistema.php');

include('../function/function.exception.php');

include('../function/function.mensagens.php');
$_ip    = $_SERVER['REMOTE_ADDR'];
$_data  = (strftime("%Y-%m-%d"));
$_hora  = (strftime("%H:%M"));

echo $layout->getHeader();

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

    .custom-file-button input[type=file] {
        margin-left: -2px !important;
    }

    .custom-file-button input[type=file]::-webkit-file-upload-button {
        display: none;
    }

    .custom-file-button input[type=file]::file-selector-button {
        display: none;
    }

    .custom-file-button:hover label {
        background-color: #dde0e3;
        cursor: pointer;
    }
</style>

<section class="content">

    <form method="POST" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2 text-end">
                <a href="index.php" class="btn border border-secondary">
                    <span class="fas fa-backward text-secondary"></span>
                    <span class="value text-secondary">Voltar</span>
                    </button>
                </a>
            </div>
        </div>

        <div class="row text-center">

            <div class="col-12 col-sm-4 offset-sm-4">

                <?
                if (isset($add)) {

                    $nome = $form_comunicante;
                    $nomes = explode(" ", $nome);

                    include "../class/class.valida.php";

                    $valida = new Valida($form_descricao, 'Descrição');
                    $valida->TamMinimo(1);
                    $erro .= $valida->PegaErros();
                }

                if (!$erro && isset($add)) {


                    $query_regiao_adm = new Query($bd);

                    $query_regiao_adm->exec("SELECT r.id_regiao_administrativa
                                             FROM   denuncias.microrregiao as m, denuncias.regiao_administrativa as r
                                             WHERE  m.id_regiao_administrativa = r.id_regiao_administrativa 
                                             AND    m.id_microrregiao = $form_microrregiao");

                    if ($query_regiao_adm->rows() > 0) {
                        $query_regiao_adm->proximo();
                        $form_regiao_administrativa = $query_regiao_adm->record['id_regiao_administrativa'];
                    }

                    $_enviar_email = [];

                    $query->begin();
                    $_cpf = preg_replace("/[^0-9]/", "", $form_cpf);



                    $query->insertTupla(
                        'denuncias.ocorrencia',
                        array(
                            $_cpf,
                            $form_email,
                            $form_comunicante,
                            $form_telefone,
                            $_data,
                            $_hora,
                            str_replace("'", "''", $form_descricao),
                            $_ip,
                            $_data,
                            $_hora,
                            'X', // NOVA OCORRÊNCIA PARA TER ÁUDIO DE NOTIFICAÇÃO DEPOIS SE TRANFORMA EM 'N' VIA JS.
                            $form_regiao_administrativa != NULL ? $form_regiao_administrativa : 'NULL',
                            $form_microrregiao != NULL ? $form_microrregiao : 'NULL',
                            $form_ponto_referencia,
                            $form_endereco_numero == "" ? $form_endereco : $form_endereco  . " " . $form_endereco_numero,

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
                                'A', // Aguardando início.
                                $_protocolo_ocorrencia,
                                'N',
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



                                $url_link = 'http://sir.desenv/public/PROTOCOLO_DETALHES.php?id_protocolo=' . $_protocolo_ocorrencia;
                                $imagem = '../assets/images/LOGO_PREFEITURA.png';


                                $mensagem_comunicado = '
                                <html>
                                <head>
                                    <title>Agradecemos seu contato</title>
                                </head>
                                <body>
                                    <p>Agradecemos seu contato.</p>
                                    <p>Para acompanhar o processo, acesse o link abaixo:</p>
                                    <a href="' . $url_link . '">
                                        <img src="' . $imagem . '" alt="Acompanhe o processo" style="width: 300px; height: auto;">
                                    </a>
                                    <p>E informe o número do protocolo: ' . $_protocolo_ocorrencia . '.</p>
                                </body>
                                </html>
                            ';


                                // $mensagem_comunicado = 'Agradecemos seu contato. Para acompanhar o processo
                                //         acesse: http://sir.desenv/public/PROTOCOLO_DETALHES.php?id_protocolo='.$_protocolo_ocorrencia. ' e informe o cpf:' . $_cpf . ' ou protocolo:' . $_protocolo_ocorrencia . '.'.                                        

                                enviaEmail($email, $assunto, $mensagem_comunicado);
                            }
                        }
                    }


                    $query->commitNotMessage();
                }


                if ($erro)

                    echo $erro;

                ?>

            </div>
        </div>

        <div class="row mb-3" id="dados_sigilo">


            <div class=" form-group col-12 col-md-8 offset-md-2 alert alert-danger" role="alert">
                <h4 class="text-center">
                    <strong>
                        <i class="fa-solid fa-shield-halved text-danger"></i>
                        IMPORTANTE
                    </strong>
                </h4>
                <h5 class="p-0 m-0 text-center">Os Dados Pessoais solicitados serão mantidos em pleno sigilo. <br> Somente utilizados para o propósito que motivou o cadastro.</h5>
            </div>
        </div>

        <div class="row mb-3" id="container_dados_pessoais">

            <div class="form-group col-12 col-md-8 offset-md-2">

                <div class="row mb-3">
                    <div class="form-group col-12 bg-light p-2 text-lg">
                        <h5 class="p-0 m-0">
                            <i class="fa-solid fa-user text-danger"></i> Dados Pessoais do Comunicante
                        </h5>
                    </div>
                </div>

                <div class="row mb-3">

                    <div class="form-group col-12 mb-3">
                        <label for="form_comunicante"><span class="text-danger">*</span> Informe seu nome completo</label>
                        <input type="text" class="form-control" name="form_comunicante" id="form_comunicante" value="<? if ($erro) echo $form_comunicante; ?>" required>
                    </div>

                    <div class="form-group col-12 col-md-6 mb-3">
                        <label for="form_cpf"><span class="text-danger">*</span> Informe seu CPF</label>
                        <input type="text" class="form-control form_cpf" name="form_cpf" id="form_cpf" value="<? if ($erro) echo $form_cpf; ?>" required>
                        <span id="form_cpf_span"></span>
                    </div>

                    <div class="form-group col-12 col-md-6 mb-3">
                        <label for="form_telefone"><span class="text-danger">*</span> Informe seu Telefone</label>
                        <input type="text" class="form-control telefone" maxlength="15" name="form_telefone" id="form_telefone" placeholder="(xx) xxxxx - xxxx" value="<? if ($erro) echo $form_telefone; ?>" required>
                    </div>

                    <div class="form-group col-12 mb-3">
                        <label for="form_email"><span class="text-danger">*</span> Informe seu e-mail</label>
                        <input type="mail" class="form-control" name="form_email" id="form_email" value="<? if ($erro) echo $form_email; ?>" required>
                    </div>

                </div>

            </div>

        </div>

        <div class="row mb-3" id="container_descricao_ocorrencia">

            <div class="form-group col-12 col-md-8 offset-md-2">

                <div class="row mb-3">
                    <div class="form-group col-12 bg-light p-2 text-lg">
                        <h5 class="p-0 m-0">
                            <i class="fas fa-plus text-danger"></i> Informações Adicionais da Ocorrência
                        </h5>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="form-group col-12 mb-3">
                        <label for="form_descricao"><span class="text-danger">*</span> Informe o que aconteceu ?</label>
                        <textarea class="form-control" name="form_descricao" id="form_descricao" cols="30" rows="2" required><? if ($erro) echo $form_descricao; ?></textarea>
                    </div>

                    <div class="form-group col-12 mb-3">
                        <label for="form_tipo_ocorrencia"><span class="text-danger">*</span> Selecione abaixo o Tipo de Ocorrência que você viu ?</label>
                        <select class="form-control" name="form_tipo_ocorrencia" id="form_tipo_ocorrencia" required>
                            <?
                            $_flag = 'P'; //P == Pública.
                            include "../includes/inc_select_tipo_ocorrencia.php";
                            ?>
                        </select>
                    </div>

                </div>

            </div>

        </div>

        <div class="row mb-3" id="container_local_ocorrencia">

            <div class="form-group col-12 col-md-8 offset-md-2">

                <div class="row mb-3">
                    <div class="form-group col-12 bg-light p-2 text-lg">
                        <h5 class="p-0 m-0">
                            <i class="fa-solid fa-location-dot text-danger"></i> Em qual local aconteceu a Ocorrência ?
                        </h5>
                    </div>
                </div>


                <div class="row mb-3">

                    <div class="form-group col-12 mb-3">
                        <label for="form_microrregiao"><span class="text-danger">*</span> Informe a sua região</label>
                        <select style="z-index: -1 !important;" class="form-control" name="form_microrregiao" id="form_microrregiao">

                            <?
                            include "../includes/inc_select_regiao.php";
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-12 mb-3">
                        <label for="form_endereco"><span class="text-danger">*</span> Informe o Endereço</label>
                        <input type="text" class="form-control" name="form_endereco" id="form_endereco" value="<? if ($erro) echo $form_endereco; ?>" required>
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3">
                        <label for="form_endereco_numero"><span class="text-danger">*</span> Informe o Número</label>
                        <input type="text" class="form-control" name="form_endereco_numero" id="form_endereco_numero" value="<? if ($erro) echo $form_endereco; ?>" required>
                    </div>

                    <div class="form-group col-9 col-md-9 mb-3">
                        <label for="form_ponto_referencia"><span class="text-danger">*</span> Informe um Ponto de Referência próximo ao endereço</label>
                        <input type="text" class="form-control" name="form_ponto_referencia" id="form_ponto_referencia" value="<? if ($erro) echo $form_ponto_referencia; ?>" required>
                    </div>

                </div>

            </div>

        </div>

        <div class="row mb-3" id="container_imagens_ocorrencia">

            <div class="form-group col-12 col-md-8 offset-md-2">


                <div class="row mb-3">
                    <div class="form-group col-6 bg-light p-2 text-lg-start">
                        <h5 class="p-0 m-0">
                            <i class="fas fa-images text-danger"></i> Coloque aqui as fotos do animal
                        </h5>
                    </div>

                    <div class="form-group col-6 bg-light p-2 text-end">
                        <button type="button" class="btn btn-pelotas btn-sm btn_modal_imagem" data-bs-toggle="modal" data-bs-target="#MODAL_IMAGEM" data-modal="VT">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="row mb-3">

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
                                    <h4 class=' text-dark text-center'>
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
                                        <button type="reset" name="clear" class="btn btn-pelotas btn-sm btn_modal_imagem" data-bs-toggle="modal" data-bs-target="#MODAL_IMAGEM" data-modal="VT">
                                            <td class="d-none">
                                                <input type='hidden' name='form_modal_imagem_descricao2[]' value='<?= $form_modal_imagem_descricao2[$i] ?>'>
                                                <input type='hidden' name='form_modal_imagem_imagem2[]' value='<?= $form_modal_imagem_imagem2[$i] ?>'>
                                                <input type='hidden' name='form_modal_imagem_tipo2[]' value='<?= $form_modal_imagem_tipo2[$i] ?>'>

                                            </td>
                                    </tr>
                            <?
                                }
                            }

                            ?>

                        </tbody>

                    </table>


                </div>

            </div>

        </div>

        <!-- Imagens -->

        <div class="modal fade" id="MODAL_IMAGEM" tabindex="-1" aria-labelledby="MODAL_IMAGEM" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header bg-light">

                        <div class="col-9 text-lg-start">
                            <h5 class="p-0 m-0">
                                <i class="fas fa-plus text-danger"></i> Escolha aqui as fotos do animal.
                            </h5>
                        </div>

                        <div class="col-3 text-end">
                            <button type="button" class="btn btn-pelotas btn-sm btn_modal_imagem" data-bs-toggle="modal" data-bs-target="#MODAL_IMAGEM" data-modal="VT">
                                <i class="fas fa-close"></i>
                            </button>
                        </div>
                    </div>

                    <div class="modal-body">

                        <div class="form-group col-12 mb-3" style="padding-bottom: 20px;">
                            <div class="input-group custom-file-button">
                                <input type="file" class="form-control" id="form_modal_imagem_imagem">
                                <label class="input-group-text" for="form_modal_imagem_imagem">Pesquisar</label>
                            </div>
                        </div>

                        <div class="form-group col-12 mb-3">
                            <label for="form_modal_add_imagem"><span class="text-danger">*</span> Por favor faça uma descrição da imagem</label>
                            <input type="text" class="form-control" id="form_modal_descricao" name="form_modal_descricao">
                        </div>


                    </div>

                    <div class="modal-footer bg-light text-center">

                        <button type="button" id="add_imagem" class="btn btn-pelotas" data-bs-dismiss="modal">
                            Adicionar
                            <i class="fa-regular fa-circle-down"></i>
                        </button>
                    </div>

                </div>

            </div>

        </div>

        <!-- TESTE DO ALERTA PARA CONFIRMAR CADASTRO E APRESENTAR PROTOCOLO -->

        <div class="modal fade" id="MODAL_TESTE" tabindex="-1" aria-labelledby="MODAL_TESTE" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-centered">

                <div class="modal-content">

                    <div class="modal-header">


                        <!-- PEGAR O PRIMEIRO E ÚLTIMO NOME DO DENUNCIANTE-- FORMATAR STRING $nome_denunciante 
                                xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->


                        <div class="form-group col-12 p-2 text-center alert alert-success" role="alert">
                            Parabéns sr.<?= $_data ?> seus dados foram </br> cadastrados com sucesso
                            <i class="fa-regular fa-circle-check"></i>
                        </div>

                    </div>

                    <div class="modal-body">

                        <div class="form-group col-12 p-2 text-lg">
                            <h4 class="p-0 m-0">
                                SEU NÚMERO DE PROTOCOLO </br> PARA ACOMPANHAMENTO É <?= $_hora ?>
                            </h4>
                        </div>
                    </div>

                    <div class="modal-footer bg-light text-center">

                        <button type="button" id="add_imagem" class="btn btn-pelotas" data-bs-dismiss="modal">
                            Pesquisar </br> Protocolo
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>

                </div>

            </div>

        </div>

        <div class="row mb-3" id="container_footer_ocorrencia">
            <div class="form-group col-12 col-md-8 offset-md-2 text-center text-md-end">
                <button type="submit" class="btn btn-pelotas" name="add" id="add">
                    Salvar
                    <i class="fa-regular fa-circle-check"></i>
                </button>
            </div>

        </div>

    </form>

</section>

<?
echo $layout->getFooter();
?>

<script src="../assets/js/jquery.mask.js"></script>
<script src="../assets/js/select2.js"></script>

<script>
    $(document).ready(function() {



        <?

        //VERIFICA SE DADOS FORAM INSERIDOS NO DB.
        $query->exec("SELECT id_ocorrencia_tramitacao, protocolo FROM denuncias.ocorrencia_tramitacao WHERE protocolo ='$_protocolo_ocorrencia'");

        $n = $query->rows();

        if ((!$erro && isset($add)) && $n == 1) {
        ?>
            $('#modal_success').find('#message').html('<div class="col-12"><h5>Sr(a). <b><?= $nomes[0] ?></b> este número:</br><b><?= $_protocolo_ocorrencia ?></b></br> é seu Protocolo para acompanhamento</h5></div><div class="col-12 text-md-end"><a class="btn btn-pelotas" href="PROTOCOLO_DETALHES.php?id_protocolo=<?= $_protocolo_ocorrencia ?>">Visualizar</a></div>');
            $('#modal_success').find('.modal-dialog').removeClass('modal-sm').addClass('modal-md');
            $('#modal_success').modal('show');

        <?
        }
        if (isset($erro) && isset($add)) {
        ?>
            $('#modal_error').find('#message').html('<div class="col-12"><h5>Sr(a). <b><?= $nomes[0] ?></b> houve um erro </br> no cadastro do seus dados. </br><b>Por favor preencher todos os campos .</h5></div><div class="col-12 text-md-end"></div>');
            $('#modal_error').find('.modal-dialog').removeClass('modal-sm').addClass('modal-md');
            $('#modal_error').modal('show');

        <?

        }

        ?>

        if ($(".select2_microrregiao").length > 0) {
            $(".select2_microrregiao").attr('data-live-search', 'true');

            $(".select2_microrregiao").select2({
                width: '100%'
            });
        }

        if ($(".select2_tipo_ocorrencia").length > 0) {
            $(".select2_tipo_ocorrencia").attr('data-live-search', 'true');

            $(".select2_tipo_ocorrencia").select2({
                width: '100%'
            });
        }

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




    })


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

    });
</script>