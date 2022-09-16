<?


$query->exec(
    "SELECT
                ar.id_responsavel,
                ar.id_animal,
                r.nome,
                r.cpf,
                r.rg,
                r.dt_nascimento,
                r.endereco,
                b.descricao,
                ar.id_animal_responsavel


            FROM
                responsavel r,
                animal_responsavel ar,
                animal a,
                bairro b
            WHERE
               a.id_animal = $id_animal
            AND
            ar.id_responsavel = r.id_responsavel
            AND
            ar.id_animal = a.id_animal
            AND
            b.id_bairro = r.id_bairro             "

);

$n = $query->rows();

?>

<div class="card border">

    <div class="card-header bg-green">

        <div class="row">

            <div class="col-md-6 text-left">

                <i class="fas fa-list"></i> RESPONSÁVEIS VINCULADOS

            </div>

            <div class="col-md-6 text-right">
                <button type="button" class="btn bg-gray btn-light btn-sm text-light btn_modal_add_responsaveis" data-toggle="modal" data-target="#modal_add_responsavel" data-modal="VI">
                    <i class="fas fa-plus"></i>
                </button>
            </div>


        </div>

    </div>

    <div class="card-body p-0 m-0">

        <div class="col-12 p-0 m-0" id="chart_info"></div>

        <div class="col-12 text-center pt-5 text-dark d-none" id="nenhum_responsavel_vinculado">

            <h5 class="mb-5">Este animal ainda não possui responsável vinculado</h5>

        </div>
        <?
        if ($n == 0) {
        ?>

            <div class="col-12 text-center pt-5 text-dark" id="ajax_excluir_responsavel_nenhum">

                <h5 class="mb-5">Este animal ainda não possui responsável vinculado</h5>

            </div>
        <?
        } else {
        ?>

            <table class="table table-sm text-sm table-overflow" style="width:auto;" id="atualizacao_tabela_ajax">

                <thead class="bg-light grey table-responsive">

                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">RG</th>
                        <th scope="col" class="px-1"></th>
                    </tr>

                </thead>

                <tbody style="height: 200px;  width:auto;">

                    <?
                    while ($n--) {
                        $query->proximo();

                    ?>
                        <tr class='entered'>
                            <td><?= $query->record[2]; ?></td>
                            <td><?= $query->record[3]; ?></td>
                            <td><?= $query->record[4]; ?></td>
                            <td class="text-right px-0 pr-1">
                                <button class="btn btn-sm btn-light delete_responsavel_button" type="button" data-id-responsavel="<?= $query->record[8] ?>" data-nome-responsavel=" <?= $query->record[2] . ' - CPF: ' . $query->record[3] ?>" title="Exclir Vínculo">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    <?

                    }

                    ?>

            </table>

        <?
        }

        ?>


    </div>

    <div class="card-footer">

        <div class="row">

            <div class="col-9"><a href='RESPONSAVEL_form.php?id_animal=<?= $id_animal ?>'><i class="fa fa-plus"></i> Novo</a></div>

        </div>

    </div>

</div>




<div class="modal fade text-left" id="modal_add_responsavel" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header bg-light-2">
                <h5 class="modal-title">

                    <i class="fas fa-address-card"></i>

                    Registro de Responsável
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_responsavel"><span class="text-danger">*</span>Nome</label>
                        <input type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="100">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_mascara"><span class="text-danger">*</span>CPF</label>
                        <input type="text" class="form-control" name="form_mascara" id="form_mascara" maxlength="11">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_rg"><span class="text-danger">*</span>RG</label>
                        <input type="text" class="form-control" name="form_rg" id="form_rg" maxlength="14">
                    </div>

                </div>

            </div>

            <div class="modal-footer bg-light-2">
                <div class="row col-12 p-0">
                    <div class="col-md-6 text-left">
                        <button type="button" id="btn_ajax_responsavel" name="btn_ajax_responsavel" class="btn btn-light btn-sm btn_ajax_responsavel">
                            <i class="fa-solid fa-filter text-green"></i>
                            Filtrar
                        </button>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" id="btn_ajax_vincular_responsavel" name="btn_ajax_vincular_responsavel" class="btn  btn-light btn-sm btn_ajax_vincular_responsavel">
                            <i class="fa-solid fa-filter text-green"></i>
                            Vincular
                        </button>
                    </div>
                </div>
            </div>

            <div id="retorna_info_responsavel_ajax" name="retorna_info_responsavel_ajax"></div>

        </div>

    </div>

</div>

