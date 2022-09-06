<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Propriedades', 'fas fa-house', 'PROPRIEDADE_viewDados.php');
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);
?>

<? require "../includes/mapbox.header.php"; ?>

<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="id_propriedade" value="<?= $id_propriedade ?>">
        <div class="card p-1">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if (isset($edit)) {
                            include "../class/class.valida.php";

                            $valida_nome = new Valida($form_nome, 'Nome');
                            $valida_nome->TamMinimo(2);
                            $erro .= $valida_nome->PegaErros();
                        }

                        if (!$erro && isset($edit)) {
                            $form_bovino = $form_bovino ?: 'N';
                            $form_equino = $form_equino ?: 'N';
                            $form_bubalino = $form_bubalino ?: 'N';

                            $query->begin();
                            $itens = array(
                                $form_proprietario,
                                $form_nome,
                                $form_coordenada,
                                $form_bovino,
                                $form_equino,
                                $form_bubalino,
                                $form_area,
                                $_login, $_ip, $_datahora,
                            );
                            $where = array(0 => array('id_propriedade', $id_propriedade));
                            $query->updateTupla('propriedade', $itens, $where);
                            $query->commit();
                        }

                        if ($erro) echo callException($erro, 2);
                        ?>
                    </div>
                </div>
            </div>

            <?
            $query->exec("SELECT * from propriedade WHERE id_propriedade = $id_propriedade");
            $query->result($query->linha);

            // e.g: "(-31,-52)" => "-31,-52".
            $coordenada = substr($query->record['coordenadas'], 1, -1);
            // e.g: "-31,-52" => "-31, -52".
            $coordenada = implode(', ', explode(',', $coordenada));

            $zoom = 9;
            ?>

            <div class="card-body pt-0">
                <div class="form-row">
                    <div class="form-group col-6">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="form_proprietario" class="mb-0 mt-1">Proprietário</label>

                                <select name="form_proprietario" class="form-control select2_proprietario">
                                    <?
                                    $where = $form_elemento = $edit ? $form_proprietario : $query->record['id_proprietario'];
                                    include "../includes/inc_select_proprietario.php";
                                    ?>
                                </select>


                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="form_coordenada" class="mb-0 mt-1">Coordenada</label>
                                <input autocomplete="off" type="text" class="form-control" id="form_coordenada" name="form_coordenada" value="<?= $coordenada ?>" maxlength="100">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="form_nome" class="mb-0 mt-1">Nome da propriedade</label>

                                <input autocomplete="off" type="text" class="form-control" id="form_nome" name="form_nome" maxlength="50" value="<?= $query->record['nome'] ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="form_area">Área (em Hectares)</label>
                                <input autocomplete="off" type="number" class="form-control" id="form_area" name="form_area" maxlength="10" step="0.01" value="<?= $query->record['area'] ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="form-check form-check-inline col-sm-3">
                                    <input type="checkbox" class="form-check-input" name="form_bovino" value="S" <? if ($query->record['bovinos'] == 'S') echo "checked"; ?>>
                                    <label for="form_bovino">Bovino</label>
                                </div>
                                <div class="form-check form-check-inline col-sm-3">
                                    <input type="checkbox" class="form-check-input" name="form_equino" value="S" <? if ($query->record['equinos'] == 'S') echo "checked"; ?>>
                                    <label for="form_equino">Equino</label>
                                </div>
                                <div class="form-check form-check-inline col-sm-3">
                                    <input type="checkbox" class="form-check-input" name="form_bubalino" value="S" <? if ($query->record['bubalinos'] == 'S') echo "checked"; ?>>
                                    <label for="form_bubalino">Bubalino</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <div id='map' style='min-height: 300px; height: 100%' meta="marker"></div>
                    </div>
                </div>
            </div>

            <div class="card-footer border-top-0 bg-transparent">

                <div class="card-footer bg-light-2">
                    <?
                    $btns = array('reload', 'edit');
                    include('../includes/dashboard/footer_forms.php');
                    ?>
                </div>

            </div>
        </div>
    </form>
</section>

<?
include_once('../includes/dashboard/footer.php');

require "../includes/mapbox.footer.php";
?>
<script>
    $(document).ready(function() {

        if ($(".select2_proprietario").length > 0) {
            $(".select2_proprietario").attr('data-live-search', 'true');

            $(".select2_proprietario").select2({
                width: '100%'
            });
        }
    });
</script>