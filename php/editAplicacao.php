<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', 'formAplicacao.php');
$tab->setTab('Pesquisar','fas fa-search', 'viewAplicacao.php');
$tab->setTab('Gerenciar','fas fa-cog', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

$query->exec("SELECT * FROM aplicacao WHERE codaplicacao=" . $codaplicacao);
$query->result($query->linha);
?>
<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF'];?>">
        <input type="hidden" name="codaplicacao" value="<?=$codaplicacao?>">
        <div class="card p-1">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if(isset($edit)){
                            include "../class/class.valida.php";

                            $valida_descricao=new Valida($form_descricao,'Descrição');
                            $valida_descricao->TamMinimo(1);
                            $erro.=$valida_descricao->PegaErros();
                        }

                        if (!$erro && isset($edit)) {
                            $query->begin();
                            $itens =array($form_superior, $form_fonte, $form_descricao, $form_tipo, $form_situacao,$form_nova_janela, $form_icone);
                            $where =array(0 => array('codaplicacao', $codaplicacao));
                            $query->updateTupla('aplicacao', $itens, $where);
                            $query->commit();
                        }
                        if($erro) echo callException($erro, 2);?>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="form_superior">Superior</label>
                        <select class="form-control" name="form_superior" id="form_superior">
                            <option> -- Selecione um superior --</option>
                            <?
                            $query_aplicacao =new Query($bd);
                            $query_aplicacao->exec("SELECT * FROM aplicacao WHERE tipo='m' ORDER BY descricao");
                            $n=$query_aplicacao->rows();
                            while ($n--) {
                                $query_aplicacao->proximo();
                                if($erro)
                                    if($form_superior == $query_aplicacao->record[0]) $flag='selected';
                                    else                                              unset($flag);
                                else
                                    if($query->record[1] == $query_aplicacao->record[0]) $flag='selected';
                                    else                                                 unset($flag);
                                echo "<option value='" . $query_aplicacao->record[0] . "' " . $flag . ">" . $query_aplicacao->record[3] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form_descricao">Descrição</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($edit) echo $form_descricao; else echo $query->record[3]; ?>">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="form_fonte">Fonte</label>
                        <input type="text" class="form-control" name="form_fonte" id="form_fonte" value="<? if($edit) echo $form_fonte; else echo $query->record[2]; ?>">
                    </div>
                    <div class="form-group col-md-11">
                        <label for="form_tipo">Tipo</label>
                        <select class="form-control" name="form_tipo" id="form_tipo">
                            <option value='a' <?if($erro){if($form_tipo=='a') echo 'selected';} else if ($query->record[4]=='a') echo selected?>>Aplicação</option>
                            <option value='m' <?if($erro){if($form_tipo=='m') echo 'selected';} else if ($query->record[4]=='m') echo selected?>>Menu</option>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="form_icone">Icone</label><br>
                        <button class="btn btn-outline-secondary icone" name="form_icone" data-icon="<?echo $query->record[7]?>" role="iconpicker" data-selected-class="btn-danger" data-unselected-class="btn-transparent" data-cols="8" data-rows="8"></button>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form_situacao">Situação</label>
                        <select class="form-control" name="form_situacao" id="form_situacao">
                            <option value=0 <?if($erro){if($form_situacao==0) echo 'selected';} else if ($query->record[5]==0) echo selected?>>Oculto</option>
                            <option value=1 <?if($erro){if($form_situacao==1) echo 'selected';} else if ($query->record[5]==1) echo selected?>>Vísivel</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form_nova_janela">Nova janela</label>
                        <select class="form-control" name="form_nova_janela" id="form_nova_janela">
                            <option value='N' <?if($erro){if($form_situacao=='N') echo 'selected';} else if ($query->record[6]=='N') echo selected?>>Não</option>
                            <option value='S' <?if($erro){if($form_situacao=='S') echo 'selected';} else if ($query->record[6]=='S') echo selected?>>Sim</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="form_grupo">Grupo</label>
                        <select multiple class="form-control" name="form_grupo[]" id="form_grupo">
                            <?
                            $query->exec("SELECT codgrupo, descricao FROM grupo ORDER BY descricao");
                            $n=$query->rows();
                            while($n--){
                                $query->proximo();
                                echo"<option selected value='".$query->record[0]."'>".$query->record[1]."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top-0 bg-transparent">
                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info" type="submit" name="edit" value="Salvar">
                </div>
            </div>
    </form>
</section>

<? include_once('../includes/dashboard/footer.php'); ?>
