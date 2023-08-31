<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Tipo de Ocorrência', 'fa-sharp fa-solid fa-eye', 'TIPO_OCORRENCIA_viewDados.php');
$tab->setTab('Novo Tipo de Ocorrência', 'fas fa-plus',  $_SERVER['PHP_SELF']);

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

                            $valida = new Valida($form_nome, 'Nome da Ocorrência');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_descricao, 'Descricao da Ocorrência');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'denuncias.tipo_ocorrencia',
                                array(
                                    $form_orgao,
                                    trim($form_nome),
                                    trim($form_descricao),
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,
                                    $form_habilitado,
                                    $form_flag
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

                    <div class="form-group col-12 col-sm-6">

                        <label for="form_area">Orgão Responsável</label>

                        <select class="form-control" name="form_orgao" id="form_orgao">

                            <?
                            $where         = "";
                            $form_elemento = $erro ? $form_orgao : $id_orgao;
                            include('../includes/inc_select_orgao.php');

                            ?>

                        </select>

                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_nome"><span class="text-danger">*</span>Nome da Ocorrência</label>
                        <input type="text" class="form-control" name="form_nome" id="form_nome" maxlength="100" value="<? if ($erro) echo $form_nome; ?>">
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group col-12 col-md-6">
                        <label for="form_descricao"><span class="text-danger">*</span>Descrição da Ocorrência</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" maxlength="50" value="<? if ($erro) echo $form_descricao; ?>">

                    </div>

                    <div class="form-group col-12 col-md-3">
                        <label for="form_flag"><span class="text-danger">*</span>Tipo</label>
                        <select class="form-control" name="form_flag" id="form_flag">
                            <option value="A" selected>Administrativo</option>
                            <option value="P">Público</option>
                        </select>
                    </div>


                    <div class="form-group col-12 col-md-3">
                        <label for="form_Habilitado"><span class="text-danger">*</span> Habilitado</label>
                        <select class="form-control" name="form_habilitado" id="form_habilitado">
                            <option value="S" selected>Sim</option>
                            <option value="N">Não</option>
                        </select>
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

<script src="../assets/js/jquery.js"></script>