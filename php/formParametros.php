<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Gerenciar','fas fa-cog', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

$query->exec("SELECT * FROM parametros WHERE id_parametros=1");
$query->result($query->linha);
?>
    <section class="content">
        <form method="post" action="<? echo $_SERVER['PHP_SELF'];?>">
            <input type="hidden" name="login" value="<?=$login?>">
            <div class="card p-1">
                <div class="card-header border-bottom-0">
                    <div class="text-center">
                        <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                    </div>
                    <div class="row text-center">
                        <div class="col-12 col-sm-4 offset-sm-4">
                            <?
                            if(isset($edit)){

                                if ($form_ckTimeout != "checked") {
                                    $form_timeout = 20000000000;
                                }
                                $itens = array($form_interna,$form_externa,$form_titulo,'ckecked','4320','2020-09-03','12:12');
                                $where = array(0 => array('id_parametros','1'));
                                $query->updateTupla('parametros',$itens,$where);
                                $query->commit();
                            }
                            if($erro) echo callException($erro, 2);?>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="form-group">
                        <label for="form_interna">Mensagem Interna</label>
                        <input type="text" class="form-control" name="form_interna" id="form_interna" value="<? if($edit) echo $form_interna; else echo $query->record['mensagem_interna']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="form_externa">Mensagem Externa</label>
                        <input type="text" class="form-control" name="form_externa" id="form_externa" value="<? if($edit) echo $form_externa; else echo $query->record['mensagem_externa']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="form_titulo">Nome sistema</label>
                        <input type="text" class="form-control" name="form_titulo" id="form_titulo" value="<? if($edit) echo $form_titulo; else echo $query->record['nome_sistema']; ?>">
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
