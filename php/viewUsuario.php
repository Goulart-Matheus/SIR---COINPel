<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', 'formUsuario.php');
$tab->setTab('Pesquisar','fas fa-search', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

?>
    <section class="content">
        <form method="get" action="viewUsuarioDados.php">
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
                        <label for="form_nome">Nome</label>
                        <input type="text" class="form-control" name="form_nome" id="form_nome" value="<? if($erro) echo $form_nome; ?>">
                    </div>

                    <div class="form-group">
                        <label for="form_habilitado">Status</label>
                        <select class="form-control" name="form_habilitado" id="form_habilitado">
                            <option value="">-- Todos --</option>
                            <option value='S'>Habilitados</option>
                            <option value='N'>Desabilitados</option>
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