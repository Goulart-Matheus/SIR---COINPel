<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', 'formPropriedade.php');
$tab->setTab('Pesquisar','fas fa-search', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);
?>

<section class="content">
    <form method="get" action="viewPropriedadeDados.php">
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
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="form_proprietario" class="mb-0 mt-1">Proprietário</label>
                        <select name="form_proprietario" class="form-control">
                            <option value="">Selecione um proprietário...</option>
                            <?
                                $query_proprietario = new Query($bd);
                                $query_proprietario->exec(
                                    "SELECT id_proprietario, nome FROM proprietario ORDER BY nome");
                                $n=$query_proprietario->rows();
                                while ($n--) {
                                    $query_proprietario->proximo();
                                    if($erro)
                                    if($form_proprietario == $query_proprietario->record[0]) $flag='selected';
                                    else unset($flag);
                                    echo "<option value='{$query_proprietario->record[0]}' $flag > {$query_proprietario->record[1]} </option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="form_nome">Nome da propriedade</label>
                        <input autocomplete="off" type="text" class="form-control" name="form_nome" id="form_nome">
                    </div>
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
