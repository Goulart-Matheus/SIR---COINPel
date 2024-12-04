<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Aplicações', 'fas fa-file-code', 'APLICACAO_viewDados.php');
$tab->setTab('Nova Aplicação', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);


?>

<link rel="stylesheet" href="../assets/css/multi-select.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" />
<link rel="stylesheet" href="../assets/iconpicker/bootstrap-iconpicker.css" />

<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <div class="card p-0">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if (isset($add)) {
                            include "../class/class.valida.php";

                            $valida_descricao = new Valida($form_descricao, 'Descrição');
                            $valida_descricao->TamMinimo(1);
                            $erro .= $valida_descricao->PegaErros();
                        }

                        if (!$erro && isset($add)) {
                            $query->begin();
                            $form_icone = ($form_icone == 'empty') ? null : $form_icone;
                            $query->insertTupla('aplicacao', array($form_superior, $form_fonte, $form_descricao, $form_tipo, $form_situacao, $form_nova_janela, $form_icone));
                            $ultimo_insert = $query->last_insert[0];
                            if (isset($form_grupo)) {
                                foreach ($form_grupo as $obj) {
                                    $query->insertTupla('grupo_aplicacao', array($obj, $ultimo_insert));
                                }
                            }

                            $query->commit();
                        }
                        if ($erro) echo callException($erro, 2); ?>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="form_superior">Superior</label>
                        <select class="form-control" name="form_superior" id="form_superior">
                            <option> -- Selecione um superior --</option>
                            <?
                            $query->exec("SELECT * FROM aplicacao WHERE tipo='m' ORDER BY descricao");
                            $n = $query->rows();
                            while ($n--) {
                                $query->proximo();
                                echo "<option value='" . $query->record[0] . "'>" . $query->record[3] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="form_descricao">Descrição</label>
                        <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if ($erro) echo $form_descricao; ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="form_icon">Ícone</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas <? if ($edit) echo $form_icone;
                                                    else echo 'fa-plus' ?>" id="icone"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="form_icon" value="<? if ($erro) echo $form_icone; ?>">
                        </div>
                    </div>

                    <div class="form-row col-12">

                        <div class="form-group col-12">
                            <label for="form_fonte">Fonte</label>
                            <input type="text" class="form-control" name="form_fonte" id="form_fonte" value="<? if ($erro) echo $form_fonte; ?>">
                        </div>

                    </div>

                    <div class="form-row col-12">

                        <div class="form-group col-12 col-md-4">
                            <label for="form_tipo">Tipo</label>
                            <select class="form-control" name="form_tipo" id="form_tipo">
                                <option value='a'>Aplicação</option>
                                <option value='m'>Menu</option>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_situacao">Situação</label>
                            <select class="form-control" name="form_situacao" id="form_situacao">
                                <option value="0">Oculto</option>
                                <option value="1" selected>Vísivel</option>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_nova_janela">Nova janela</label>
                            <select class="form-control" name="form_nova_janela" id="form_nova_janela">
                                <option value='N'>Não</option>
                                <option value='S'>Sim</option>
                            </select>
                        </div>

                    </div>


                    <div class="form-group col-12">

                        <label for="form_grupo">Grupo</label>
                        <select class="searchable form-group" multiple="multiple" id="multi-select-group" name="form_grupo[]">
                            <?

                            $query->exec("SELECT codgrupo, descricao FROM grupo ORDER BY descricao");
                            $n = $query->rows();

                            while ($n--) {
                                $query->proximo();
                                echo "<option value='" . $query->record[0] . "'>" . $query->record[1] . "</option>";
                            }

                            ?>
                        </select>

                    </div>


                </div>
            </div>
            <div class="card-footer bg-light-2">
                <?
                $btns = array('clean', 'save');
                include('../includes/dashboard/footer_forms.php');
                ?>
            </div>
    </form>
</section>

<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/jquery.multi-select.js"></script>
<script src="../assets/js/jquery.quicksearch.js"></script>
<script>
    $('input#form_search').quicksearch('#multi-select-group option');

    $('#multi-select-group').multiSelect({

        selectableHeader: "<div></div><input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom:5px'>",
        selectionHeader: "<div></div><input type='text' class='search-input form-control' autocomplete='off' placeholder='Pesquisar' style='margin-bottom:5px'>",

        afterInit: function(ms) {
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e) {
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function() {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function() {
            this.qs1.cache();
            this.qs2.cache();
        },
        selectableOptgroup: true
    });

    /*
        $('#icon_picker').on('change', function(e) {
            $("#form_icon").val(e.icon);
        });
    */
    $('#form_icon').on('keyup', function() {
        if ($(this).val() != "") {
            var newIcon = $(this).val();
            var e = $("#icone");
            e.removeClass().addClass(newIcon);
        }
    });
</script>

<? include_once('../includes/dashboard/footer.php'); ?>