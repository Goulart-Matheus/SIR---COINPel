<?

$query_animal->insertTupla('animal', $dados);
$id_animal_last = $query_animal->last_insert[0];

if ($form_animal == "") {

    $query->exec("SELECT 
          id_animal , nro_ficha , nro_chip , id_pelagem , id_especie, sexo, observacao
        FROM 
            animal
        WHERE 
            id_animal = $id_animal
        
    ");

    $query->result($query->linha);

    $id_animal                = $query->record[0];
    $nro_ficha                = $query->record[1];
    $nro_chip                 = $query->record[2];
    $id_pelagem               = $query->record[3];
    $id_especie               = $query->record[4];
    $sexo                     = $query->record[5];
    $observacao               = $query->record[6];
}
?>

<div class="modal fade show" id="modal_add_animal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <form action="<? echo $_SERVER['PHP_SELF'] . "?id_animal=" . $id_animal ?>" method="post">

                <div class="modal-header bg-gradient-yellow-orange">
                    <h5 class="modal-title"><i class="fas fa-project-diagram"></i> Adicionar Animal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group col-12">

                        <div class="form-row">

                            <div class="form-group col-6">
                                <label for="form_animal"><span class="text-danger">*</span> Animal :</label>
                                <select name="form_animal" id="form_animal" class="form-control" required>
                                    <?
                                    $form_elemento = $erro ? $form_animal : "";
                                    include("../includes/inc_select_animal.php"); ?>
                                </select>
                                <div class="invalid-feedback">
                                    Escolha o Animal.
                                </div>
                            </div>


                            <div class="form-group col-12 col-md-3">
                                <label for="form_nro_ficha"><span class="text-danger">*</span> Nro_Ficha:</label>
                                <input required autocomplete="off" type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha" maxlength="100" value="<? if ($erro) echo $form_nro_ficha; ?>">
                            </div>


                            <div class="form-group col-12 col-md-3">
                                <label for="form_nro_chip"><span class="text-danger">*</span> Nro_chip :</label>
                                <input required autocomplete="off" type="text" class="form-control" name="form_nro_chip" id="form_nro_chip" maxlength="100" value="<? if ($erro) echo $form_nro_chip; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12 col-md-2">
                                <label for="form_id_pelagem"><span class="text-danger">*</span> Pelagem :</label>
                                <input type="text" class="form-control" name="form_id_pelagem" id="form_id_pelagem" maxlength="100" value="<? if ($erro) echo $form_id_pelagem; ?>">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="form_id_especie"><span class="text-danger">*</span> Especie:</label>
                                <input type="text" class="form-control" name="form_id_especie" id="form_id_especie" maxlength="100" value="<? if ($erro) echo $form_id_especie; ?>">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="form_sexo"><span class="text-danger">*</span> Sexo:</label>
                                <input type="text" class="form-control" name="form_sexo" id="form_sexo" maxlength="100" value="<? if ($erro) echo $form_sexo; ?>">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="form_observacao"><span class="text-danger">*</span> Observacao:</label>
                                <input type="text" class="form-control" name="form_observacao" id="form_observacao" maxlength="100" value="<? if ($erro) echo $form_observacao; ?>">
                            </div>

                        </div>
                    </div>

                </div>
        </div>
    </div>
    <div class="form-row">


                        <table class="table p-0 m-0">

                            <thead class="bg-light grey">

                                <tr>
                                    <th style="width: 25px;" class="px-1"></th>
                                    <th style="width: 150px;" class="px-1">Nro_ficha</th>
                                    <th style="width: 25px;" class="px-1">Numero_chp</th>
                                    <th style="width: 25px;" class="px-1">Especie</th>
                                    <th style="width: 25px;" class="px-1">Sexo</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?
                                while ($n--) {
                                    $query->proximo();

                                ?>
                                    <tr>
                                        <td><?= $query->record[0]; ?></td>
                                        <td><?= $query->record[1]; ?></td>
                                        <td><?= $query->record[2]; ?></td>
                                        <td><?= $query->record[3]; ?></td>
                                        <td><?= $query->record[4]; ?></td>

                                    </tr>

                                <?

                                }

                                ?>

                            </tbody>

                        </table>

                    </div>




</div>


<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    <button type="button" id="modal_add_animal" class="btn btn-info">
        <i class="fas fa-check"></i>&nbsp;
        Salvar
    </button>
</div>