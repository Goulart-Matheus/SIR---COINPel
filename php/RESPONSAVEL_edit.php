<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', 'RESPONSAVEL_form.php');
$tab->setTab('Pesquisar', 'fas fa-search', 'RESPONSAVEL_view.php');
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

$query->exec("SELECT id_resposnavel, nome , cpf, rg , dt_nascimento, endereco , id_bairro  
              FROM responsavel 
              WHERE id_resposnavel = " . $id_responsavel);

$query->result($query->linha);

?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">

        <input type="hidden" name="id_responsavel" value="<? echo $query->record[0]; ?>">

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

                            $valida = new Valida($form_responsavel, 'Responsável');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_mascara, 'CPF');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_rg, 'RG');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_rg, 'Data de nascimento');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_endereco, 'Endereço');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($edit)) {

                            $query->begin();

                            $itens = array(
                                $id_responsavel,
                                trim($form_responsavel),
                                    $form_mascara, //CPF
                                    $form_rg,
                                    $form_dt_nascimento,
                                    $form_endereco,
                                    $form_bairro,
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,

                            );

                            $where = array(0 => array('id_responsavel', $id_responsavel));
                            $query->updateTupla('responsavel', $itens, $where);

                            $query->commit();
                        }

                        if ($erro) echo callException($erro, 2);

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">
                    <div class="form-group col-12 ">
                        <label for="form_responsavel"><span class="text-danger">*</span> Nome :</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="100" value="<? if ($edit) echo trim($form_responsavel);
                                                                                                                                        else echo trim($query->record[1]); ?>">
                    </div>
            
                </div> 
                <div class="form-row">
                    <div class="form-group col-12 col-md-4">
                    <label for="form_mascara"><span class="text-danger">*</span>CPF: </label>
                    <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($edit) echo trim($form_mascara);
                                                                                                                                                    else echo trim($query->record[2]); ?>">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_rg"><span class="text-danger">*</span> RG :</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_rg" id="form_rg" maxlength="100" value="<? if ($edit) echo trim($form_rg);
                                                                                                                                                   else echo trim($query->record[3]); ?>">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_nascimento"><span class="text-danger">*</span> Data de nascimento :</label>
                        <input type="date" class="form-control" name="form_dt_nascimento" id="form_dt_nascimento" maxlength="100" value="<? if ($edit) echo trim($form_dt_nascimento);
                                                                                                                                            else echo trim($query->record[4]); ?>">
                    </div>
            
                </div>
                <div class="form-row">
                    <div class="form-group col-12 col-md-8">
                        <label for="form_endereco"><span class="text-danger">*</span> Endereço :</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_endereco" id="form_endereco" maxlength="100" value="<? if ($edit) echo trim($form_endereco);
                                                                                                                                  else echo trim($query->record[5]); ?>">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_bairro"><span class="text-danger">*</span> Bairro :</label>
                        <select name="form_bairro" id="form_bairro" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_bairro : "";
                            include("../includes/inc_select_bairro.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o bairro.
                        </div>
                        </div>

                    </div>

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
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/jquery.mask.js"></script>
<script type="text/javascript">

    $('#form_mascara').mask('000.000.000-00', {
        reverse: false
    }).on("keyup", function(e) {

        if ($(this).val().length == 11
        ) {
            $(this).mask('000.000.000-00');
        } 

    });
</script>