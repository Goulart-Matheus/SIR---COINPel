<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar', 'fas fa-search', 'RESPONSAVEL_view.php');

$tab->printTab($_SERVER['PHP_SELF']);
$link = isset($id_animal) && $id_animal != "" ? "?id_animal=$id_animal" : "";
?>
<section class="content">

<form method="post" id= "form.cadresponsavel" action="<? echo $_SERVER['PHP_SELF'] . $link ?>" enctype="multipart/form-data"> 


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
                            
                            foreach ($form_valor_contato as $val) {
                                $valida = new Valida($val[0], 'Contato');
                                $valida->TamMinimo(1);
                                $erro .= $valida->PegaErros();
                            }
                            
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'responsavel',
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
                            
                            $id_responsavel = $query->last_insert[0];
                                                                          
                            foreach($form_valor_contato as $val){
                            $query->insertTupla(
                                'responsavel_contato',
                                array(
                                    $id_responsavel,
                                    $form_tipo_contato[0],
                                    $val,
                                    $form_principal, 
                                    $auth->getUser(),
                                    $_ip,
                                    $_data,
                                    $_hora,
                                    
                                )
                               
                            );
                              
                        }
                           
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
                
                    <div class="form-group col-6 ">
                        <label for="form_responsavel"><span class="text-danger">*</span> Nome :</label>
                        <input type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="100" value="<? if ($erro) echo $form_responsavel; ?>">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="form_mascara"><span class="text-danger">*</span>CPF: </label>
                        <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($erro) echo $form_mascara; ?>">
                        <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="form_rg"><span class="text-danger">*</span> RG :</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_rg" id="form_rg" maxlength="100" value="<? if ($erro) echo $form_rg; ?>">
                    </div>    
                </div>

                <div class="form-row">                                        
                    <div class="form-group col-12 col-md-2">
                        <label for="form_dt_nascimento"><span class="text-danger">*</span> Data de nascimento :</label>
                        <input type="date" class="form-control" name="form_dt_nascimento" id="form_dt_nascimento" maxlength="100" value="<? if ($erro) echo $form_dt_nascimento; ?>">
                    </div>
                    
                    <div class="form-group col-12 col-md-6">
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

                <div class="form-row ">

                    <div class="form-group col-12 ">

                        <p class="text-center py-2 bg-dark" >
                            Contatos :
                        </p>

                    </div>

                </div>

                <div class="form-row">
                   
                <label for="form_contato"><span class="text-danger">*</span> Contatos:</label>
                    <div class="form-group col-12" id="container_dinamico">

                        <?
                        $qnt = 1;

                        if ($erro) {
                            $qnt = count($form_principal);
                        }

                        for ($c = 0; $c < $qnt; $c++) {

                        ?>

                            <div class="input-group ml-0 mb-2" id="campo_dinamico">

                           
                                <select name="form_tipo_contato[]" id="form_tipo_contato" class="form-control form_tipo_contato" required>
                                    <? $form_elemento = $erro ? $form_tipo_contato :  include("../includes/inc_select_tipo_contato.php"); ?>
                                </select>
                              
                                <input type="text"  name="form_valor_contato[]" id="form_valor_contato"  class="form-control col-md-7 form_valor_contato" placeholder="Contato" value="<? if ($erro) echo $form_valor_contato[$c]; ?>" /> 
                                <input type="text" disabled="" class="form-control col-md-1 text-center" placeholder="Principal"/>
                                <select name="form_principal"  required id="form_principal" required  class="form-control col-md-1">
                                <option value="" selected>Selecione</option>
                                    <option value="S">Sim</option>
                                    <option value="N">Não</option>
                                </select>

                                <div class="input-group-append">

                                    <? if ($c == $qnt - 1) { ?>

                                        <a class="btn btn-success " id="novo_campo_dinamico" href="#form_principal">+</a>

                                    <? } else { ?>

                                        <a class="btn btn-danger" id="remove_campo_dinamico" href="#form_principal">x</a>

                                    <? } ?>

                                </div>
                               
                            </div>
                                       
                        <?

                        }

                        ?>
                    </div>
                </div>
                 
            <div class="card-footer border-top-0 bg-transparent">
                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info ad_duplicidade" type="button" name="add" value="Salvar">
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

    $('#form_mascara').mask('000.000.000-00');
    $('#form_rg').mask('00000000000000');
    $(document).on('change','.form_tipo_contato',function(){
       var mascara = $(this).find(':selected').data('mascara');
       if(mascara == 'email'){
        $(this).parents('#campo_dinamico').find('.form_valor_contato').attr('type','email');
       }
       else {
        $(this).parents('#campo_dinamico').find('.form_valor_contato').attr('type','text').mask(mascara);
       }
    });

</script>


<script src="../assets/js/jquery.js"></script>

