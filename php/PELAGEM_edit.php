<?
    include('../includes/session.php');
    include('../includes/variaveisAmbiente.php');
    include_once('../includes/dashboard/header.php');
    include('../class/class.tab.php');

    $tab = new Tab();

    $tab->setTab('Adicionar','fas fa-plus'      , 'PELAGEM_form.php'     );
    $tab->setTab('Pesquisar','fas fa-search'    , 'PELAGEM_view.php'     );
    $tab->setTab('Editar'   ,'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

    $tab->printTab($_SERVER['PHP_SELF']);

    $query->exec("SELECT id_pelagem , descricao  FROM pelagem WHERE id_pelagem = " . $id_pelagem);
    $query->result($query->linha);

?>

    <section class="content">

        <form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">

            <input type="hidden" name="id_pelagem" value="<? echo $query->record[0]; ?>">

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

                                }

                                if (!$erro && isset($edit)) {

                                    $query->begin();

                                    $itens =array($_id_pelagem                  ,
                                                  trim($form_descricao)         , 
                                                  $_habilitado,
                                                  $_login,
                                                  $_ip,
                                                  $_data,
                                                  $_hora,
                                                  
                                                );

                                    $where =array(0 => array('id_pelagem', $id_pelagem));
                                    $query->updateTupla('pelagem', $itens, $where);
                                    
                                    $query->commit();
                                }

                                if($erro) echo callException($erro, 2);

                            ?>

                        </div>

                    </div>

                </div>

                <div class="card-body pt-0">

                    <div class="form-row">

                        
                        <div class="form-group col-12 col-md-9">
                            <label for="form_descricao"><span class="text-danger">*</span> Descrição</label>
                            <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($edit) echo trim($form_descricao); else echo trim($query->record[1]); ?>">
                        </div>

                    </div>

                    <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_nome"><span class="text-danger">*</span> Habilitado</label>
                        <select class="form-control" name="form_habilitado">
                           <option value= "S" <? if ($erro && $form_habilitado == "S") echo 'selected'; else echo 'selected'; ?>>Sim</option>
                           <option value= "N"  <? if ($erro && $form_habilitado == "N") echo 'selected';                        ?>>Não</option> 
                        </select>
                    </div>
                       

                </div>

                <div class="card-footer border-top-0 bg-transparent">

                    <div class="text-center">
                        <input class="btn btn-secondary" type="reset"  name="clear" value="Limpar">
                        <input class="btn btn-info"      type="submit" name="edit"  value="Salvar">
                    </div>

                </div>

            </div>

        </form>

    </section>

<? 
    include_once('../includes/dashboard/footer.php'); 
?>