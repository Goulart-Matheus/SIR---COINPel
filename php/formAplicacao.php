<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar','fas fa-search', 'viewAplicacao.php');
$tab->printTab($_SERVER['PHP_SELF']);
?>


<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <div class="card p-1">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if(isset($add)){
                            include "../class/class.valida.php";

                            $valida_descricao=new Valida($form_descricao,'Descrição');
                            $valida_descricao->TamMinimo(1);
                            $erro.=$valida_descricao->PegaErros();
                        }

                        if (!$erro && isset($add)) {
                            $query->begin();
                            $form_icone = ($form_icone == 'empty') ? null : $form_icone;
                            $query->insertTupla('aplicacao', array($form_superior, $form_fonte, $form_descricao, $form_tipo, $form_situacao,$form_nova_janela, $form_icone));
                            $ultimo_insert=$query->last_insert[0];
                            if(isset($form_grupo)){
                                foreach($form_grupo as $obj){
                                    $query->insertTupla('grupo_aplicacao', array($obj,$ultimo_insert));
                                }
                            }

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
                            $query->exec("SELECT * FROM aplicacao WHERE tipo='m' ORDER BY descricao");
                            $n=$query->rows();
                            while ($n--) {
                                $query->proximo();
                                echo "<option value='" . $query->record[0] . "'>" . $query->record[3] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form_descricao">Descrição</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($erro) echo $form_descricao; ?>">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="form_fonte">Fonte</label>
                        <input type="text" class="form-control" name="form_fonte" id="form_fonte" value="<? if($erro) echo $form_fonte; ?>">
                    </div>
                    <div class="form-group col-md-11">
                        <label for="form_tipo">Tipo</label>
                        <select class="form-control" name="form_tipo" id="form_tipo">
                            <option value='a'>Aplicação</option>
                            <option value='m'>Menu</option>
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="form_icone">Icone</label><br>
                        <button class="btn btn-outline-secondary icone" name="form_icone" role="iconpicker" data-selected-class="btn-danger" data-unselected-class="btn-transparent" data-cols="8" data-rows="8"></button>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form_situacao">Situação</label>
                        <select class="form-control" name="form_situacao" id="form_situacao">
                            <option value="0">Oculto</option>
                            <option value="1" selected>Vísivel</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form_nova_janela">Nova janela</label>
                        <select class="form-control" name="form_nova_janela" id="form_nova_janela">
                            <option value='N'>Não</option>
                            <option value='S'>Sim</option>
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
                                echo"<option value='".$query->record[0]."'>".$query->record[1]."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top-0 bg-transparent">
                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info" type="submit" name="add" value="Salvar">
                </div>
            </div>
    </form>
</section>

<? include_once('../includes/dashboard/footer.php'); ?>
