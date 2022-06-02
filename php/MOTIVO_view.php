<?

    include('../includes/session.php');
    include_once('../includes/dashboard/header.php');
    include('../class/class.tab.php');

    $tab = new Tab();

    $tab->setTab('Adicionar','fas fa-plus'  , 'MOTIVO_form.php');
    $tab->setTab('Pesquisar','fas fa-search', $_SERVER['PHP_SELF']);

    $tab->printTab($_SERVER['PHP_SELF']);

?>

    <section class="content">

        <form method="post" action="MOTIVO_viewDados.php">

            <div class="card p-0">

                <div class="card-header border-bottom-1 mb-3 bg-light-2">
                    
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
                            <label for="form_descricao"> Solicitante</label>

                            <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($erro) echo $form_descricao; ?>">
                        </div>

                    </div>

                </div>

                <div class="card-footer border-top-0 bg-transparent">

                    <div class="text-center">
                        <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                        <input class="btn btn-info" type="submit" name="add" value="Buscar">
                    </div>

                </div>

            </div>

        </form>

    </section>

<? 
    include_once('../includes/dashboard/footer.php'); 
?>