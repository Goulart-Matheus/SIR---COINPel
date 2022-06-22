<?

include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', 'ANIMAL_form.php');
$tab->setTab('Pesquisar', 'fas fa-search', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

?>

<section class="content">

    <form method="post" action="ANIMAL_viewDados.php">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <? if ($erro) echo callException($erro, 2); ?>
                    </div>
                </div>

            </div>

            <div class="card-body pt-0">

                <div class="card-body pt-0">

                    <div class="form-row">

                        <div class="form-group col-12 col-md-4">
                            <label for="form_numero_ficha"></span> Nunero Ficha</label>
                            <input type="text" class="form-control" name="form_numero_ficha" id="form_numero_ficha" maxlength="100" value="<? if ($erro) echo $form_numero_ficha; ?>">
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_numero_chip"></span> Nunero Chip</label>
                            <input type="text" class="form-control" name="form_numero_chip" id="form_numero_chip" maxlength="100" value="<? if ($erro) echo $form_numero_chip; ?>">
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_sexo"></span> Sexo</label>
                            <select name="form_sexo" id="form_sexo" class="form-control">
                                <option value="">Selecione o sexo:</option>
                                <option value="M">Macho</option>
                                <option value="F">Fêmea</option>
                            </select>
                            <div class="invalid-feedback">
                                Escolha o sexo do animal.
                            </div>
                        </div>


                        <div class="form-group col-12 col-md-6">
                            <label for="form_pelagem"></span>Pelagem</label>
                            <select name="form_pelagem" id="form_pelagem" class="form-control" >
                                <?
                                $form_elemento = $erro ? $form_pelagem : "";
                                include("../includes/inc_select_pelagem.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha a Pelagem
                            </div>
                        </div>


                        <div class="form-group col-12 col-md-6">
                            <label for="form_especie"></span>Espécie</label>
                            <select name="form_especie" id="form_especie" class="form-control">
                                <?
                                $form_elemento = $erro ? $form_especie : "";
                                include("../includes/inc_select_especie.php"); ?>
                            </select>
                            <div class="invalid-feedback">
                                Escolha Especie
                            </div>
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