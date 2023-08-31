<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Tipo de Ocorrência', 'fa-sharp fa-solid fa-eye', 'TIPO_OCORRENCIA_viewDados.php');
$tab->setTab('Editar', 'fas fa-pencil-alt',  $_SERVER['PHP_SELF'] . '?id_tipo_ocorrencia=' . $id_tipo_ocorrencia);

$tab->printTab($_SERVER['PHP_SELF'] . '?id_tipo_ocorrencia=' . $id_tipo_ocorrencia);


$query->exec("SELECT t.id_tipo_ocorrencia,t.id_orgao,t.nome, t.descricao, t.habilitado, t.flag
                    FROM denuncias.tipo_ocorrencia as t, orgao as o
                   WHERE t.id_orgao = o.id_orgao
                   AND t.id_tipo_ocorrencia =" . $id_tipo_ocorrencia);

$query->proximo();

?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">

        <input type="hidden" name="id_tipo_ocorrencia" value="<? echo $query->record[0]; ?>">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">

                    <div class="col-12 col-sm-4 offset-sm-4">

                        <?
                        if (isset($edit)) {
                            include "../class/class.valida.php";

                            $valida = new Valida($form_nome, 'Nome da Ocorrência');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_descricao, 'Descrição da Ocorrência');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_habilitado, 'Habilitado');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($edit)) {

                            $query->begin();

                            $itens = array(
                                $form_orgao,
                                trim($form_nome),
                                trim($form_descricao),
                                $_login,
                                $_ip,
                                $_data,
                                $_hora,
                                $form_habilitado,
                                $form_flag
                            );

                            $where = array(0 => array('id_tipo_ocorrencia', $id_tipo_ocorrencia));
                            $query->updateTupla('denuncias.tipo_ocorrencia', $itens, $where);

                            $query->commit();
                        }

                        if ($erro) echo callException($erro, 2);

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
                            $form_elemento = isset($edit) ? $form_orgao : $query->record[1];
                            include('../includes/inc_select_orgao.php');

                            ?>

                        </select>

                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_nome"><span class="text-danger">*</span>Nome da Ocorrência</label>
                        <input type="text" class="form-control" name="form_nome" id="form_nome" maxlength="100" value="<? if (isset($edit)) echo $form_nome;
                                                                                                                        else echo trim($query->record[2]) ?>">
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group col-12 col-md-6">
                        <label for="form_descricao"><span class="text-danger">*</span>Descrição da Ocorrência</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" maxlength="50" value="<? if (isset($edit)) echo $form_descricao;
                                                                                                                                else echo trim($query->record[3]) ?>">
                    </div>

                    <div class="form-group col-12 col-md-3">
                        <label for="form_flag"><span class="text-danger">*</span> Tipo</label>
                        <select class="form-control" name="form_flag" id="form_flag">
                            <option value="A" <? if (isset($edit) && $form_flag == 'A') echo 'selected';
                                                else {
                                                    if (!isset($edit) && $query->record['flag'] == "A") {
                                                        echo 'selected';
                                                    }
                                                }  ?>>Administrativo</option>
                            <option value="P" <? if (isset($edit) && $form_flag == 'P') echo 'selected';
                                                else {
                                                    if (!isset($edit) && $query->record['flag'] == "P") {
                                                        echo 'selected';
                                                    }
                                                }  ?>>Público</option>

                        </select>
                    </div>


                    <div class="form-group col-12 col-md-3">
                        <label for="form_Habilitado"><span class="text-danger">*</span> Habilitado</label>
                        <select class="form-control" name="form_habilitado" id="form_habilitado">
                            <option value="S" <? if (isset($edit) && $form_habilitado == 'S') echo 'selected';
                                                else {
                                                    if (!isset($edit) && $query->record[4] == "S") {
                                                        echo 'selected';
                                                    }
                                                }  ?>>Sim</option>
                            <option value="N" <? if (isset($edit) && $form_habilitado == 'N') echo 'selected';
                                                else {
                                                    if (!isset($edit) && $query->record[4] == "N") {
                                                        echo 'selected';
                                                    }
                                                }  ?>>Não</option>

                        </select>
                    </div>

                </div>

            </div>

            <div class="card-footer bg-light-2">
                <?
                $btns = array('reload', 'edit');
                include('../includes/dashboard/footer_forms.php');
                ?>
            </div>

        </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
?>