<script>

    $(document).on('click', '.ad_duplicidade', function() {
  var nome= 'boi';
  var cpf= '';
  console.log(nome);
        $.ajax({
            type: "GET",
            url: "../includes/ajax_valida_dup_responsavel_animal.php",


               data: {
                'nome': nome,
                'cpf': cpf
            },
            success: function(r) { 
                
                if (r[0]["status"] == 0) {const form = document.querySelector('form'); form.submit()}



                if (r[0]["status"] == 1) {
                    console.log("coringa");

                    var modal = '<div class="modal" tabindex="-1" role="dialog" id="modal_dup_responsavel_animal">';
                    modal += '<div class="modal-dialog modal-xl" role="document">';
                    modal += '<div class="modal-content">';
                    modal += '<div class="modal-header">';
                    modal += '<h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning"></i> Possível Duplicidade</h5>';
                    modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    modal += '<span aria-hidden="true">&times;</span>';
                    modal += '</button>';
                    modal += '</div>';
                    modal += '<div class="modal-body">';
                    modal += '<table class="table table-striped">';
                    modal += '<thead>';
                    modal += '<tr>';
                    modal += '<th>ID</th>';
                    modal += '<th>Nome</th>';
                    modal += '<th class="text-center">CPF</th>';
                    modal += '<th class="text-center">RG(a)</th>';
                    modal += '<th class="text-center">Dt_nascimento</th>';
                    modal += '<th class="text-center">Endereço</th>';
                    modal += '<th class="text-center">Id_bairro</th>';
                    modal += '<th class="text-center">Opções</th>';
                    modal += '<tr>';
                    modal += '<tbody>';

                    r.forEach(function(valor, chave) {

                        modal += '<tr>';
                        modal += '<td><b>' + valor['id_responsavel'] + '</b></td>';
                        modal += '<td>' + valor['nome'] + '</td>';
                        modal += '<td class="text-center">' + valor['cpf'] + '</td>';
                        modal += '<td class="text-center">' + valor['rg'] + '</td>';
                        modal += '<td class="text-center">' + valor['dt_nascimento'] + '</td>';
                        modal += '<td class="text-center">' + valor['endereco'] + '</td>';
                        modal += '<td class="text-center">' + valor['id_bairro'] + '</td>';


                        if (valor['duplicidade'] == "S") {
                            modal += '<td class="text-center">';
                            modal += '<button class="btn btn_green bg-green btn-sm ad_duplicidade" ';
                            modal += 'data-id-resp="' + valor['id_responsavel'] + '" ';
                            modal += 'data-id-nome="' + valor['nome'] + '" ';
                            modal += 'data-id-cpf="' + valor['cpf'] + '" ';
                            modal += 'data-id-rg="' + valor['rg'] + '" ';
                            modal += 'data-id-dt_nascimento="' + valor['dt_nascimento'] + '" ';
                            modal += 'data-id-endereco="' + valor['endereco'] + '" ';
                            modal += 'data-id-bairro="' + valor['bairro'] + '" ';
                            modal += '>Adicionar Duplicidade</button>';

                            modal += '</td>';
                        } else {
                            console.log("sem açoes");
                            modal += '<td class="text-center">Sem Ações</td>';
                        }

                        modal += '</tr>';
                    });

                    modal += '</tbody>';
                    modal += '</table>';
                    modal += '</div>';
                    modal += '</div>';
                    modal += '</div>';
                    modal += '</div>';

                    if ($(".modal-backdrop").length > 0) {
                        $(".modal-backdrop").remove();
                    }

                    if ($("#modal_dup_responsavel_animal").length > 0) {
                        $("#modal_dup_responsavel_animal").remove();
                    }

                    $('body').append(modal);
                    $("#modal_dup_responsavel_animal").modal('show');
                    console.log(r);

                    return;
                }

            },
            error: function(r) {
                alert("Erro ao buscar os dados dos responsaveis");

                console.log(r);
            }
        });

    });

    </script>



<!--<script src="../assets/js/jquery.js"></script>-->


