<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar', 'fas fa-search', 'TIPO_CONTATO_view.php');

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

                            $valida = new Valida($form_descricao, 'Descrição');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_mascara, 'Mascara');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            //if ($form_mascara != "") {
                                //$valida = new Valida(limpaCPF_RG($form_mascara), 'mascara');
                                //$valida->TamMinimo(11);
                                //$erro .= $valida->PegaErros();
                            //}

                            //if ($form_cpf != "") {
                            // $valida = new Valida(limpaCPF_CNPJ($form_cpf), 'Cpf');
                            //$valida->TamMinimo(11);
                            // $erro .= $valida->PegaErros();
                            //}
                            //if ($form_cpf != "") {
                            // $valida = new Valida(limpaCPF_CNPJ($form_cpf), 'rg');
                            // $valida->TamMinimo(10);
                            // $erro .= $valida->PegaErros();
                            //}

                            $valida = new Valida($form_habilitado, 'Habilitado');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) 

                            //$form_mascara_unmask = limpaCPF_CNPJ($form_mascara);

                            //$form_mascara_unmask = limpaCPF_($form_mascara);
                            //$form_rg_unmask = limpaCPF_RG($form_rg);

                            $query_aux = new Query($bd);


                            $x = 0;

                            if ($form_mascara != "") {
                                $query_aux->exec("SELECT * FROM tipo_contato WHERE  = mascara'$form_mascara_unmask'");
                                $x = $query_aux->rows();
                            }

                            if ($x > 0) {

                               echo callException("Contato já cadastrado.", 2);
                            } else {

                         
                            $query->begin();

                            $query->insertTupla(
                                'tipo_contato',
                                array(
                                    trim($form_descricao),
                                    $form_mascara,
                                    $_habilitado,
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
                        <label for="form_descricao"><span class="text-danger">*</span> Contato</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" maxlength="100" value="<? if ($erro) echo $form_descricao; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_nome"><span class="text-danger">*</span> Habilitado</label>
                        <select class="form-control" name="form_habilitado">
                            <option value="S" <? if ($erro && $form_habilitado == "S") echo 'selected';
                                                else echo 'selected'; ?>>Sim</option>
                            <option value="N" <? if ($erro && $form_habilitado == "N") ?>>Não</option>
                        </select>
                    </div>

                
                    <div class="form-group col-12 col-md-4">
                        <label for="form_mascara"><span class="text-danger">*</span> Documento</label>
                        <input type="text"class="form-control form_mascara"name="form_mascara"id="form_mascara"maxlength="20"value="<? if ($erro) echo $form_mascara; ?>">
                        <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                        <div class="invalid-feedback">
                            Preencha o campo documento.
                        </div>

                    </div>

                </div>

            </div>

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


<script type="text/javascript">
    
    $('#form_mascara').mask('000.000.000-00', {
        reverse: false
    }).on("keyup", function(e) {

        if ($(this).val().length == 11) {
            $(this).mask('000.000.000-00');
        } else {
            $(this).mask('0000000000');
        }

    });
</script>


<!--<script>

    $('.form_cpf').mask('000.000.000-00');
    
    $('#form_nome,#form_cpf').on('change', function(e) {

        var rg = $('#form_rg').val();
        var cpf = $('#form_cpf').val();

        $.ajax({
            type: "GET",
            url: "../includes/ajax_valida.php",
            data: {
                'rg': rg,
                'cpf': cpf
            },

            function mascara_rg(elemento) {
                $(elemento).mask('0000000000');

            }

            function mascara_cpf(elemento) {
                $(elemento).mask('000.000.000-00');
            }

            $('.salvar').on('click', function(event) {
                if (!submitFormValidad(this)) {
                    event.preventDefault();
                }
                $cpf_unmask = $('.form_cpf').cleanVal();
                $('.form_cpf_unmask').val($cpf_unmask);
            });


            $('.salvar').on('click', function(event) {
                if (!submitFormValidad(this)) {
                    event.preventDefault();
                }
                $cpf_unmask = $('.form_rg').cleanVal();
                $('.form_cpf_unmask').val($cpf_unmask);

            });


        });

    
    }); -->

</script>