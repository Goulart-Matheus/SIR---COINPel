<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Animais', 'fa-solid fa-dog', 'ANIMAL_viewDados.php');
$tab->setTab('Novo Animal', 'fas fa-plus', $_SERVER['PHP_SELF']);


$tab->printTab($_SERVER['PHP_SELF']);
$link = isset($id_responsavel) && $id_responsavel != "" ? "?id_responsavel=$id_responsavel" : "";


$query_count = new Query($bd);
$query_count->exec("SELECT nro_ficha FROM animal ORDER BY nro_ficha DESC");
$query_count->proximo();

$t = $query_count->rows();
if($t  > 0){
   // $nro_ficha = $query_count->record[0 ]+ 1;
   $contagem_ficha = $query_count->record[0] + 1;
}else{
    $contagem_ficha = 1;
}



?>
<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF'] . $link ?>" enctype="multipart/form-data">

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

                            $query_aux  = new Query($bd);
                            $query_aux1 = new Query($bd);


                            // $valida = new Valida($form_nro_chip, 'Nro_chip');
                            // $valida->TamMinimo(1);
                            // $erro .= $valida->PegaErros();


                            $valida = new Valida($form_sexo, 'Sexo');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_id_pelagem, 'Id_pelagem');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_id_especie, 'Id_especie');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            // Validação testa se o nro_ficha e o nro_chip já estão cadastrados no BD
                            // inicio

                            if($form_nro_chip != ''){
                            
                                $query_aux1->exec("SELECT id_animal
                                                        FROM animal
                                                        WHERE nro_chip = '$form_nro_chip'
                                            ");
                                if ($query_aux1->rows() > 0) {
                                    $erro .= "O chip de numero $form_nro_chip, já esta cadastrado no sistema";
                                }

                            }
                            //fim 



                        }

                        if($form_nro_chip == ''){
                            $form_nro_chip = 'NULL';
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'animal',
                                array(
                                    trim($contagem_ficha),
                                    $form_nro_chip,
                                    $form_id_pelagem,
                                    $form_id_especie,
                                    $form_sexo,
                                    $form_observacao,
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,

                                )
                            );
                            $id_animal = $query->last_insert[0];

                            $query->commit();

                            if ($id_responsavel != "") {
                                $query->insertTupla(
                                    'animal_responsavel',
                                    array(
                                        $id_animal,
                                        $id_responsavel,
                                        $auth->getUser(),
                                        $_ip,
                                        $_data,
                                        $_hora,
                                    )
                                );
                            }
                        }

                        if ($erro)

                            echo callException($erro, 2);

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_ficha"><span class="text-danger">*</span> Numero Ficha</label>
                        <input type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha" maxlength="100" value="<?= str_pad($contagem_ficha,6,0, STR_PAD_LEFT) ?>" disabled>
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_chip"> Numero Chip</label>
                        <input type="text" class="form-control" name="form_nro_chip" id="form_nro_chip" maxlength="100" value="<? if ($erro) echo $form_nro_chip; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_sexo"><span class="text-danger">*</span> Sexo</label>
                        <select name="form_sexo" required id="form_sexo" class="form-control">
                            <option value="" selected>Selecione o sexo:</option>
                            <option value="M">Macho</option>
                            <option value="F">Fêmea</option>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o sexo do animal.
                        </div>
                    </div>
                </div>
                <div class="form-row">


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_pelagem"><span class="text-danger">*</span>Pelagem</label>
                        <select name="form_id_pelagem" id="form_id_pelagem" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_id_pelagem : "";
                            include("../includes/inc_select_pelagem.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha a Pelagem
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_especie"><span class="text-danger">*</span>Espécie</label>
                        <select name="form_id_especie" id="form_id_especie" class="form-control select2_animal-especie" required>
                            <?
                            $where="";
                            $form_elemento = $erro ? $form_especie : "";
                            include("../includes/inc_select_especie.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha Especie
                        </div>
                    </div>


                </div>
                <div class="form-row">

                    <div class="form-group col-12">
                        <label for="form_observacao">Observação</label>
                        <input type="text" class="form-control" name="form_observacao" id="form_observacao" maxlength="200" value="<? if ($erro) echo $form_observacao; ?>">
                    </div>

                </div>

                <script>
                    <?
                    if ($id_responsavel != "") {
                        link('RESPONSAVEL_cover.php?id_responsavel=', $id_responsavel);
                    }
                    ?>
                </script>

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