<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', 'formGrupo.php');
$tab->setTab('Pesquisar','fas fa-search', 'viewGrupo.php');
$tab->setTab('Gerenciar','fas fa-cog', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

$query->exec("SELECT * FROM grupo WHERE codgrupo=" . $codgrupo);
$query->result($query->linha);
?>
<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF'];?>">
        <input type="hidden" name="codgrupo" value="<?=$codgrupo?>">
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

                            $valores=substr($form_aplicacao[0],0,-1);
                            $valida_list=new Valida($form_aplicacao[0], 'Aplicação');
                            $valida_list->TamMinimo(1);
                            $erro.=$valida_list->PegaErros();

                        }

                        if (!$erro && isset($edit)) {
                            $query->begin();

                            $query->updateTupla('grupo', [$form_descricao], [['codgrupo', $codgrupo]]);
                            $query->deleteTupla('grupo_aplicacao', [['codgrupo', $codgrupo]]);

                            foreach(isset($form_aplicacao) ? $form_aplicacao : [] as $codaplicacao){
                                $query->insertTuplaFast(
                                    'grupo_aplicacao',
                                    'codgrupo, codaplicacao',
                                    [$codgrupo,  $codaplicacao]
                                );
                            }

                            $query->commit();
                        }
                        if($erro) echo callException($erro, 2);?>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="form-group">
                    <label for="form_descricao">Descrição</label>
                    <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($edit) echo $form_descricao; else echo $query->record['descricao']; ?>">
                </div>
                <div class="form-group">
                    <label for="form_aplicacao">Aplicação</label>
                    <select multiple class="form-control" name="form_aplicacao[]" id="form_aplicacao">
                        <?
                        $query->exec("SELECT codaplicacao, descricao FROM aplicacao ORDER BY descricao");
                        $n=$query->rows();
                        while($n--){
                            $query->proximo();
                            echo"<option selected value='".$query->record[0]."'>".$query->record[1]."</option>";
                        }
                        ?>
                    </select>
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