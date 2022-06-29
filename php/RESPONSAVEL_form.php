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

                            // $valida = new Valida($form_valor_contato, 'Contato');
                            // $valida->TamMinimo(1);
                            // $erro .= $valida->PegaErros();

                            foreach ($form_valor_contato as $c => $val) {
                                $valida = new Valida($form_valor_contato[$c], 'Contato');
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
                            
                                                      
                            foreach($form_valor_contato as $c =>$val){
                            $query->insertTupla(
                                'responsavel_contato',
                                array(
                                    $id_responsavel,
                                    $form_tipo_contato,
                                    $val,
                                    $form_principal, 
                                    $_login,
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

                        <p class="text-center py-2 bg-dark">
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
                            $qnt = count($form_valor_contato);
                        }

                        for ($c = 0; $c < $qnt; $c++) {

                        ?>

                            <div class="input-group ml-0 mb-2" id="campo_dinamico">

                           
                                <select name="form_tipo_contato[]" id="form_tipo_contato" class="form-control form_tipo_contato" required>
                                    <? $form_elemento = $erro ? $form_tipo_contato : ""; include("../includes/inc_select_tipo_contato.php"); ?>
                                </select>
                              
                                <input type="text"  name="form_valor_contato[]" id="form_valor_contato"  class="form-control col-md-7 form_valor_contato" placeholder="Contato" value="<? if ($erro) echo $form_valor_contato[$c]; ?>"" /> 

                                <input type="text" disabled="" class="form-control col-md-1 text-center" placeholder="Principal"/>
                                <select name="form_principal" id="form_principal" class="form-control col-md-1">
                                    <option value="S">Sim</option>
                                    <option value="N">Não</option>
                                </select>

                                <div class="input-group-append">

                                    <? if ($c == $qnt - 1) { ?>

                                        <a class="btn btn-success " id="novo_campo_dinamico" href="#id_mult">+</a>

                                    <? } else { ?>

                                        <a class="btn btn-danger" id="remove_campo_dinamico" href="#id_mult">x</a>

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


