<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Bairros', 'fas fa-home', 'BAIRRO_viewDados.php');
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

$query->exec("SELECT id_bairro , descricao, habilitado  FROM bairro WHERE id_bairro = " . $id_bairro);
$query->result($query->linha);

?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">

        <input type="hidden" name="id_bairro" value="<? echo $query->record[0]; ?>">

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

                            $valida = new Valida($form_descricao, 'Descrição');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_habilitado, 'Habilitado');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($edit)) {

                            $query->begin();

                            $itens = array(                                
                                trim($form_descricao),
                                $form_habilitado,
                                $_login,
                                $_ip,
                                $_data,
                                $_hora,

                            );

                            $where = array(0 => array('id_bairro', $id_bairro));
                            $query->updateTupla('bairro', $itens, $where);

                            $query->commit();
                        }

                        if ($erro) echo callException($erro, 2);

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">


                    <div class="form-group col-6 col-md-6">
                        <label for="form_descricao"><span class="text-danger">*</span> Descrição </label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if ($edit) echo trim($form_descricao);
                                                                                                                    else echo trim($query->record[1]); ?>">
                    </div>



                    <div class="form-group col-6 col-md-6">
                        <label for="form_habilitado"><span class="text-danger">*</span> Habilitado</label>
                        <select class="form-control" name="form_habilitado" id="form_habilitado">
                            <option value="S" <? if ($edit && $form_habilitado == 'S') echo 'selected';  else { if(!$edit && $query->record[2] == "S") {echo 'selected'; } }  ?>>Sim</option>
                            <option value="N" <? if ($edit && $form_habilitado == 'N') echo 'selected';  else { if(!$edit && $query->record[2] == "N") {echo 'selected'; } }  ?>>Não</option>
                        </select>
                    </div>


                </div>

                               
            </div>

            <div class="card-footer border-top-0 bg-transparent">

            <div class="card-footer bg-light-2">
                <?
                $btns = array('clean', 'edit');
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