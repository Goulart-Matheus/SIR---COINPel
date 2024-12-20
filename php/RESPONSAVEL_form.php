<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();
$tab->setTab('Responsáveis', 'fas fa-user', 'RESPONSAVEL_viewDados.php');
$tab->setTab('Novo Responsável', 'fas fa-plus', $_SERVER['PHP_SELF']);


$tab->printTab($_SERVER['PHP_SELF']);
$link = isset($id_animal) && $id_animal != "" ? "?id_animal=$id_animal" : "";
?>
<section class="content">

    <form method="post" id="form.cadresponsavel" action="<? echo $_SERVER['PHP_SELF'] . $link ?>" enctype="multipart/form-data">


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


                            // $valida = new Valida($form_responsavel, 'Responsável');
                            // $valida->TamMinimo(1);
                            // $erro .= $valida->PegaErros();


                            // $valida = new Valida($form_endereco, 'Endereço');
                            // $valida->TamMinimo(1);
                            // $erro .= $valida->PegaErros();

                           
                            // Validação testa se o CPF e o RG já estão cadastrados no BD
                            // inicio

                            $query_aux->exec("SELECT id_responsavel, cpf
                                                        FROM responsavel
                                                        WHERE cpf = '$form_mascara'
                                                        
                                                    ");


                            if ($query_aux->rows() > 0 && $query_aux->record[1] != '') {
                                $erro .= "Já existe CPF cadastrado com este numero: $form_mascara";
                                $erro .= "CPF de numero $form_mascara, já esta cadastrado no sistema <br>";
                            }

                            $query_aux1->exec("SELECT id_responsavel, nome, rg
                                              FROM responsavel
                                              WHERE rg = '$form_rg'
                                ");
                            $nome1 = $query_aux1->last_insert[1];
                            if ($query_aux1->rows() > 0 && $query_aux1->record[2] != '') {
                                $erro .= "Já existe RG cadastrado com este numero: $form_rg";
                                $erro .= "RG de numero $form_rg, já esta cadastrado no sistema ";
                            }
                            //fim        
                        }


                        if (!$erro && isset($add)) {

                            if($form_dt_nascimento == ''){
                                $form_dt_nascimento = 'NULL';
                            }

                            if($form_bairro == ''){
                                $form_bairro = 'NULL';
                            }

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

                            

                            if($form_valor_contato[0] != ""){

                                $id_responsavel = $query->last_insert[0];
                                $i = 0;
                                foreach ($form_valor_contato as $val) {
                                    
                                    $query->insertTupla(
                                        'responsavel_contato',
                                        array(
                                            $id_responsavel,
                                            $form_tipo_contato[$i],
                                            $val,
                                            $form_principal[$i],
                                            $auth->getUser(),
                                            $_ip,
                                            $_data,
                                            $_hora,

                                        )

                                    );
                                    $i++;
                            }


                                if ($id_animal != "") {
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
                        <input type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="200" required value="<? if ($erro) echo $form_responsavel; ?>">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="form_mascara">CPF: </label>
                        <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($erro) echo $form_mascara; ?>">
                        <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="form_rg">RG :</label>
                        <input autocomplete="off" type="text" class="form-control" name="form_rg" id="form_rg" maxlength="14" value="<? if ($erro) echo $form_rg; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12 col-md-2">
                        <label for="form_dt_nascimento"> Data de nascimento :</label>
                        <input type="date" class="form-control" name="form_dt_nascimento" id="form_dt_nascimento" maxlength="08" value="<? if ($erro) echo $form_dt_nascimento; ?>">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_endereco"> Endereço :</label>
                        <input type="text" class="form-control" name="form_endereco" id="form_endereco" maxlength="200" value="<? if ($erro) echo $form_endereco; ?>">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_bairro"> Bairro :</label>
                        <select name="form_bairro" id="form_bairro" class="form-control">
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

                        <p class="text-center py-2 bg-green">
                            Contatos :
                        </p>

                    </div>

                </div>

                <div class="form-row">

                    <label for="form_contato">  Contatos:</label>
                    <div class="form-group col-12" id="container_dinamico">

                        <?
                        $qnt = 1;

                        if ($erro) {
                            $qnt = count($form_valor_contato);
                        }

                        for ($c = 0; $c < $qnt; $c++) {

                        ?>

                            <div class="input-group ml-0 mb-2" id="campo_dinamico">


                                <select name="form_tipo_contato[]" id="form_tipo_contato" class="form-control form_tipo_contato">
                                    <? $form_elemento = $erro ? $form_tipo_contato :  include("../includes/inc_select_tipo_contato.php"); ?>
                                </select>

                                <input type="text" name="form_valor_contato[]" id="form_valor_contato" class="form-control col-md-7 form_valor_contato" placeholder="Contato" value="<? if ($erro) echo $form_valor_contato[$c]; ?>" />
                                <input type="text" disabled="" class="form-control col-md-1 text-center" placeholder="Principal" />
                                <select name="form_principal[]" id="form_principal"  class="form-control col-md-1 form_principal">
                                    <option value="" selected>Selecione</option>
                                    <option value="S">Sim</option>
                                    <option value="N">Não</option>
                                </select>

                                <div class="input-group-append">

                                    <? if ($c == $qnt - 1) { ?>

                                        <a class="btn btn-green " id="novo_campo_dinamico" href="#form_principal">+</a>

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
    $('#form_mascara').mask('000.000.000-00');
    $('#form_rg').mask('00000000000000');
    $(document).on('change', '.form_tipo_contato', function() {
        var mascara = $(this).find(':selected').data('mascara');
        if (mascara == 'email') {
            $(this).parents('#campo_dinamico').find('.form_valor_contato').attr('type', 'email');
        } else {
            $(this).parents('#campo_dinamico').find('.form_valor_contato').attr('type', 'text').mask(mascara);
        }
    });

    $("#modal_add_responsavel").on('click', function() {


        var nome_responsavel = $("#form_nome_responsavel").val();


        $.ajax({
            type: "post",
            url: "../includes/ajax_add_responsavel.php",


            data: {
                "nome": nome,

            },
            dataType: "json",
            beforeSend: function() {

                $("#modal_add_responsavel").modal('hide');
            },
            success: function(response) {

                var option = "<option value='" + response['id_responsavel'] + "' selected>" + response['nome'] + "</option>";

                $("#RESPONSAVEL_form").append(option)

            },
            error: function(response) {

            }
        });

    });
</script>