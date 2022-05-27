<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar','fas fa-search', 'viewGrupo.php');
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

                            $valores=substr($form_aplicacao[0],0,-1);
                            $valida_list=new Valida($form_aplicacao[0], 'Aplicação');
                            $valida_list->TamMinimo(1);
                            $erro.=$valida_list->PegaErros();


                        }

                        if (!$erro && isset($add)) {
                            $query->begin();
                            $query->insertTupla('grupo', array($form_descricao));
                            $codgrupo=   $query->last_insert[0];
                            //insere grupo nas aplicacoes correspondentes
                            $valores=explode(',', $valores);

                            if(isset($form_aplicacao)){
                                foreach($form_aplicacao as $obj){
                                    $query->insertTupla('grupo_aplicacao', array($codgrupo,$obj));
                                }
                            }

                            $query->commit();
                            $query->exec("select distinct a.superior,b.descricao
				 from grupo_aplicacao ga,aplicacao a,aplicacao b
				 where ga.codaplicacao=a.codaplicacao and
				       a.superior=b.codaplicacao and codgrupo=$codgrupo and
				       a.superior not in (select codaplicacao from grupo_aplicacao where codgrupo=$codgrupo)");
                            $n=$query->rows();

                            if ($n>0) $erro.="Faltam as seguintes superiores:<br>";
                            while ($n--) {
                                $query->proximo();
                                $erro.="->".$query->record[1]."<br>";
                            }
                        }
                        if($erro) echo callException($erro, 2);?>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">

                <div class="form-group">
                    <label for="form_descricao">Descrição</label>
                    <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($erro) echo $form_descricao; ?>">
                </div>
                <div class="form-group">
                    <label for="form_aplicacao">Aplicação</label>
                    <select multiple class="form-control" name="form_aplicacao[]" id="form_aplicacao">
                        <?
                        $query->exec("SELECT codaplicacao, descricao FROM aplicacao ORDER BY descricao");
                        $n=$query->rows();
                        while($n--){
                            $query->proximo();
                            echo"<option value='".$query->record[0]."'>".$query->record[1]."</option>";
                        }
                        ?>
                    </select>
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
