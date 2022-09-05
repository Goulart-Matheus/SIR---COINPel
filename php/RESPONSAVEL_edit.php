<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$query->exec("SELECT r.id_responsavel, r.nome , r.cpf, r.rg , r.dt_nascimento, r.endereco , r.id_bairro , rc.id_responsavel_contato
              FROM responsavel as r, responsavel_contato as rc, tipo_contato as t
              WHERE r.id_responsavel = rc.id_responsavel AND
                    t.id_tipo_contato = rc.id_tipo_contato AND
                    r.id_responsavel = " . $id_responsavel);
$query->result($query->linha);
$tab = new Tab();

$tab->setTab('Responsáveis', 'fa-solid fa-user', 'RESPONSAVEL_viewDados.php');
$tab->setTab($query->record[1], '', 'RESPONSAVEL_cover.php?id_responsavel='   . $id_responsavel);
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);




$link = isset($id_animal) && $id_animal != "" ? "?id_animal=$id_animal" : "";

?>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF'] . $link ?>" enctype="multipart/form-data">

        <input type="hidden" name="id_responsavel" value="<? echo $query->record[0]; ?>">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">

                    <div class="col-12 col-sm-4 offset-sm-4">

                        <?
                        if (isset($edit)) {

                            include "../class/class.valida.php";

                            $valida = new Valida($form_responsavel, 'Responsável');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_mascara, 'CPF');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_rg, 'RG');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_rg, 'Data de nascimento');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_endereco, 'Endereço');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($edit)) {

                            $query->begin();

                            $itens = array(
                                $id_responsavel,
                                trim($form_responsavel),
                                $form_mascara, //CPF
                                $form_rg,
                                $form_dt_nascimento,
                                $form_endereco,
                                $form_bairro,
                                $_login,
                                $_ip,
                                $_data,
                                $_hora,

                            );

                            $where = array(0 => array('id_responsavel', $id_responsavel));
                            $query->updateTupla('responsavel', $itens, $where);

                            $responsavel_contato = new Query($bd);
                            $responsavel_contato->exec("SELECT rc.id_responsavel_contato
                                                    FROM responsavel as r, responsavel_contato as rc, tipo_contato as t
                                                    WHERE r.id_responsavel = rc.id_responsavel AND
                                                        t.id_tipo_contato = rc.id_tipo_contato AND
                                                        r.id_responsavel = " . $id_responsavel);
                            $linhas = $responsavel_contato->rows();

                            $linhas_array = [];
                            $linhas_array_atual = [];
                            $linhas_array_deletar = [];
                            while ($linhas--) {
                                $responsavel_contato->proximo();
                                array_push($linhas_array, $responsavel_contato->record[0]);
                            }

                            $i = 0;

                            foreach ($form_valor_contato as $val) {

                                $itens = array(
                                    $id_responsavel,
                                    $form_tipo_contato[$i],
                                    $val,
                                    $form_principal[$i],
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,
                                );


                                if ($id_responsavel_contato[$i]) { // Atualiza os Programas.

                                    if (in_array($id_responsavel_contato[$i], $linhas_array)) {

                                        $where = array(0 => array('id_responsavel_contato', $id_responsavel_contato[$i]));
                                        $query->updateTupla('responsavel_contato', $itens, $where);
                                        array_push($linhas_array_atual, $id_responsavel_contato[$i]);
                                    }
                                } else {
                                    $query->insertTupla('responsavel_contato', $itens);
                                }

                                $i++;
                            }

                            foreach ($linhas_array as $linhas_array_deletar) { // Deleta elementos que não estão na página.
                                if (!in_array($linhas_array_deletar, $linhas_array_atual)) {
                                    $where = array(0 => array('id_responsavel_contato', $linhas_array_deletar));
                                    $query->deleteTupla('responsavel_contato', $where);
                                }
                            }


                            $query->commit();
                        }

                        if ($erro) echo callException($erro, 2);

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="form_responsavel"><span class="text-danger">*</span> Nome :</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="100" value="<? if ($edit) echo trim($form_responsavel);
                                                                                                                                                                    else echo trim($query->record[1]); ?>">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="form_mascara"><span class="text-danger">*</span>CPF: </label>
                        <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($edit) echo trim($form_mascara);
                                                                                                                            else echo trim($query->record[2]); ?>">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="form_rg"><span class="text-danger">*</span> RG :</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_rg" id="form_rg" maxlength="100" value="<? if ($edit) echo trim($form_rg);
                                                                                                                                                else echo trim($query->record[3]); ?>">
                    </div>
                </div>
                <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_dt_nascimento"><span class="text-danger">*</span> Data de nascimento :</label>
                        <input type="date" class="form-control" name="form_dt_nascimento" id="form_dt_nascimento" maxlength="100" value="<? if ($edit) echo trim($form_dt_nascimento);
                                                                                                                                            else echo trim($query->record[4]); ?>">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_endereco"><span class="text-danger">*</span> Endereço :</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_endereco" id="form_endereco" maxlength="100" value="<? if ($edit) echo trim($form_endereco);
                                                                                                                                                            else echo trim($query->record[5]); ?>">
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="form_bairro"><span class="text-danger">*</span> Bairro :</label>
                        <select name="form_bairro" id="form_bairro" class="form-control" required value="<? if ($edit) echo trim($form_bairro);
                                                                                                            else echo trim($query->record[6]); ?>">>
                            <?
                            $form_elemento = $edit ? $form_bairro : $query->record[6];
                            include("../includes/inc_select_bairro.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o bairro.
                        </div>
                    </div>

                </div>


                <div class="form-row ">

                    <div class="form-group col-12 ">

                        <p class="text-center py-2 bg-green">
                            Contatos :
                        </p>

                    </div>

                </div>

                <div class="form-row">


                    <div class="form-group col-12" id="container_dinamico">

                        <?

                        if ($edit) {
                            $qnt = count($form_valor_contato);
                        } else {
                            $programas = new Query($bd);
                            $programas->exec("SELECT rc.id_tipo_contato, rc.valor_contato, rc.principal, rc.id_responsavel_contato
                                              FROM responsavel as r, responsavel_contato as rc, tipo_contato as t
                                              WHERE r.id_responsavel = rc.id_responsavel AND
                                                    t.id_tipo_contato = rc.id_tipo_contato AND
                                                    r.id_responsavel = " . $id_responsavel);
                            $qnt = $programas->rows() == 0 ? 1 : $programas->rows();
                        }

                        for ($c = 0; $c < $qnt; $c++) {
                            $programas->proximo();
                        ?>

                            <div class="input-group ml-0 mb-2" id="campo_dinamico">


                                <select name="form_tipo_contato[]" id="form_tipo_contato" class="form-control form_tipo_contato" required>
                                    <? $form_elemento = $edit ? $form_tipo_contato :  $programas->record[0];
                                    include("../includes/inc_select_tipo_contato.php"); ?>

                                    <input type="hidden" name="id_responsavel_contato[]" id="id_responsavel_contato" value="<? echo $programas->record[3]; ?>" />

                                    <input type="text" name="form_valor_contato[]" id="form_valor_contato" class="form-control col-md-7" placeholder="Contato" value="<? if ($edit) echo $form_valor_contato[$c];
                                                                                                                                                                        else echo $programas->record[1] ?>" />
                                    <select name="form_principal[]" required id="form_principal" class="form-control col-md-1 form_principal" value="<? if ($edit) echo $form_principal[$c];
                                                                                                                                                        else echo $programas->record[2]; ?>">
                                        <option value="S" <? if ($edit && $form_principal == 'S') echo 'selected';
                                                            else {
                                                                if (!$edit && $programas->record[2] == "S") {
                                                                    echo 'selected';
                                                                }
                                                            }  ?>>Sim</option>
                                        <option value="N" <? if ($edit && $form_principal == 'N') echo 'selected';
                                                            else {
                                                                if (!$edit && $programas->record[2] == "N") {
                                                                    echo 'selected';
                                                                }
                                                            }  ?>>Não</option>
                                    </select>

                                    <div class="input-group-append">

                                        <? if ($c == $qnt - 1) { ?>

                                            <a class="btn btn-success " id="novo_campo_dinamico" href="#form_principal">+</a>

                                        <? } else { ?>

                                            <a class="btn btn-danger" id="remove_campo_dinamico" href="#form_principal">x</a>

                                        <? } ?>

                                    </div>

                            </div>

                        <?

                        }

                        ?>

                    </div>

                </div>

                <div class="card-footer bg-light-2">
                    <?
                    $btns = array('reload', 'edit');
                    include('../includes/dashboard/footer_forms.php');
                    ?>
                </div>

            </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
?>
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/jquery.mask.js"></script>
<script type="text/javascript">
    $('#form_mascara').mask('000.000.000-00');
    $('#form_rg').mask('00000000000000');
    $(document).on('change', '.form_tipo_contato', function() {
        var mascara = $(this).find(':selected').data('mascara');
        if (mascara == 'email') {
            $(this).parents('#campo_dinamico').find('.form_valor_contato').attr('type', 'email');
        } else {
            $(this).parents('#campo_dinamico').find('.form_valor_contato').attr('type', 'text').mask(mascara);
        }
    });
</script>