<?
    include('../includes/session.php');
    include('../includes/variaveisAmbiente.php');
    include_once('../includes/dashboard/header.php');
    include('../class/class.tab.php');

    $tab = new Tab();

    $tab->setTab('Região Administrativa','fas fa-file-code'   , 'REGIAO_ADMINISTRATIVA_viewDados.php');
    $tab->setTab('Editar','fas fa-pencil-alt'   , $_SERVER['PHP_SELF']);

    $tab->printTab($_SERVER['PHP_SELF']);

    $query->exec("SELECT id_regiao_administrativa , nome , habilitado  FROM denuncias.regiao_administrativa WHERE id_regiao_administrativa = " . $id_regiao_administrativa);
    $query->result($query->linha);
    

?>

    <link href="../assets/css/multi-select.css" rel="stylesheet" type="text/css">   

    <section class="content">

        <form method="post" action="<? echo $_SERVER['PHP_SELF'] ?>">

            <input type="hidden" name="id_regiao_administrativa" id="id_regiao_administrativa" value="<? echo $query->record[0]; ?>">

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

                                    $valida = new Valida($form_nome, 'Nome');
                                    $valida->TamMinimo(1);
                                    $erro .= $valida->PegaErros();
                                  
                                    $valida = new Valida($form_habilitado, 'Habilitado');
                                    $valida->TamMinimo(1);
                                    $erro .= $valida->PegaErros();

                                   

                                }

                                if (!$erro && isset($edit)) {

                                    $query->begin();

                                    $itens =array(trim($form_nome)              ,                                                 
                                                  $_user                        , 
                                                  $_ip                          , 
                                                  $_data                        , 
                                                  $_hora                        ,
                                                  trim($form_habilitado)        ,                                                
                                                         
                                                );

                                    $where =array(0 => array('id_regiao_administrativa', $id_regiao_administrativa));
                                    $query->updateTupla('denuncias.regiao_administrativa', $itens, $where);                                   
                                    
                                    $query->commit();
                                }

                                if($erro) echo callException($erro, 2);

                            ?>

                        </div>

                    </div>

                </div>

                <div class="card-body pt-0">

                    <div class="form-row">
                        
                        <div class="form-group col-12 col-md-6">
                            <label for="form_nome">Nome</label>
                            <input type="text" class="form-control" name="form_nome" id="form_nome" value="<? if($edit) echo trim($form_nome); else echo trim($query->record[1]); ?>">
                        </div>  
                        
                        <div class="form-group col-12 col-md-6">
                                <label for="form_habilitado"><span class="text-danger">*</span> Habilitado</label>
                                <select name="form_habilitado" id="form_habilitado" class="form-control">
                                    <option value="S" <? if($edit && $form_habilitado == 'S') echo 'selected' ?>>Sim</option>
                                    <option value="N" <? if($edit && $form_habilitado == 'N') echo 'selected';?>>Não</option>
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