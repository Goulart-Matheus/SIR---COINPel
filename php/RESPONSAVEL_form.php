<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar', 'fas fa-search', 'RESPONSAVEL_view.php');

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

                        
                            $valida = new Valida($form_responsavel, 'Responsável');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_mascara, 'CPF');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_rg, 'RG');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_endereco, 'Endereço');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'Responsavel',
                                array(
                                    trim($form_responsavel),
                                    $form_mascara, // CPF
                                    $form_rg,
                                    $form_dt_nascimento,
                                    $form_endereco,
                                    $form_bairro,
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
                
                    <div class="form-group col-12 ">
                        <label for="form_responsavel"><span class="text-danger">*</span> Nome :</label>
                        <input type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="100" value="<? if ($erro) echo $form_responsavel; ?>">
                    </div>
                    
            
                </div>

                <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_mascara"><span class="text-danger">*</span>CPF: </label>
                        <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($erro) echo $form_mascara; ?>">
                        <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_rg"><span class="text-danger">*</span> RG :</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_rg" id="form_rg" maxlength="100" value="<? if ($erro) echo $form_rg; ?>">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_nascimento"><span class="text-danger">*</span> Data de nascimento :</label>
                        <input type="date" class="form-control" name="form_dt_nascimento" id="form_dt_nascimento" maxlength="100" value="<? if ($erro) echo $form_dt_nascimento; ?>">
                    </div>
                
                </div>



                <div class="form-row">
                    <div class="form-group col-12 col-md-8">
                        <label for="form_endereco"><span class="text-danger">*</span> Endereço :</label>
                        <input type="text" class="form-control" name="form_endereco" id="form_endereco" maxlength="100" value="<? if ($erro) echo $form_endereco; ?>">
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
               
               
                <!-- Realizando de testes ainda não funiona a modal-->
                <!--inicio modal -->
                
                
                <div class="form-row">                
                
                <div class="form-group col-12 ">&nbsp;&nbsp;
                 <label for="form_animal col-12"><span class="text-danger" >*</span>  Animais :&nbsp;</label>   

                    <button type="button" class="btn btn-dark text-white" data-toggle="modal" data-target="#modal_animal_cadastro">
                             <i class="fas fa-plus"></i>
                        </button>


                        <div class="modal animal" id="modal_animal_cadastro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">

                            <div class="modal-dialog modal-xl">

                                <div class="modal-content">

                                    <form action="" method="post">

                                        <div class="modal-header bg-gradient-info-purple">
                                            <h5 class="modal-title"><i class="fas fa-project-diagram"></i> Adicionar Animal:</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                        </div>

                                        <div class="modal-body">

                                            <div class="form-group col-12">

                                            <div class="form-row">

                                                <div class="form-group col-12 col-md-6 ">
                                                    <label for="form_nro_ficha"><span class="text-danger">*</span> Numero da ficha:</label>
                                                    <input type="text" name="form_nro_ficha" id="form_nro_ficha" class="form-control">
                                                </div>

                                                <div class="form-group col-12 col-md-6 ">
                                                    <label for="form_nro_chip"><span class="text-danger">*</span> Numero do chip:</label>
                                                    <input type="text" name="form_nro_chip" id="form_nro_chip" class="form-control">
                                                </div>

                                            </div>
                                            <div class="form-row">
                    
                                                <div class="form-group col-12 col-md-6">
                                                    <label for="form_tipo_pelagem"><span class="text-danger">*</span> Pelagem:</label>
                                                    <select name="form_tipo_pelagem" id="form_tipo_pelagem" class="form-control">
                                                        <?
                                                            $form_elemento = $erro ? $form_tipo_pelagem : "";
                                                            include("../includes/inc_select_pelagem.php");
                                                        ?>
                                                    </select>
                                                </div>
                                        
                                                <div class="form-group col-12 col-md-6">
                                                    <label for="form_tipo_especie"><span class="text-danger">*</span> Espécie:</label>
                                                    <select name="form_tipo_especie" id="form_tipo_especie" class="form-control">
                                                        <?
                                                            $form_elemento = $erro ? $form_tipo_especie : "";
                                                            include("../includes/inc_select_especie.php");
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>        


                                            <div class="form-row">
                    
                                                    <div class="form-group col-12 col-md-6">
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

                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" name="add_animal_modal" class="btn btn-info">
                                                    <i class="fas fa-check"></i>&nbsp;
                                                        Salvar
                                                </button>
                                            </div>

                                    </form>

                                    </div>

                            </div>

                        </div>
                        <button type="submit" name="add_animal_modal" class="btn btn-info">
                            <i class="fas fa-check"></i>&nbsp;
                                Salvar
                        </button>
                </div>

                                                

                
                
                <!--final  modal -->


            </div>   

            <div class="card-footer border-top-0 bg-transparent">
                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info" type="submit" name="add" value="Salvar">
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
<script>
$("#modal_animal_cadastro").on('click', function() {

var nro_ficha   = $("#nro_ficha").val();
var nro_chip    = $("#nro_chip").val();
var id_pelagem  = $("#id_pelagem").val();
var id_especie  = $("#id_especie").val();
var sexo        = $("#sexo").val();

$.ajax({
    type: "post",
    url: "../includes/ajax_add_animal.php",
    data: {
        "nro_ficha" : nro_ficha,
        "nro_chip"  : nro_chip,
        "id_pelagem": id_pelagem,
        "id_especie": id_especie,
        "sexo"      : sexo
        },
    dataType: "json",
    beforeSend: function() {

        $("#modal_animal_cadastro").modal('hide');
    },
    success: function(response) {

        var option = "<option value='" + response['id_animal'] + "' selected>" + response['nro_ficha'] + "</option>";

        $("#form_animal").append(option)

    },
    error: function(response) {

    }
});

});
</script>
