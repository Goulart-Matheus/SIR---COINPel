<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('URM', 'fab fa-asymmetrik','URM_viewDados.php');
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);


$query->exec("SELECT id_urm, valor, ativo, mes_referencia, ano_referencia
              FROM urm  WHERE id_urm = $id_urm");

$query -> proximo();

?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">

        <input type="hidden" name="id_urm" value="<? echo $query->record[0]; ?>">

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

                            $valida = new Valida($form_valor, 'Valor');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_mes_referencia, 'Mes_referencia');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_ano_referencia, 'Ano_referencia');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_ativo, 'Ativo');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($edit)) {

                            $query->begin();

                            $itens = array(                                
                                trim($form_valor),
                                $form_ativo,
                                $form_mes_referencia,
                                $form_ano_referencia,
                                $_login,
                                $_ip,
                                $_data,
                                $_hora,

                            );

                            $where = array(0 => array('id_urm', $id_urm));
                            $query->updateTupla('urm', $itens, $where);

                            $query->commit();
                            var_dump($query->sql);

                        }

                        if ($erro) echo callException($erro, 2);

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">


                    <div class="form-group col-6 col-md-6">
                        <label for="form_valor"></span> Valor</label>
                        <input type="text" class="form-control" name="form_valor" id="form_valor" value="<? 
                       
                   echo ($query->record[1]); ?>">                                                                                 
                    
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="form_ativo"></span> Ativo</label>
                        <select class="form-control" name="form_ativo" id="form_ativo">                           
                            <option value="S" <? if ($edit && $form_ativo == 'S') echo 'selected';  else { if(!$edit && $query->record[2] == "S") {echo 'selected'; } }  ?>>Sim</option>
                            <option value="N" <? if ($edit && $form_ativo == 'N') echo 'selected';  else { if(!$edit && $query->record[2] == "N") {echo 'selected'; } }  ?>>Não</option>
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="form_mes_referencia"></span> Mes Referencia</label>
                        <input type="text" class="form-control" name="form_mes_referencia" id="form_mes_referencia" value="<?
                    echo ($query->record[3]); ?>">
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="form_ano_referencia"></span> Ano Referencia</label>
                        <input type="text" class="form-control" name="form_ano_referencia" id="form_ano_referencia" value="<?
                     if ($edit) echo trim($form_ano_referencia);
                     else echo trim($query->record[4]); ?>">
                    </div>

                </div>


            </div>

            <div class="card-footer bg-light-2">
                <?
                $btns = array('clean', 'edit');
                include('../includes/dashboard/footer_forms.php');
                ?>
            </div>

        </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
?>