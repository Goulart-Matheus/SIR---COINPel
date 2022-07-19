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

            <div class="col-12">
                <i class="fas fa-list"></i> Responsáveis Vinculados

            </div>

            <div class="form-row">
                <div class="col-md-9 text-left">
                    <button type="button" class="btn bg-green btn-light btn-sm text-light" data-toggle="modal" data-target="#modal_add_responsavel">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>


            <!-- <div class="card-header">
                <div class="form-row">
                    <div class="col-md-6 text-left"><i class="fa-solid fa-car-side"></i></div>

                    <div class="col-md-6 text-right">
                        <button type="button" class="btn bg-green btn-light btn-sm text-light btn_modal_veiculos" data-toggle="modal" data-target="#modal_add_responsavel">
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
        //var tipo_escola = $("#form_tipo_escola").val();

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