<div class="modal fade" id="delete_responsavel_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                    <input type="hidden" id="id_responsavel_desvincular_ajax">
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
                        Você deseja realmente excluir: <br><span class="font-weight-bold" id="responsavel_label"></span> ?

                    </div>
                    <div class="form-group col-12 text-dark">
                        <p class="text-dark" style="font-size: small;">Este procedimento irá realizar a exclusão do responsável vinculado.</9>
                    </div>

                </div>

                <div class="modal-footer p-0 pt-2">
                    <div class="row col-12 p-0">
                        <div class="col-md-6 text-left">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-danger" id="deleta_responsavel">Excluir</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script src="../../../assets/js/jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function() {        

        $(".btn_ajax_responsavel").on('click', function() {

            var nome = $("#form_responsavel").val();
            var cpf = $("#form_mascara").val();
            var rg = $("#form_rg").val();

            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_busca_responsavel.php',
                data: {
                    "nome": nome,
                    "cpf": cpf,
                    "rg": rg,

                },
                beforeSend: function() {


                },
                success: function(ret) {

                    if (ret[0].resultado == 1) {

                        var monta_tabela = "";
                        monta_tabela += "<div class='card-body p-0 m-0' style='height: 250px;'>";
                        monta_tabela += "<table class='table table-sm text-sm text-left table-overflow'>";
                        monta_tabela += "<thead p-0 text-left>";
                        monta_tabela += "<tr>";
                        monta_tabela += "<th style='width: 5px;   background-color:#F3EFE7; '></th>";
                        monta_tabela += "<th style='width: 250px; background-color:#F3EFE7;'>Nome:</th>";
                        monta_tabela += "<th style='width: 180px; background-color:#F3EFE7;'>CPF:</th>";
                        monta_tabela += "<th style='width: 180px; background-color:#F3EFE7;'>RG:</th>";
                        monta_tabela += "<th style='width: 240px; background-color:#F3EFE7;'>Endereço:</th>";
                        monta_tabela += "<th style='width: 200px; background-color:#F3EFE7;'>Bairro:</th>";
                        monta_tabela += "</tr>";
                        monta_tabela += "</tread>";

                        monta_tabela += "<tbody p-0 style='height: 190px;'>";

                        $.each(ret, function(indice, nome) {

                            monta_tabela += "<tr class='entered'>";
                            monta_tabela += "<td style='width: 30px;'><input type='checkbox' name='form_vincula_responsavel[]' value= " + ret[indice].id_responsavel + " ></td>";
                            monta_tabela += "<td style='width: 250px;'>" + ret[indice].nome + "</td>";
                            monta_tabela += "<td style='width: 180px;'>" + ret[indice].cpf + "</td>";
                            monta_tabela += "<td style='width: 180px;'>" + ret[indice].rg + "</td>";
                            monta_tabela += "<td style='width: 240px;'>" + ret[indice].endereco + "</td>";
                            monta_tabela += "<td style='width: 200px;'>" + ret[indice].bairro + "</td>";
                            monta_tabela += "</tr>";

                        });
                        monta_tabela += "</tbody>";                   
                        monta_tabela += " </table>";
                        monta_tabela += " </div>";

                        $("#retorna_info_responsavel_ajax").html(monta_tabela).addClass('bg-ligth').removeClass('bg-danger')

                    } else {

                        $("#retorna_info_responsavel_ajax").html('<h5 class = "text-center col-12">Responsável não encontrado</h5>').addClass('bg-danger').removeClass('bg-green');

                    }

                },
                error: function(erro) {

                    console.log(erro);

                }
            });
        });


        $(".btn_ajax_vincular_responsavel").on('click', function() {


            var id_animal = <? echo $id_animal ?>;
            var form_vincula_responsavel = [];
            $.each($("input[name='form_vincula_responsavel[]']:checked"), function() {
                form_vincula_responsavel.push($(this).val());

            });

            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_vincula_responsavel.php',
                data: {

                    " id_animal": id_animal,
                    "form_vincula_responsavel": form_vincula_responsavel,

                },
                beforeSend: function() {

                    console.log("Enviado ok");
                    $("#modal_add_responsavel").modal('hide');

                },
                success: function(ret) {                  

                    if (ret.status ==1) {
                        window.location = 'ANIMAL_cover.php?id_animal=' + id_animal + '';
                        $("#ajax_excluir_responsavel_nenhum").addClass("d-none");
                        $("#ajax_excluir_responsavel_nenhum_botao").removeClass("d-none");
                        
                    } else {
                        $("#ajax_excluir_responsavel_nenhum").removeClass("d-none");
                        $("#ajax_excluir_responsavel_nenhum_botao").addClass("d-none");                       
                    }

                },
                error: function(erro) {

                    console.log(erro);

                }
            });

        });




        $(".delete_responsavel_button").on('click', function() {

            var local = $("#delete_responsavel_modal");
            local.find("#responsavel_label").html($(this).attr('data-nome-responsavel'));
            local.find("#id_responsavel_desvincular_ajax").val($(this).attr('data-id-responsavel'));
            $("#delete_responsavel_modal").modal('show');

        });

        $("#deleta_responsavel").on('click', function() {


            $("#delete_responsavel_modal").modal('hide');

            var id_responsavel_animal = $("#id_responsavel_desvincular_ajax").val();
            var id_animal = <? echo $id_animal ?>;

            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_exclui_responsavel.php',
                data: {

                    "id_responsavel_animal": id_responsavel_animal,
                    "id_animal": id_animal

                },
                beforeSend: function() {

                    console.log("Enviado ok");

                },
                success: function(ret) {

                    if (ret.status == 1) {
                        $("button[data-id-responsavel='" + id_responsavel_animal + "']").parents("tr").remove();

                        if ($("#atualizacao_tabela_ajax").children('tbody').children().length == 0) {
                            $("#nenhum_responsavel_vinculado").removeClass("d-none");
                            $("#atualizacao_tabela_ajax").children('thead').remove();
                        } else {
                            $("#nenhum_responsavel_vinculado").addClass("d-none");
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