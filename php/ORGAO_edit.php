<?
    include('../includes/session.php');
    include('../includes/variaveisAmbiente.php');
    include_once('../includes/dashboard/header.php');
    include('../class/class.tab.php');

    $tab = new Tab();

    $tab->setTab('Órgãos'       ,'fas fa-hotel'     , 'ORGAO_viewDados.php');
    $tab->setTab('Novo Órgão'   ,'fas fa-plus'      , 'ORGAO_form.php'   );
    $tab->setTab('Editar'       ,'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

    $tab->printTab($_SERVER['PHP_SELF']);

    $query->exec("SELECT id_orgao , descricao , orgao_gestor , sigla FROM orgao WHERE id_orgao = " . $id_orgao);
    $query->result($query->linha);

?>

    <section class="content">

        <form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">

            <input type="hidden" name="id_orgao" value="<? echo $query->record[0]; ?>">

            <div class="card p-0">

                <div class="card-header border-bottom-1 mb-3 bg-light-2">

                    <div class="text-center">
                        <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                    </div>

                    <div class="row text-center">

                        <div class="col-12 col-sm-4 offset-sm-4">
                            
                            <?
                                if(isset($edit)){

                                    include "../class/class.valida.php";

                                    $valida = new Valida($form_descricao, 'Descrição');
                                    $valida->TamMinimo(1);
                                    $erro .= $valida->PegaErros();

                                    $valida = new Valida($form_sigla, 'Sigla');
                                    $valida->TamMinimo(1);
                                    $erro .= $valida->PegaErros();

                                }

                                if (!$erro && isset($edit)) {

                                    $query->begin();

                                    $itens =array($_id_cliente                  ,
                                                  trim($form_descricao)         , 
                                                  $_user                        , 
                                                  $_ip                          , 
                                                  $_data                        , 
                                                  $_hora                        ,
                                                  $form_gestor                  ,
                                                  strtoupper(trim($form_sigla))
                                                );

                                    $where =array(0 => array('id_orgao', $id_orgao));
                                    $query->updateTupla('orgao', $itens, $where);
                                    
                                    $query->commit();
                                }

                                if($erro) echo callException($erro, 2);

                            ?>

                        </div>

                    </div>

                </div>

                <div class="card-body pt-0">

                    <div class="form-row">

                        <div class="form-group col-12 col-md-3">
                            <label for="form_sigla"><span class="text-danger">*</span> Sigla</label>
                            <input type="text" class="form-control text-uppercase" name="form_sigla" id="form_sigla" maxlength="10" value="<? if($edit) echo trim($form_sigla); else echo trim($query->record[3]); ?>">
                        </div>

                        <div class="form-group col-12 col-md-9">
                            <label for="form_descricao"><span class="text-danger">*</span> Descrição</label>
                            <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($edit) echo trim($form_descricao); else echo trim($query->record[1]); ?>">
                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group col-12">
                            <label for="form_gestor"><span class="text-danger">*</span> Orgão Gestor</label>
                            <select name="form_gestor" id="form_gestor" class="form-control">
                                <option value="S" <? if($edit && $form_gestor == 'S') echo 'selected'; else { if(!$edit && $query->record[2] == "S") {echo 'selected'; } }  ?>>Sim</option>
                                <option value="N" <? if($edit && $form_gestor == 'N') echo 'selected'; else { if(!$edit && $query->record[2] == "N") {echo 'selected'; } }  ?>>Não</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="card-footer bg-light-2">
                    <?
                        $btns = array('clean','edit');
                        include('../includes/dashboard/footer_forms.php');
                    ?>
                </div>

            </div>

        </form>

    </section>

<? 
    include_once('../includes/dashboard/footer.php'); 
?>