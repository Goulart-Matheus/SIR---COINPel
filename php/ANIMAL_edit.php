<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Animais', 'fa-solid fa-dog', 'ANIMAL_viewDados.php');
$tab->setTab('Animal', 'fa-solid fa-dog', 'ANIMAL_cover.php?id_animal='   . $id_animal);
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);


$query->exec(
    "SELECT a.id_animal , a.nro_ficha , a.nro_chip , a.sexo , pe.descricao as pelagem , es.descricao as especie , a.observacao, es.id_especie, pe.id_pelagem
    FROM    animal as a, pelagem as pe, especie as es
    WHERE   a.id_especie = es.id_especie AND
            a.id_pelagem = pe.id_pelagem AND
            a.id_animal= $id_animal"
);

$query->result($query->linha);

?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">

        <input type="hidden" name="id_animal" value="<? echo $query->record[0]; ?>">

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
                        }

                        if (!$erro && isset($edit)) {

                            $query->begin();

                            $itens = array(
                                $form_nro_ficha,
                                $form_nro_chip,
                                $form_id_pelagem,
                                $form_id_especie,
                                $form_sexo,
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

                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_ficha"></span> Nunero Ficha</label>
                        <input type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha" maxlength="100" value="<? if ($edit) echo $form_nro_ficha;
                                                                                                                                    else echo trim(str_pad($query->record[1],6,0, STR_PAD_LEFT)) ?>" disabled>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_chip"></span> Nunero Chip</label>
                        <input type="text" class="form-control" name="form_nro_chip" id="form_nro_chip" maxlength="100" value="<? if ($edit) echo $form_nro_chip;
                                                                                                                                else echo trim($query->record[2]) ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_sexo"></span>Sexo</label>
                        <select name="form_sexo" id="form_sexo" class="form-control" value="<? if ($edit) echo $form_sexo;
                                                                                            else echo trim($query->record[3]) ?>">
                            <option value="M" <? if ($edit && $form_sexo == 'M') echo 'selected';
                                                else {
                                                    if (!$edit && $query->record[3] == "M") {
                                                        echo 'selected';
                                                    }
                                                }  ?>>Macho</option>
                            <option value="F" <? if ($edit && $form_sexo == 'F') echo 'selected';
                                                else {
                                                    if (!$edit && $query->record[3] == "F") {
                                                        echo 'selected';
                                                    }
                                                }  ?>>Fêmea</option>
                        </select>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_pelagem"></span> Pelagem</label>
                        <select class="form-control" name="form_id_pelagem" id="form_id_pelagem" required>
                            <?
                            $form_elemento = $edit ? $form_id_pelagem : $query->record[8];
                            include("../includes/inc_select_pelagem.php");
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_especie"></span>Especie</label>
                        <select class="form-control select2_animal-especie" name="form_id_especie" id="form_id_especie" required>
                            <?
                            $where="";
                            $form_elemento = $edit ? $form_id_especie : $query->record[7];
                            include("../includes/inc_select_especie.php");
                            echo $query->record[5];
                            ?>
                        </select>
                    </div>


                    <div class="form-group col-12 ">

                        <label for="form_observacao">Observação</label>
                        <input type="text" class="form-control" name="form_observacao" id="form_observacao" maxlength="200" value="<? if ($edit) echo $form_observacao;
                                                                                                                                    else echo trim($query->record[6]) ?>">
                    </div>

                </div>

            </div>

            <div class="card-footer border-top-0 bg-transparent">

                <div class="card-footer bg-light-2">
                    <?
                    $btns = array('reload', 'edit');
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
<script src="../includes/inc_select_pelagem.php"></script>
<script>
    $(document).ready(function() {

        if ($(".select2_animal-especie").length > 0) {
            $(".select2_animal-especie").attr('data-live-search', 'true');

            $(".select2_animal-especie").select2({
                width: '100%'
            });
        }
    });
</script>