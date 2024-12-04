<?php
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include('../includes/dashboard/header.php');
include('../function/function.date.php');
include('../function/function.string.php');
?>
<script type="text/javascript" language="javascript" src="../java/ajax.js"></script>

<?php
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Pesquisa', 'fa-solid fa-search', 'TUTORVERIFICA_viewDados.php');
$tab->setTab('Adicionar', 'fa-solid fa-plus', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

if (!$erro && isset($add)) {
    include "../class/class.valida.php";

    $cpf = str_replace('-', '', str_replace('.', '', $form_cpf));

    $valida_nome = new Valida(trim($form_nome), 'Nome');
    $valida_nome->TamMinimo(5);
    $erro .= $valida_nome->PegaErros();

    $valida_cpf = new Valida(trim($form_cpf), 'CPF');
    $valida_cpf->TamMinimo(11);
    $erro .= $valida_cpf->PegaErros();

    $valida_telefone = new Valida(trim($form_telefone), 'Telefone');
    $valida_telefone->TamMinimo(1);
    $erro .= $valida_telefone->PegaErros();

    $valida_endereco = new Valida(trim($form_endereco), 'Endereço');
    $valida_endereco->TamMinimo(1);
    $erro .= $valida_endereco->PegaErros();

    $valida_solicitacao = new Valida($form_id_microregiao, 'Micro Região');
    $valida_solicitacao->TamMinimo(1);
    $erro .= $valida_solicitacao->PegaErros();

    // Verifica se o CPF já está cadastrado
    $query->exec("SELECT nome FROM canil.tutor WHERE cpf='$cpf'");
    if ($query->rows() > 0) {
        $query->proximo();
        $erro .= "CPF já cadastrado para " . $query->record[0] . " ! <br/>";
    }
}

if (!$erro && isset($add)) {
    if (trim($_FILES["form_arquivo"]["name"]) <> '') {
        $diretorio = "../attach";
        include('../includes/uploadarquivo.php');
    } else {
        $arquivo_nome = 'NULL';
    }
}

if (!$erro && isset($add)) {
    $query->begin();
    $query->insertTupla('canil.tutor', array(
        $form_nome,
        retira_acentos(trim($form_nome)),
        $cpf,
        $form_telefone,
        $form_endereco,
        $form_id_microregiao,
        $form_ponto_referencia,
        $_login,
        $_ip,
        $_data,
        $_hora,
        $form_tipo,
        $arquivo_nome,
        $form_adotante,
        $form_posoperatorio
    ));
    $id_tutor = $query->last_insert[0];

    if (!$erro && isset($form_nome)) {
        foreach ($form_nome as $obj) {
            $query->updateTupla1Coluna('canil.tutor', 'id_tutor', $id_tutor, $obj);
        }
    }
    $query->commit();


    $query->insertTupla('canil.tutor_movimentacoes', array(
        $id_tutor,
        "Tutor $form_nome cadastrado",
        "I",
        $_login,
        $_ip,
        $_data,
        $_hora
    ));
    $query->commit();

    if(!$erro){
?>
    <script type="text/javascript">
        window.location = ('CADASTROANIMAL_form.php?id_tutor=<?= $id_tutor ?>')
    </script>
<?
        exit;
    }
    
}

if($erro){
    echo callException($erro, 2);
}

?>


<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <div class="card p-0">

            <div class="card-header border-bottom-2 mb-1 bg-light-2">
                <div class="col-12 col-md-12 text-center">

                    <h5>
                        <b class="text-green">Cadastro de Tutor</b>
                    </h5>

                </div>
            </div>

            <div class="card-body">


                <div class="form-row">
                    <div class="form-group col- col-md-6">

                        <label for="form_nome" class="col-12 px-0"><span class="text-danger">*</span> Nome:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                            <input type="text" class="form-control" name="form_nome" id="form_nome" value="<? if ($erro) echo $form_nome; ?>" required>

                        </div>
                    </div>


                    <div class="form-group col- col-md-6">

                        <label for="form_cpf" class="col-12 px-0"><span class="text-danger">*</span> CPF:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                            <input type="text" class="form-control" name="form_cpf" id="form_cpf" value="<? if ($erro) echo $form_cpf; ?>" required>

                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6">

                        <label for="form_tipo" class="col-12 px-0"><span class="text-danger">*</span> Tipo:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                            <label class="btn btn-light">
                                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="T" <? if (isset($erro) && $form_tipo == 'T') echo 'checked' ?> required> Tutor
                            </label> &nbsp;

                            <label class="btn btn-light">
                                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="P" <? if (isset($erro) && $form_tipo == 'P') echo 'checked' ?> required> Protetor
                            </label> &nbsp;

                            <label class="btn btn-light">
                                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="C" <? if (isset($erro) && $form_tipo == 'C') echo 'checked' ?> required> Canil
                            </label> &nbsp;

                            <label class="btn btn-light">
                                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="F" <? if (isset($erro) && $form_tipo == 'F') echo 'checked' ?> required> Fiscalização
                            </label> &nbsp;

                            <label class="btn btn-light">
                                <input class="form_tipo" type="radio" name="form_tipo" id="form_tipo" value="A" <? if (isset($erro) && $form_tipo == 'A') echo 'checked' ?> required> Abrigo
                            </label> &nbsp;

                        </div>
                    </div>

                    <div class="form-group col-12 col-md-6">

                        <label for="form_adotante" class="col-12 px-0"><span class="text-danger">*</span> Adotante:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                            <label class="btn btn-light">
                                <input class="form_adotante" type="radio" name="form_adotante" id="form_adotante" value="S" <? if (isset($erro) && $form_adotante == 'S') echo 'checked' ?> required> Sim
                            </label> &nbsp;

                            <label class="btn btn-light">
                                <input class="form_adotante" type="radio" name="form_adotante" id="form_adotante" value="N" <? if (isset($erro) && $form_adotante == 'N') echo 'checked' ?> required> Não
                            </label> &nbsp;

                        </div>
                    </div>

                    <div class="form-group col-12 col-md-6">

                        <label for="form_posoperatorio" class="col-12 px-0"><span class="text-danger">*</span> Pós Operatório:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                            <label class="btn btn-light">
                                <input class="form_posoperatorio" type="radio" name="form_posoperatorio" id="form_posoperatorio" value="S" <? if (isset($erro) && $form_posoperatorio == 'S') echo 'checked' ?> required> Sim
                            </label> &nbsp;

                            <label class="btn btn-light">
                                <input class="form_posoperatorio" type="radio" name="form_posoperatorio" id="form_posoperatorio" value="N" <? if (isset($erro) && $form_posoperatorio == 'S') echo 'checked' ?> required> Não
                            </label> &nbsp;

                        </div>
                    </div>

                    <div class="form-group col-12 col-md-6">

                        <label for="for_arqivo" class="col-12 px-0"> Documento:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                            <input type="file" name="form_arquivo" class="borda_form" size="60" maxlength="25" value="">

                        </div>
                    </div>

                    <div class="form-group col- col-md-6">

                        <label for="form_telefone" class="col-12 px-0"><span class="text-danger">*</span> Telefone:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons" >

                            <input type="text" class="form-control" name="form_telefone" id="form_telefone" value="<? if ($erro) echo $form_telefone; ?>" required>

                        </div>
                    </div>

                    <div class="form-group col- col-md-6">

                        <label for="form_endereco" class="col-12 px-0"><span class="text-danger">*</span> Endereço:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                            <input type="text" class="form-control" name="form_endereco" id="form_endereco" value="<? if ($erro) echo $form_endereco; ?>" required>

                        </div>
                    </div>

                    <div class="form-group col-12 col-md-6">

                        <label for="form_macroregiao" class="col-12 px-0"> Macroregião:
                        </label>
                        <div class="col-md-8 mx-1">
                            <select name="form_macroregiao" class="form-control col-12">
                                <?php include('../includes/inc_select_macroregiao.php'); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-12 col-md-6">

                        <label for="form_id_microregiao" class="col-12 px-0"><span class="text-danger">*</span> Microregião:
                        </label>
                        <div class="col-md-8 mx-1">
                            <select name="form_id_microregiao" class="form-control col-12" required>
                                <?php include('../includes/inc_select_microregiao.php'); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col- col-md-6">

                        <label for="form_ponto_referencia" class="col-12 px-0"> Ponto de Referência:
                        </label>
                        <div class="btn-group btn-group-toggle mx-3" data-toggle="buttons">

                            <input type="text" class="form-control" name="form_ponto_referencia" id="form_ponto_referencia" value="<? if ($erro) echo $form_ponto_referencia; ?>">

                        </div>
                    </div>

                </div>

                <div>
                    <?
                    $btns = array('clean', 'save');
                    include('../includes/dashboard/footer_forms.php');
                    ?>
                </div>
            </div>

        </div>
    </form>
</section>

<? include('../includes/dashboard/footer.php'); ?>