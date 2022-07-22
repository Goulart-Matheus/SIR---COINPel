<?
// preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

$query->exec(
    "SELECT
                ar.id_responsavel,
                ar.id_animal,
                r.nome,
                r.cpf,
                r.rg,
                r.dt_nascimento,
                r.endereco,
                b.descricao

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
            b.id_bairro = r.id_bairro
             "

);
//$total_contato = $query->record[0];
$n = $query->rows();

//$js_Onclick = "OnClick=javascript:window.location=('ANIMAL_edit.php?search=true&id_animal=$id_animal";

?>

<div class="card border">

    <div class="card-header bg-green">

        <div class="row">

            <div class="col-md-6 text-left">

                <i class="fas fa-list"></i> Responsáveis Vinculados

            </div>


            <div class="col-md-6 text-right">
                <button type="button" class="btn bg-green btn-light btn-sm text-light btn_modal_add_responsaveis" data-toggle="modal" data-target="#modal_add_responsavel" data-modal="VI">
                    <i class="fas fa-plus"></i>
                </button>
            </div>


            <!-- <div class="card-header">
                <div class="form-row">
                    <div class="col-md-6 text-left"><i class="fa-solid fa-car-side"></i>                                            

                    <div class="col-md-6 text-right">
                        <button type="button" class="btn bg-green btn-light btn-sm text-light btn_modal_add_responsavel" data-toggle="modal" data-target="#MODAL_ADD_RESPONSAVEL" data-modal="VI">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>-->



        </div>

    </div>

    <div class="card-body p-0 m-0" style="height: 175px;">

        <div class="col-12 p-0 m-0" id="chart_info"></div>
        <!-- Inicio -->
        <?
        if ($n == 0) {
        ?>

            <div class="col-12 text-center pt-5 text-dark">

                <h5 class="mb-5">Este animal ainda não possue nenhum responsável vinculado</h5>



            </div>
        <?
        } else {
        ?>

            <table class="table p-0 m-0">

                <thead class="bg-light grey">

                    <tr>

                        <th style="width: 150px;" class="px-1">Nome</th>
                        <th style="width: 25px;" class="px-1">CPF</th>
                        <th style="width: 25px;" class="px-1">RG</th>
                        <th style="width: 25px;" class="px-1">Data de nascimento</th>
                        <th style="width: 150px;" class="px-1">Endereço</th>
                        <th style="width: 75px;" class="px-1">Bairro</th>

                    </tr>

                </thead>

                <tbody>

                    <?
                    while ($n--) {
                        $query->proximo();

                    ?>
                        <tr>
                            <td><?= $query->record[2]; ?></td>
                            <td><?= $query->record[3]; ?></td>
                            <td><?= $query->record[4]; ?></td>
                            <td><?= $query->record[5]; ?></td>
                            <td><?= $query->record[6]; ?></td>
                            <td><?= $query->record[7]; ?></td>

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

            <div class="col-9"><a href='RESPONSAVEL_form.php?id_animal=<?= $id_animal ?>'><i class="fa fa-plus"></i> Novo</a></div>

        </div>

        <!-- <div class="col-6 text-right"><a href='ANIMAL_viewDados.php?id_animal=<?= $id_animal ?>'>Editar informações</a></div> -->

    </div>

</div>

</div>



<script>
    $("#modal_add_responsvel").on('click', function() {

        var nome_responsavel = $("#form_nome_responsavel").val();
        var CPF = $("#form_cpf").val();

        $.ajax({
            type: "post",
            url: "../includes/ajax_add_responsavel.php",
            data: {
                "nome_responsavel": nome_responsavel,
                //"tipo_escola": tipo_escola
            },
            beforeSend: function() {
                $("#modal_add_responsavel").modal('hide');
            },
            success: function(response) {
                console.log("OI");
                console.log(response);
            },
            error: function(response) {

            }
        });

    });
</script>

<?
$query_modal2 = new Query($bd);
$query_modal = new Query($bd);
?>
<div class="modal fade text-left" id="modal_add_responsavel" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtro Registro de Responsaveis
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">


                        <div class="form-group col-12 col-md-4">
                            <label for="form_responsavel"><span class="text-danger">*</span>Nome do Responsavel</label>
                            <input type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="100">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_cpf"><span class="text-danger">*</span>CPF</label>
                            <input type="text" class="form-control" name="form_cpf" id="form_cpf" maxlength="100">
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_rg"><span class="text-danger">*</span>RG</label>
                            <input type="text" class="form-control" name="form_rg" id="form_rg" maxlength="100">
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="form_endereço"><span class="text-danger">*</span>Endereço</label>
                            <input type="text" class="form-control" name="form_endereco" id="form_endereco" maxlength="100">
                        </div>


                        <div class="form-group col-12 col-md-6">
                            <label for="form_bairro"><span class="text-danger">*</span>Bairro</label>
                            <input type="text" class="form-control" name="form_bairro" id="form_Bairro" maxlength="100">
                        </div>


                        <!--<label for="form_responsavel"><span class="text-danger">*</span> Nome do Responsavel </label>
                            <select class="search form-control" name="form_responsavel" id="form_responsavel">
                               // <?
                                    // echo "<option value='' selected>" . "Selecione um Responsavel" . "</option>";
                                    // $query_modal2->exec("SELECT id_responsavel, nome, cpf, rg, dt_nascimento, endereco, id_bairro FROM responsavel");
                                    //$nmodal = $query_modal2->rows();

                                    // while ($nmodal--) {
                                    //$query_modal2->proximo();
                                    //echo "<option value='" . $query_modal2->record[0] . "'>" . $query_modal2->record[1] . "</option>";
                                    // }

                                    ?>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_tipo_contato"><span class="text-danger">*</span> Tipo de Contato </label>
                            <select class="search form-control" name="form_tipo_contato" id="form_tipo_contato">
                                <?
                                // echo "<option value='' selected>" . "Selecione um Tipo de Contato" . "</option>";
                                // $query_modal->exec("SELECT id_tipo_contato, descricao, mascara FROM tipo_de_contato");
                                // $nmodal2 = $query_modal->rows();

                                //while ($nmodal2--) {
                                //$query_modal->proximo();
                                //echo "<option value='" . $query_modal->record[0] . "'>" . $query_modal->record[1] . "</option>";
                                //}

                                //
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_mascara"><span class="text-danger">*</span>Contato</label>
                            <input type="number" class="form-control" name="form_mascara" id="form_mascara" maxlength="100">
                        </div>-->

                    </div>


                </div>

                <div class="modal-footer bg-light-2 text-center">
                    <button type="submit" name="filter" class="btn btn-light">
                        <i class="fa-solid fa-filter text-green"></i>
                        Filtrar
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>