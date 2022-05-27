<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', 'formAplicacao.php');
$tab->setTab('Pesquisar','fas fa-search', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

?>
    <section class="content">
        <form method="get" action="viewAplicacaoDados.php">
            <div class="card p-1">
                <div class="card-header border-bottom-0">
                    <div class="text-center">
                        <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                    </div>
                    <div class="row text-center">
                        <div class="col-12 col-sm-4 offset-sm-4">
                            <?if($erro) echo callException($erro, 2);?>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="form-group">
                        <label for="form_superior">Superior</label>
                        <select class="form-control" name="form_superior" id="form_superior">
                            <option value="">-- Selecione um superior --</option>
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
                    <div class="form-group">
                        <label for="form_descricao">Descrição</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($erro) echo $form_descricao; ?>">
                    </div>
                    <div class="form-group">
                        <label for="form_fonte">Fonte</label>
                        <input type="text" class="form-control" name="form_fonte" value="<? if($erro) echo $form_fonte; ?>">
                    </div>
                    <div class="form-group">
                        <label for="form_tipo">Tipo</label>
                        <select class="form-control" name="form_tipo" id="form_tipo">
                            <option value="">-- Selecione um tipo --</option>
                            <option value='a'>Aplicação</option>
                            <option value='m'>Menu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="form_situacao">Situação</label>
                        <select class="form-control" name="form_situacao" id="form_situacao">
                            <option value="">-- Selecione uma sitiação --</option>
                            <option value="0">Oculto</option>
                            <option value="1">Vísivel</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer border-top-0 bg-transparent">
                    <div class="text-center">
                        <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                        <input class="btn btn-info" type="submit" name="add" value="Buscar">
                    </div>
                </div>
        </form>
    </section>

<? include_once('../includes/dashboard/footer.php'); ?>
