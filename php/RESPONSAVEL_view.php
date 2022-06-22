<?

include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../includes/variaveisAmbiente.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', 'RESPONSAVEL_form.php');
$tab->setTab('Pesquisar', 'fas fa-search', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

?>

<section class="content">

    <form method="POST" action="RESPONSAVEL_viewDados.php">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <? if ($erro) echo callException($erro, 2); ?>
                    </div>
                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">

                    <div class="form-group col-12 col-md-6 ">
                        <label for="form_responsavel">Nome: </label>
                        <input type="text" class="form-control" name="form_responsavel" id="form_responsavel" value="<? if ($erro) echo $form_responsavel; ?>">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="form_mascara">CPF: </label>
                        <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($erro) echo $form_mascara; ?>">
                        <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="form_rg">RG: </label>
                        <input type="text" class="form-control" name="form_rg" id="form_rg" value="<? if ($erro) echo $form_rg; ?>">
                    </div>                

                </div>

            </div>
            <div class="card-body pt-0">

                <div class="form-row">
                
                <div class="form-group col-12 col-md-4">
                        <label for="form_bairro"><span class="text-danger">*</span> Bairro :</label>
                        <select name="form_bairro" id="form_bairro" class="form-control" >
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

            <div class="card-footer border-top-0 bg-transparent">

                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info" type="submit" name="add" value="Buscar">
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
        ) {                                   // IMPORTANTE NÃO ESQUECER
            $(this).mask('000.000.000-00');   // verificar mais tarde a escolha tem que ser dinamica
        } 

    });
</script>



