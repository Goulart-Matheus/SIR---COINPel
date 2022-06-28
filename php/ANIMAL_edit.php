<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', 'ANIMAL_form.php');
$tab->setTab('Pesquisar', 'fas fa-search', 'ANIMAL_view.php');
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

$query->exec("SELECT id_animal , nro_ficha , nro_chip , id_pelagem , id_especie , sexo , observacao  FROM animal WHERE id_animal = " . $id_animal);
$query->result($query->linha);


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

                            $valida = new Valida($form_nro_ficha, 'Nro_ficha');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_nro_chip, 'Nro_chip');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_id_pelagem, 'id_pelagem ');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_id_especie, 'id_especie ');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_sexo, 'sexo ');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_observacao, 'observacao ');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($edit)) {

                            $query->begin();

                            $itens = array(
                                $id_animal,
                                trim($form_nro_ficha),
                                $nro_chip,
                                $id_pelagem,
                                $id_especie,
                                $sexo,
                                $observacao,
                                $_login,
                                $_ip,
                                $_data,
                                $_hora,

                            );

                            $where = array(0 => array('id_animal', $id_animal));
                            $query->updateTupla('animal', $itens, $where);

                            $query->commit();
                        }

                        if ($erro) echo callException($erro, 2);

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">

                    <div class="form-group col-12 col-md-6">
                        <label for="form_nro_ficha"></span> Nunero Ficha</label>
                        <input type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha" maxlength="100" value="<? if ($erro) echo $form_nro_ficha; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_nro_chip"></span> Nunero Chip</label>
                        <input type="text" class="form-control" name="form_nro_chip" id="form_nro_chip" maxlength="100" value="<? if ($erro) echo $form_nro_chip; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_sexo"></span>Sexo</label>
                        <input type="text" class="form-control" name="form_sexo" id="form_sexo" maxlength="100" value="<? if ($erro) echo $form_sexo; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_pelagem"></span> Pelagem</label>
                        <input type="text" class="form-control" name="form_id_pelagem" id="form_id_pelagem" maxlength="100" value="<? if ($erro) echo $form_id_pelagem; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_especie"></span>Especie</label>
                        <input type="text" class="form-control" name="form_id_especie" id="form_id_especie" maxlength="100" value="<? if ($erro) echo $form_id_especie; ?>">
                    </div>


                </div>


            </div>

            <div class="card-footer border-top-0 bg-transparent">

                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info" type="submit" name="edit" value="Salvar">
                </div>

            </div>

        </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
?>