<script>


    $(document).on('click', '.ad_duplicidade', function() {

        //var sexo = "Masculino";
        //if ($(this).attr('data-id-sexo') == "F") {
        //sexo = "Feminino";
        //}

        var modal = '<div class="modal" tabindex="-1" role="dialog" id="modal_vincula_responsavel_animal">';
        modal += '<div class="modal-dialog modal-md" role="document">';
        modal += '<div class="modal-content">';
        modal += '<div class="modal-header bg-warning">';
        modal += '<h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger"></i> Adicionar Vinculo? </h5>';
        modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        modal += '<span aria-hidden="true">&times;</span>';
        modal += '</button>';
        modal += '</div>';
        modal += '<div class="modal-body">';
        modal += '<div class="row">';

        modal += '<div class="col-12 text-justify">';
        modal += 'Deseja Adicionar Vinculo para o responsavel abaixo? <br><small>Este procedimento tornará o responsavel <b>ID ' + $(this).attr('data-id-vinc') + '</b> inalterável, possibilitando alterações apenas no novo registro</small>';
        modal += '</div>';

        modal += '<div class="col-12 text-justify">';

        modal += '<div class="row p-2">';

        modal += '<div class="col-4 text-justify py-1 bg-warning-secondary">ID</div>';
        modal += '<div class="col-8 text-right py-1 bg-warning-secondary"><b>' + $(this).attr('data-id-vinc') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1">Nome</div>';
        modal += '<div class="col-8 text-right py-1"><b>' + $(this).attr('data-id-nome') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1">CPF</div>';
        modal += '<div class="col-8 text-right py-1"><b>' + $(this).attr('data-id-cpf') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1 bg-warning-secondary">RG</div>';
        modal += '<div class="col-8 text-right py-1 bg-warning-secondary"><b>' + $(this).attr('data-id-rg') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1">Dt_nascimento</div>';
        modal += '<div class="col-8 text-right py-1"><b>' + $(this).attr('data-id-dt_nascimento') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1 bg-warning-secondary">Endereco</div>';
        modal += '<div class="col-8 text-right py-1 bg-warning-secondary"><b>' + $(this).attr('data-id-endereco') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1 bg-warning-secondary">Id_bairro</div>';
        modal += '<div class="col-8 text-right py-1 bg-warning-secondary"><b>' + $(this).attr('data-id-bairro') + '</b></div>';

        modal += '</div>';

        modal += '</div>';

        modal += '<div class="col-12 text-right">';
        modal += '<button class="btn btn-warning" id="vincular_responsavel" data-id-vinc="' + $(this).attr('data-id-vinc') + '">Vincular</a>';
        modal += '</div>';

        modal += '</div>';

        modal += '</div>';

        modal += '</div>';

        modal += '</div>';

        modal += '</div>';

        if ($(".modal-backdrop").length > 0) {
            $(".modal-backdrop").remove();
        }

        if ($("#modal_vincula_responsavel_animal").length > 0) {
            $("#modal_vincula_responsavel_animal").remove();
        }

        if ($("#modal_valida_dup_responsavel_animal").length > 0) {
            $("#modal_valida_dup_responsavel_animal").remove();
        }

        $('body').append(modal);
        $("#modal_valida").modal('show');

        return;

    });


    $(document).on("click", "#vincular_responsavel", function() {

        var id_responsavel = $(this).attr('data-id-responsavel');

        $.ajax({
            type: "GET",
            url: "../includes/ajax_vincula_responsavel_animal.php",
            data: {
                'id_responsavel_base': id_responsavel

            },

            success: function(r) {

                if (r[0]["status"] == 1) {

                    var modal = '<div class="modal" tabindex="-1" role="dialog" id="modal_responsavel_vinculado">';
                    modal += '<div class="modal-dialog modal-md" role="document">';
                    modal += '<div class="modal-content">';
                    modal += '<div class="modal-header bg-green">';
                    modal += '<h5 class="modal-title"><i class="fas fa-check-circle text-light"></i> Vinculo adicionado com sucesso! </h5>';
                    modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    modal += '<span aria-hidden="true">&times;</span>';
                    modal += '</button>';
                    modal += '</div>';
                    modal += '<div class="modal-body">';
                    modal += '<div class="row">';

                    modal += '<div class="col-12 text-center py-3">';
                    modal += '<h1 class="text-center"><i class="fas fa-check-circle text-green"></i></h1>';
                    modal += '</div>';

                    modal += '<div class="col-12 text-justify">';
                    modal += 'O responsavel de ID <b>' + id_responsavel + '</b> foi vinculado ao ID <b>' + r[0]["id_responsavel_new"] + '</b> sendo este seu novo registro.';
                    modal += '</div>';

                    modal += '<div class="col-12 text-center py-3">';
                    modal += '<a class="btn btn-green bg-green" href="infoResponsavel.php?id_responsavel=' + r[0]["id_responsavel_new"] + '">Acessar responsavel <b>' + r[0]["id_responsavel_new"] + '</b></a>';
                    modal += '</div>';

                    modal += '</div>';

                    modal += '</div>';

                    modal += '</div>';

                    modal += '</div>';

                    modal += '</div>';

                    if ($(".modal-backdrop").length > 0) {
                        $(".modal-backdrop").remove();
                    }

                    if ($("#modal_responsavel_vinculado").length > 0) {
                        $("#modal_responsavel_vinculado").remove();
                    }

                    if ($("#modal_valida_dup_responsavel").length > 0) {
                        $("#modal_valida_dup_responsavel").remove();
                    }

                    
                    $('body').append(modal);
                    $("#modal_responsavel_vinculado").modal('show');
                    console.log(r);

                    return;
                }

            },
            error: function(r) {
                alert("Erro ao vincular os dados do responsavel");
            }
        });
    });
</script>