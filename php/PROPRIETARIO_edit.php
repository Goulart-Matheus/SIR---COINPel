<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();

$tab->setTab('Proprietários', 'fas fa-user-circle', 'PROPRIETARIO_viewDados.php');
$tab->setTab('Editar', 'fas fa-pencil-alt', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);
?>

<style>
    .marca {
        display: block;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: 325px;
        object-fit: contain;
    }
</style>

<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <input type="hidden" name="id_proprietario" value="<?= $id_proprietario ?>">
        <div class="card p-1">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4>
                        <?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?>
                        <a href="certificadoProprietario.php?id_proprietario=<?= $id_proprietario ?>" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                    </h4>
                </div>

                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if (isset($edit)) {
                            include "../class/class.valida.php";

                            $valida_nome = new Valida($form_nome, 'Nome');
                            $valida_nome->TamMinimo(2);
                            $erro .= $valida_nome->PegaErros();

                            $valida_cpf = new Valida($form_cpf, 'CPF');
                            $valida_cpf->checacpf();
                            $erro .= $valida_cpf->PegaErros();

                            $valida_inscricao_estadual = new Valida($form_inscricao_estadual, 'Inscrição Estadual');
                            $valida_inscricao_estadual->ENumerico();
                            $erro .= $valida_inscricao_estadual->PegaErros();

                            $has_uploaded_image =
                                !empty($_FILES["form_imagem"]["tmp_name"]) &&
                                is_uploaded_file($_FILES["form_imagem"]["tmp_name"]);
                            if ($has_uploaded_image && !$erro) {
                                $diretorio = "assets/images/marcas";
                                include "../includes/uploadarquivo.php";
                            }
                        }

                        if (!$erro && isset($edit)) {
                            $marca_antiga = "../assets/images/marcas/{$query->record['desenho_marca']}";

                            $query->begin();
                            $itens = array(
                                $form_nome,
                                $form_cpf,
                                $form_data,
                                $has_uploaded_image ? $imagem_nome : $form_marca_old,
                                $form_inscricao_estadual,
                                $form_tipo,
                                $form_email,
                                $form_telefone,
                                $form_ano_estimado,
                                $_login, $_ip, $_datahora,
                            );
                            $where = array(0 => array('id_proprietario', $id_proprietario));
                            $query->updateTupla('proprietario', $itens, $where);
                            $query->commit();

                            if (!$erro) {
                                unlink($marca_antiga);
                            }
                        }
                        if ($erro) echo callException($erro, 2); ?>
                    </div>
                </div>
            </div>

            <?
            $query->exec("SELECT * FROM proprietario WHERE id_proprietario = $id_proprietario");
            $query->result($query->linha);
            ?>

            <div class="card-body pt-0">
                <div class="form-row">
                    <input type="hidden" name="form_marca_old" value="<?= $query->record['desenho_marca'] ?>">
                    <div class="form-group col-md-6">
                        <label for="form_cpf">CPF</label>
                        <input autocomplete="off" type="text" class="form-control" name="form_cpf" id="form_cpf" value="<?= $query->record['cpf'] ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="form_nome">Nome</label>
                        <input autocomplete="off" type="text" class="form-control" name="form_nome" id="form_nome" value="<?= $query->record['nome'] ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="form_inscricao_estadual">Inscrição estadual</label>
                                <input autocomplete="off" type="number" class="form-control" name="form_inscricao_estadual" id="form_inscricao_estadual" value="<?= $query->record['inscricao_estadual'] ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="form_data">Data de registro</label>
                                <input readonly type="date" class="form-control" name="form_data" id="form_data" value="<?= $query->record['dt_registro_marca'] ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="form_data">Ano estimado</label>
                                <input autocomplete="off" type="number" class="form-control" name="form_ano_estimado" id="form_ano_estimado" value="<?= $query->record['ano_estimado'] ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="form_imagem">Desenho da marca</label>
                                <input autocomplete="off" type="file" class="form-control" name="form_imagem" id="form_imagem">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="form_tipo">Tipo de marca</label>
                                <select class="form-control" name="form_tipo" id="form_tipo">
                                    <?
                                    include "../includes/tipo_de_marca.php";
                                    print_options_for_tipo_de_marca($query->record['tipo_marca']);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <img src="<?= isset($edit) && !$erro && $has_uploaded_image && isset($imagem_dir)
                                        ? $imagem_dir
                                        : "../assets/images/marcas/" . $query->record['desenho_marca'] ?>" alt="Desenho marca" class="marca">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="form_email">Email</label>
                        <input autocomplete="off" type="text" class="form-control" name="form_email" id="form_email" value="<? echo isset($edit) && !$erro ? $form_email : $query->record['email'] ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="form_telefone">Telefone</label>
                        <input autocomplete="off" type="text" class="form-control" name="form_telefone" id="form_telefone" value="<? echo isset($edit) && !$erro ? $form_telefone : $query->record['celular'] ?>">
                    </div>
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

<? include_once('../includes/dashboard/footer.php'); ?>