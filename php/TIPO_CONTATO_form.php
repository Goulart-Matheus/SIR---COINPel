<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Tipo de Contato', 'fas fa-address-book','TIPO_CONTATO_viewDados.php');
$tab->setTab('Novo Tipo de Contato', 'fas fa-plus',  $_SERVER['PHP_SELF']);

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

                            $valida = new Valida($form_descricao, 'Descricao');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'tipo_contato',
                                array(
                                    trim($form_descricao),
                                    $form_mascara, 
                                    $form_habilitado,
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

                    <div class="form-group col-12 col-md-4">
                        <label for="form_descricao"><span class="text-danger">*</span> Descrição do Contato</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" maxlength="100" value="<? if ($erro) echo $form_descricao; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_mascara"><span class="text-danger">*</span> Forma de Preenchimento</label>
                        <input type="text" class="form-control form_mascara" name="form_mascara" id="form_mascara" maxlength="20" value="<? if ($erro) echo $form_mascara; ?>">
                        <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                        <div class="invalid-feedback">
                            Preencha o campo documento.
                        </div>

                    </div>


                    <div class="form-group col-12 col-md-4">
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
<script src="../assets/js/jquery.mask.js"></script>


<script type="text/javascript">

      $("#form_mascara").mask("0000000000");
    $('#form_mascara').mask('000.000.000-00', {
        reverse: false
    }).on("keyup", function(e) {

        if ($(this).val().length == 11
        ) {
            $(this).mask('000.000.000-00');
        } if ($(this).val().length == 13
        ) {
            $(this).mask('0000000000');  
        }else {
            $(this).mask('0000000000');
        }

    });
</script>-->