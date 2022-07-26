<?
// preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

$query->exec(
    "SELECT
                id_animal,
                id_responsavel,
                nro_ficha,
                nro_chip,
                id_pelagem,
                id_especie,
                sexo,
                observacao

            FROM
                animal,
                
            WHERE
                id_animal = $id_animal
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

                <i class="fas fa-list"></i> Animais Vinculados

            </div>


            <div class="col-md-6 text-right">
                <button type="button" class="btn bg-green btn-light btn-sm text-light btn_modal_add_animal" data-toggle="modal" data-target="#modal_add_animal" data-modal="VI">
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

                <h5 class="mb-5">Este responsavel ainda não possue nenhum animal vinculado</h5>



            </div>
        <?
        } else {
        ?>

            <table class="table p-0 m-0">

                <thead class="bg-light grey">

                    <tr>

                        <th style="width: 150px;" class="px-1">Nro Ficha</th>
                        <th style="width: 25px;" class="px-1">Nro Chip</th>
                        <th style="width: 25px;" class="px-1">Pelagem</th>
                        <th style="width: 25px;" class="px-1">Especie</th>
                        <th style="width: 150px;" class="px-1">Sexo</th>
                        <th style="width: 75px;" class="px-1">Observacao</th>

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

            <div class="col-9"><a href='ANIMAL_form.php?id_responsavel=<?= $id_responsavel ?>'><i class="fa fa-plus"></i> Novo</a></div>

        </div>

        <!-- <div class="col-6 text-right"><a href='ANIMAL_viewDados.php?id_animal=<?= $id_animal ?>'>Editar informações</a></div> -->

    </div>

</div>

</div>


<script>
    $("#modal_add_animal").on('click', function() {

        var nro_ficha = $("#form_nro_ficha").val();
        var nro_chip = $("#form_nro_chip").val();
        var pelagem = $("#form_pelagem").val();
        var especie = $("#form_especie").val();
        var sexo = $("#form_sexo").val();
        var observacao = $("#form_observacao").val();


        $.ajax({
            type: "post",
            url: "../includes/ajax_add_animal.php",
            data: {
                "nro_ficha": nro_ficha,
                "nro_chip": nro_chip,
                "pelagem": pelagem,
                "especie": especie,
                "sexo": sexo,
                "observacao": observacao,

            },
            beforeSend: function() {
                $("#modal_add_animal").modal('hide');
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

$query_modal = new Query($bd);
$query_modal_tab = new Query($bd);
?>
<div class="modal fade text-left" id="modal_add_animal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">

                        <i class="fas fa-meh text-green"></i>

                        Registro de Animais
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">


                        <div class="form-group col-12 col-md-4">
                            <label for="form_nro_ficha"><span class="text-danger">*</span>Nro Ficha</label>
                            <input type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha" maxlength="100">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_nro_chip"><span class="text-danger">*</span>Chip</label>
                            <input type="text" class="form-control" name="form_nro_chip" id="form_nro_chip" maxlength="100">
                        </div>


                        <div class="form-group col-12 col-md-4">
                            <label for="form_especie"><span class="text-danger">*</span>Especie</label>
                            <input type="text" class="form-control" name="form_Especie" id="form_especie" maxlength="100">
                        </div>

                        <div class="form-group col-12 col-md-4">
                            <label for="form_sexo"><span class="text-danger">*</span>Sexo</label>
                            <input type="text" class="form-control" name="form_Sexo" id="form_sexo" maxlength="100">
                        </div>


                        <?


                        $where = "";
                        $where .= $form_animal      != "" ? "           AND r.nome  = $form_animal " : "";
                        $where .= $form_nro_ficha   != "" ? "           AND r.cpf   = $form_nro_ficha " : "";
                        $where .= $form_nro_chip    != "" ? "           AND r.rg    = $form_nro_chip " : "";

                        $query_modal_tab->exec(

                            "SELECT 
                                            a.id_animal, a.nro_ficha, a.nro_chip,e.descricao,a.sexo
                                    FROM
                                            animal a, especie e
                                    WHERE
                                            a.id_especie = e.id_especie " 
                        );


                        $nmodal = $query_modal_tab->rows();

                        ?>

                    </div>

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

