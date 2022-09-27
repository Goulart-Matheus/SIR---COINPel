<?
$query_anexo = new Query($bd);

$query_anexo->exec("SELECT ha.nome, ha.arquivo, ha.dt_alteracao, ha.login, ha.id_hospedagem_anexo
              FROM hospedagem_anexo as ha, hospedagem as h
              WHERE h.id_hospedagem = $id_hospedagem AND
                    ha.id_hospedagem = h.id_hospedagem 
              ");

$n_anexo = $query_anexo->rows();
?>

<div class="card border">

    <div class="card-header bg-green">

        <div class="row">

            <div class="col-md-6 text-left">

                <i class="fas fa-paperclip pr-1"></i> ANEXOS

            </div>


            <div class="col-md-6 text-right">
                <button type="button" class="btn bg-gray btn-light btn-sm text-light" data-toggle="modal" data-target="#HOSPEDAGEM_ANEXO_ADD" data-modal="VI">
                    <i class="fas fa-plus"></i>
                </button>
            </div>

        </div>

    </div>

    <div class="card-body p-0 m-0" style="height: 200px;">

        <div class="col-12 p-0 m-0" id="chart_info"></div>
        <!-- Inicio -->

        <div class="col-12 text-center pt-5 text-dark d-none" id="nenhum_anexo_vinculado">

            <h5 class="mb-5">Sem anexo vinculado</h5>

        </div>
        <?


        if ($n_anexo == 0) {
        ?>

            <div class="col-12 text-center pt-5 text-dark" id="nenhum_anexo_vinculado">


                <h5 class="mb-5">Sem anexo vinculado</h5>

            </div>
        <?
        } else {

        ?>

            <table class="table table-overflow table-sm text-sm" id="atualizacao_tabela_anexo">

                <thead class="bg-light grey pl-1 table-responsive" style="position: sticky;top: 0">

                    <tr>
                        <th scope="col" class="px-1">Nome do Arquivo</th>
                        <th scope="col" class="px-1">Usuário</th>
                        <th scope="col" class="px-1">Data de Criação</th>
                        <th scope="col" class="px-1">Abrir</th>
                        <th scope="col" class="px-1"></th>


                    </tr>

                </thead>

                <tbody style="height: min 166px; width:auto;">

                    <?
                    while ($n_anexo--) {
                        $query_anexo->proximo();

                    ?>
                        <tr class='entered'>
                            <td><?= $query_anexo->record[0]; ?></td>
                            <td><?= $query_anexo->record[3]; ?></td>
                            <td><?= $query_anexo->record[2]; ?></td>
                            <td>
                                <a href="../arquivos/<?= $_login ?>/<?= $query_anexo->record[1] ?>" class="link" target="_blank">
                                    <i class="fas fa-paperclip"></i>
                                    Abrir
                                </a>
                            </td>
                            <td class="text-right px-0 pr-2">
                                <button class="btn btn-sm btn-light edit_anexo_button" type="button" data_id_anexo="<?= $query_anexo->record[4] ?>" data_nome_anexo=" <?= $query_anexo->record[0] ?>" title="Editar Anexo">
                                    <i class="fas fa-pencil-alt text-success"></i>
                                </button>
                                <button class="btn btn-sm btn-light delete_anexo_button" type="button" data_id_anexo="<?= $query_anexo->record[4] ?>" data_nome_anexo=" <?= $query_anexo->record[0] ?>" title="Excluir Anexo">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </td>

                        </tr>
                    <?

                    }

                    ?>

                </tbody>

            </table>

        <?
        }

        ?>


        <!-- Fim-->

    </div>

    <div class="card-footer">

    </div>

</div>

<div class="modal fade text-left" id="HOSPEDAGEM_ANEXO_ADD" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">


            <div class="modal-header bg-light-2">
                <h5 class="modal-title">
                    <i class="fas fa-filter text-green"></i>
                    Adicionar Anexo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formulario" method="post" enctype="multipart/form-data">
                <div class="modal-body">

                    <label for="form_arquivo">Anexo</label>
                    <div class="input-group ml-0 mb-2">

                        <input type="text" name="nome_arquivo_hospedagem_anexo" id="nome_arquivo_hospedagem_anexo" class="form-control col-7" placeholder="Nome do Anexo">

                        <div class="custom-file col-5">
                            <label for="arquivo_hospedagem_anexo" class="custom-file-label" data-browse="Pesquisar"><? echo "Selecione um Anexo" ?></label>
                            <input type="file" class="form-control custom-file-input" name="arquivo_hospedagem_anexo" id="arquivo_hospedagem_anexo">

                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light-2 text-center">
                    <button type="button" id="add_hospedagem_anexo" class="btn btn-light">
                        <i class="fa-solid fa-filter text-green"></i>
                        Salvar
                    </button>
                </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="delete_anexo_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title"><i class="fas fa-project-diagram"></i> Atenção</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-12 text-center">

                <div class="form-row">
                    <input type="hidden" id="id_anexo_delete_ajax">
                </div>

                <div class="form-row">
                    <div class="form-group col-12">
                        <h1 class="text-danger text-center py-2">
                            <i class="fas fa-trash"></i>
                        </h1>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group col-12 text-dark">
                        Você deseja realmente excluir: <br><span class="font-weight-bold" id="nome_anexo_ajax"></span> ?

                    </div>
                    <div class="form-group col-12 text-dark">
                        <p class="text-dark" style="font-size: small;">Este procedimento irá realizar a exclusão do anexo.</9>
                    </div>

                </div>

                <div class="modal-footer p-0 pt-2">
                    <div class="row col-12 p-0">
                        <div class="col-md-6 text-left">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-danger" id="deleta_anexo">Excluir</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="edit_anexo_modal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header bg-light-2">
                <h5 class="modal-title">
                    <i class="fas fa-filter text-green"></i>
                    Editar Anexo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group col-12 col-md-12">

                    <div class="form-row">

                        <input hidden type="text" name="edit_id_anexo_ajax" id="edit_id_anexo_ajax" class="form-control col-6">

                        <input type="text" name="edit_nome_anexo_ajax" id="edit_nome_anexo_ajax" class="form-control col-6" placeholder="Nome do Anexo">

                        <div class="custom-file col-6">

                            <label for="edit_arquivo_anexo_ajax" class="custom-file-label" data-browse="Pesquisar"><? echo "Selecione um Anexo" ?></label>
                            <input type="file" class="form-control custom-file-input" name="edit_arquivo_anexo_ajax" id="edit_arquivo_anexo_ajax">

                        </div>

                    </div>

                </div>


            </div>

            <div class="modal-footer bg-light-2 text-center">
                <button type="button" id="edit_button" class="btn btn-light">
                    <i class="fa-solid fa-filter text-green"></i>
                    Editar
                </button>
            </div>

        </div>

    </div>

</div>

<script src="../../../assets/js/jquery.js"></script>

<script>
    $(document).ready(function() {

        $("#add_hospedagem_anexo").on('click', function() {

            var id_hospedagem = <?= $id_hospedagem ?>;
            var form_nome = $("#nome_arquivo_hospedagem_anexo").val();
            var form_arquivo = $("#arquivo_hospedagem_anexo").prop('files')[0];

            var form_data = new FormData();

            form_data.append("id_hospedagem", id_hospedagem);
            form_data.append("form_nome", form_nome);
            form_data.append("form_arquivo", form_arquivo);

            $.ajax({
                type: 'POST',
                url: '../../includes/ajax_add_anexo_HOSPEDAGEM.php',
                data: form_data,
                method: "POST",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {

                    $("#HOSPEDAGEM_ANEXO_ADD").modal('hide');

                },
                success: function(response) {

                    if (response[0].status == 1) {
                        
                        window.location = 'HOSPEDAGEM_cover.php?id_hospedagem=' + id_hospedagem + '';

                    } else {

                        $("#nenhum_anexo_vinculado").addClass("d-none");

                    }

                },
                error: function(erro) {

                    // console.log(erro);

                }
            });
        });

        $(".delete_anexo_button").on('click', function() {

            var local = $("#delete_anexo_modal");
            local.find("#nome_anexo_ajax").html($(this).attr('data_nome_anexo'));
            local.find("#id_anexo_delete_ajax").val($(this).attr('data_id_anexo'));
            $("#delete_anexo_modal").modal('show');

        });

        $("#deleta_anexo").on('click', function() {


            $("#delete_anexo_modal").modal('hide');

            var id_anexo = $("#id_anexo_delete_ajax").val();

            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_exclui_anexo_HOSPEDAGEM.php',
                data: {

                    "id_anexo": id_anexo

                },
                beforeSend: function() {

                    console.log("Enviado ok");

                },
                success: function(ret) {

                    if (ret.status == 1) {

                        $("button[data_id_anexo='" + id_anexo + "']").parents("tr").remove();

                        if ($("#atualizacao_tabela_anexo").children('tbody').children().length == 0) {
                            $("#nenhum_anexo_vinculado").removeClass("d-none");
                            $("#atualizacao_tabela_anexo").children('thead').remove();
                        } else {
                            $("#nenhum_anexo_vinculado").addClass("d-none");
                        }
                    }
                },
                error: function(erro) {

                    console.log(erro);

                }

            });

        });

        $(".edit_anexo_button").on('click', function() {

            var local = $("#edit_anexo_modal");

            local.find("#edit_nome_anexo_ajax").val($(this).attr('data_nome_anexo'));
            local.find("#edit_id_anexo_ajax").val($(this).attr('data_id_anexo'));
            $("#edit_anexo_modal").modal('show');

        });

        $("#edit_button").on('click', function() {


            var id_hospedagem = <?= $id_hospedagem ?>;

            var id_hospedagem_anexo_edit = $("#edit_id_anexo_ajax").val();
            var form_nome_edit = $("#edit_nome_anexo_ajax").val();
            var form_arquivo_edit = $("#edit_arquivo_anexo_ajax").prop('files')[0];

            var form_data = new FormData();

            form_data.append("id_hospedagem_anexo", id_hospedagem_anexo_edit);
            form_data.append("form_nome", form_nome_edit);
            form_data.append("form_arquivo", form_arquivo_edit);
            form_data.append("id_hospedagem", id_hospedagem);

            $.ajax({
                type: 'POST',
                url: '../../includes/ajax_edit_anexo_HOSPEDAGEM.php',
                data: form_data,
                method: "POST",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {

                    $("#edit_anexo_modal").modal('hide');

                },
                success: function(response) {

                    if (response[0].resultado == 1) {
                        window.location = 'HOSPEDAGEM_cover.php?id_hospedagem=' + id_hospedagem + '';
                    }

                },
                error: function(erro) {

                    // console.log(erro);

                }
            });
        });



    });
</script>