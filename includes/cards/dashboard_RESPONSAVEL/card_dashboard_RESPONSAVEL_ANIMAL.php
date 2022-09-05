<?
// preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)
include('../includes/variaveisAmbiente.php');


$query->exec(
    "SELECT
            ar.id_responsavel,
            ar.id_animal,
            a.nro_ficha,
            a.nro_chip,
            p.descricao,
            e.descricao,
            a.sexo,
            ar.id_animal_responsavel

        FROM
            responsavel r,
            animal_responsavel ar,
            animal a,
            pelagem p,
            especie e
        WHERE
            r.id_responsavel = $id_responsavel
        AND
            ar.id_responsavel = r.id_responsavel
        AND
            ar.id_animal = a.id_animal
        AND
            p.id_pelagem = a.id_pelagem
        AND
            e.id_especie = a.id_especie
            "

);

$n = $query->rows();


?>

<div class="card border">

    <div class="card-header bg-green">

        <div class="row">

            <div class="col-md-6 text-left">

                <i class="fas fa-list"></i> Animais Vinculados

            </div>


            <div class="col-md-6 text-right">
                <button type="button" class="btn bg-gray btn-light btn-sm text-light btn_modal_add_animal" data-toggle="modal" data-target="#modal_add_animal" data-modal="VI">
                    <i class="fas fa-plus"></i>
                </button>
            </div>




        </div>

    </div>

    <div class="card-body p-0 m-0 overflow-auto" style="height: 175px;">

        <div class="col-12 p-0 m-0" id="chart_info"></div>
        <!-- Inicio -->

        <div class="col-12 text-center pt-5 text-dark d-none" id="nenhum_animal_vinculado">

            <h5 class="mb-5">Este responsável ainda não possui nenhum animal vinculado</h5>

        </div>
        <?


        if ($n == 0) {
        ?>

            <div class="col-12 text-center pt-5 text-dark">

                <h5 class="mb-5">Este responsável ainda não possui nenhum animal vinculado</h5>

            </div>
        <?
        } else {

        ?>

            <? ?>
            <table class="table responsive" id="atualizacao_tabela_ajax2">

                <thead class="bg-light grey pl-1" style="position: sticky;top: 0">

                    <tr>
                        <th scope="col" class="px-1">Nro Ficha</th>
                        <th scope="col" class="px-1">Nro Chip</th>
                        <th scope="col" class="px-1">Pelagem</th>
                        <th scope="col" class="px-1">Especie</th>
                        <th scope="col" class="px-1">Sexo</th>
                        <th scope="col" class="px-1">Observacao</th>
                        <th scope="col" class="px-1"></th>

                    </tr>

                </thead>

                <tbody>

                    <?
                    while ($n--) {
                        $query->proximo();

                    ?>
                        <tr class='entered'>
                            <td><?= $query->record[2]; ?></td>
                            <td><?= $query->record[3]; ?></td>
                            <td><?= $query->record[4]; ?></td>
                            <td><?= $query->record[5]; ?></td>
                            <td><?= $query->record[6] == "M" ? "Macho" : "Fêmea"; ?></td>
                            <td><?= $query->record[7]; ?></td>
                            <td class="text-right px-0 pr-1" style="width: 100px; vertical-align: middle;">
                                <button class="btn btn-sm btn-light delete_animal_button" type="button" data-id-relatorio="<?= $query->record[7] ?>" data-nome-relatorio=" <?= $query->record[5] . ' - Nro Chip: ' . $query->record[3] ?>" title="Excluir Relatório">
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

        <div class="row">

            <div class="col-9"><a href='ANIMAL_form.php?id_responsavel=<?= $id_responsavel ?>'><i class="fa fa-plus"></i> Novo</a></div>

        </div>

    </div>

</div>




<div class="modal fade text-left" id="modal_add_animal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header bg-light-2">
                <h5 class="modal-title">

                    <i class="fa-solid fa-dog text-green"></i>

                    Registro de Animais
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-row">

                    <div class="form-group col-12 col-md-6">
                        <label for="form_nro_ficha"><span class="text-danger">*</span>Nro Ficha:</label>
                        <input type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha" maxlength="100">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="form_nro_chip"><span class="text-danger">*</span>Nro Chip:</label>
                        <input type="text" class="form-control" name="form_nro_chip" id="form_nro_chip" maxlength="100">
                    </div>

                </div>
                <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_especie"><span class="text-danger">*</span>Espécie:</label>
                        <select name="form_id_especie" id="form_id_especie" class="form-control">
                            <?
                            $form_elemento = $erro ? $form_id_especie : "";
                            include("../includes/inc_select_especie_card.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha Especie
                        </div>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_id_pelagem"><span class="text-danger">*</span>Pelagem:</label>
                        <select name="form_id_pelagem" id="form_id_pelagem" class="form-control">
                            <?
                            $form_elemento = $erro ? $form_id_pelagem : "";
                            include("../includes/inc_select_pelagem_card.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha a Pelagem
                        </div>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_sexo"><span class="text-danger">*</span> Sexo:</label>
                        <select name="form_sexo" id="form_sexo" class="form-control">
                            <option value="">Selecione o sexo:</option>
                            <option value="M">Macho</option>
                            <option value="F">Fêmea</option>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o sexo do animal.
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer bg-light-2 p-0">

                <div class="row col-12 p-0">
                    <div class="col-6 col-md-6 text-left p-1 pl-3">
                        <button type="button" id="btn_ajax_animal" name="btn_ajax_animal" class="btn btn-light btn_ajax_animal">
                            <i class="fa-solid fa-filter text-green"></i>
                            Filtrar
                        </button>
                    </div>

                    <div class="col-6 col-md-6 text-right p-1 pr-2">
                        <button type="button" id="btn_ajax_vincular_animal" name="btn_ajax_vincular_animal" class="btn btn-light btn_ajax_vincular_animal">
                            <i class="fa-solid fa-filter text-green"></i>
                            Vincular
                        </button>
                    </div>
                </div>

            </div>

            <div id="retorna_info_animal_ajax" name="retorna_info_animal_ajax"></div>

        </div>

    </div>
</div>

<div class="modal fade" id="delete_animal_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                    <input type="hidden" id="id_animal_desvincular_ajax">
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
                        Você deseja realmente excluir: <br><span class="font-weight-bold" id="animal_label"></span> ?

                    </div>
                    <div class="form-group col-12 text-dark">
                        <p class="text-dark" style="font-size: small;">Este procedimento irá realizar a exclusão do animal vinculado.</9>
                    </div>

                </div>

                <div class="modal-footer p-0 pt-2">
                    <div class="row col-12 p-0">
                        <div class="col-md-6 text-left">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-danger" id="deleta_animal">Excluir</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script src="../../../assets/js/app.js"></script>
<script src="../../../assets/js/jquery.js"></script>

<script>
    $(document).ready(function() {

        $(".btn_ajax_animal").on('click', function() {


            var nro_ficha = $("#form_nro_ficha").val();
            var nro_chip = $("#form_nro_chip").val();
            var pelagem = $("#form_id_pelagem").val();
            var especie = $("#form_id_especie").val();
            var sexo = $("#form_sexo").val();

            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_busca_animal.php',
                data: {
                    "nro_ficha": nro_ficha,
                    "nro_chip": nro_chip,
                    "pelagem": pelagem,
                    "especie": especie,
                    "sexo": sexo,

                },
                beforeSend: function() {

                    console.log("Enviado ok");
                    $("#modal_loading").modal('show');


                },
                success: function(ret) {

                    console.log(ret);

                    if (ret[0].resultado == 1) {

                        var monta_tabela = "";

                        monta_tabela += " <table class='table table-sm responsive text-center'>";
                        monta_tabela += "<tbody>";
                        monta_tabela += "<tr>";
                        monta_tabela += "<td style='width: 30px; background-color:#F3EFE7;'>*</td>";
                        monta_tabela += "<td style='width: 250px; background-color:#F3EFE7;'>Nro. Ficha:</td>";
                        monta_tabela += "<td style='width: 180px; background-color:#F3EFE7;'>Nro. Chip:</td>";
                        monta_tabela += "<td style='width: 180px; background-color:#F3EFE7;'>Pelagem:</td>";
                        monta_tabela += "<td style='width: 240px; background-color:#F3EFE7;'>Especie:</td>";
                        monta_tabela += "<td style='width: 200px; background-color:#F3EFE7;'>Sexo:</td>";
                        monta_tabela += "</tr>";

                        $.each(ret, function(indice, sexo) {
                            monta_tabela += "<tr class='entered'>";
                            monta_tabela += "<td style='width: 30px;'><input type='checkbox' name='form_vincula_animal[]' value= " + ret[indice].id_animal + " ></td>";
                            monta_tabela += "<td style='width: 250px;'>" + ret[indice].nro_ficha + "</td>";
                            monta_tabela += "<td style='width: 180px;'>" + ret[indice].nro_chip + "</td>";
                            monta_tabela += "<td style='width: 180px;'>" + ret[indice].pelagem + "</td>";
                            monta_tabela += "<td style='width: 240px;'>" + ret[indice].especie + "</td>";
                            monta_tabela += "<td style='width: 200px;'>" + ret[indice].sexo + "</td>";
                            monta_tabela += "</tr>";


                        });
                        monta_tabela += "</tbody>";

                        monta_tabela += "<tfoot>";
                        monta_tabela += "<tr>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "</tr>";
                        monta_tabela += "</tfoot>";

                        monta_tabela += " </table>";


                        $("#retorna_info_animal_ajax").html(monta_tabela).addClass('bg-ligth').removeClass('bg-danger')
                    } else {

                        $("#retorna_info_animal_ajax").html('<h5 class = "text-center col-12">Animal não encontrado</h5>').addClass('bg-danger').removeClass('bg-green')

                    }

                },
                error: function(erro) {

                    console.log(erro);

                }
            });
        });
    });
</script>


<script>
    $(document).ready(function() {

        $(".btn_ajax_vincular_animal").on('click', function() {


            var id_responsavel = <? echo $id_responsavel ?>;
            var form_vincula_animal = [];
            $.each($("input[name='form_vincula_animal[]']:checked"), function() {
                form_vincula_animal.push($(this).val());

                console.log(id_responsavel);
                console.log(form_vincula_animal);
            });

            console.log(form_vincula_animal);

            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_vincula_animal.php',
                data: {

                    "form_vincula_animal": form_vincula_animal,
                    " id_responsavel": id_responsavel

                },
                beforeSend: function() {

                    console.log("Enviado ok");
                    $("#modal_loading").modal('show');

                    $("#modal_add_animal").modal('hide');
                },
                success: function(ret) {

                    console.log(ret);
                    window.location = 'RESPONSAVEL_cover.php?id_responsavel=' + id_responsavel + '';
                    if (ret[0] == 1) {


                        //console.log('RESPONSAVEL_cover.php?id_responsavel=' + id_responsavel + '');

                        //   $("#retorna_info_animal_ajax").html('').addClass('bg-ligth').removeClass('bg-danger')
                    } else {

                        $("#retorna_info_animal_ajax").html('<h5 class = "text-center col-12">Erro ao vincular este animal</h5>').addClass('bg-danger').removeClass('bg-green')

                    }

                },
                error: function(erro) {

                    console.log(erro);

                }
            });
        });

        $(".delete_animal_button").on('click', function() {

            var local = $("#delete_animal_modal");
            local.find("#animal_label").html($(this).attr('data-nome-relatorio'));
            local.find("#id_animal_desvincular_ajax").val($(this).attr('data-id-relatorio'));
            $("#delete_animal_modal").modal('show');

        });       


        console.log($("#atualizacao_tabela_ajax2").find('tbody').html());
        $("#deleta_animal").on('click', function() {


            $("#delete_animal_modal").modal('hide');

            var id_animal_responsavel = $("#id_animal_desvincular_ajax").val();
            var id_responsavel = <? echo $id_responsavel ?>;

            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_desvincula_animal.php',
                data: {

                    "id_animal_responsavel": id_animal_responsavel,
                    "id_responsavel": id_responsavel

                },
                beforeSend: function() {

                    console.log("Enviado ok");

                },
                success: function(ret) {

                    if (ret.status == 1) {
                        $("button[data-id-relatorio='" + id_animal_responsavel + "']").parents("tr").remove();
                        
                        if ($("#atualizacao_tabela_ajax2").children('tbody').children().length==0) {
                            $("#nenhum_animal_vinculado").removeClass("d-none");
                            $("#atualizacao_tabela_ajax2").children('thead').remove();
                        }else{
                            $("#nenhum_animal_vinculado").addClass("d-none");
                        }
                    } 
                },
                error: function(erro) {

                    console.log(erro);

                }

            });

        });
    });
</script>