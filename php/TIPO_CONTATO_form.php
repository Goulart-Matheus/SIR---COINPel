<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar', 'fas fa-search', 'TIPO_CONTATO_view.php');

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

                            $valida = new Valida($form_mascara, 'Mascara');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_habilitado, 'Habilitado');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'tipo_contato',
                                array(
                                    trim($form_descricao),
                                    $form_tipo_mascara,
                                    $habilitado,
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
                        <label for="form_descricao"><span class="text-danger">*</span> Nome Solicitante</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" maxlength="100" value="<? if ($erro) echo $form_descricao; ?>">
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_tipo_mascara"><span class="text-danger">*</span> Tipo de Documento</label>
                        <select name="form_tipo_mascara" id="form_tipo_mascara" class="form-control" required>
                            <option value="" <? if ($erro && $form_tipo_mascara == "") echo "selected"; ?>>Selecione uma opção:</option>
                            <option value="RG" <? if ($erro && $form_tipo_mascara == "RG") echo "selected"; ?>>RG</option>
                            <option value="CPF" <? if ($erro && $form_mascara == "CPF") echo "selected"; ?>>CPF</option>
                            <option value="NI" <? if ($erro && $form_contactante == "NI") echo "selected"; ?>>Não Informado</option>
                        </select>
                        <div class="invalid-feedback">
                            Escolha uma opção.
                        </div>
                    </div>

                </div>

                <div class="form-row">

                    <div class="form-group col-12 col-md-6">
                        <label for="form_dt_solicitacao"><span class="text-danger">*</span> Data da Solicitação</label>
                        <input type="date" class="form-control" name="form_dt_solicitacao" id="form_dt_solicitacao" required value="<? if ($erro) echo $form_dt_solitacao; ?>" />
                        <div class="invalid-feedback">
                            Preencha a data da Solicitação.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_nome"><span class="text-danger">*</span> Habilitado</label>
                        <select class="form-control" name="form_habilitado">
                            <option value="S" <? if ($erro && $form_habilitado == "S") echo 'selected';
                                                else echo 'selected'; ?>>Sim</option>
                            <option value="N" <? if ($erro && $form_habilitado == "N") ?>>Não</option>
                        </select>
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


<script>

function mascara_identidade(elemento) {
        $(elemento).mask('0000000000');
    }


function mascara_cpf(elemento) {
        $(elemento).mask('000.000.000-00');
    }


</script>
