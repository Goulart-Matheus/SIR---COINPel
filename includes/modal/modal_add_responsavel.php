<?
$query_responsavel->insertTupla('responsavel', $dados);
$id_responsavel_last = $query_responsavel->last_insert[0];

if ($form_responsavel == "") {

    $query->exec("SELECT 
    id_responsavel , nome , cpf , rg 
     FROM 
    Responsavel 
      
    ");

    $query->result($query->linha);

    $id_responsavel         = $query->record[0];
    $nome                   = $query->record[1];
    $cpf                    = $query->record[2];
    $rg                     = $query->record[3];
    $dt_nascimento          = $query->record[4];
    $endereco               = $query->record[5];
    $bairro                 = $query->record[6];
}
?>


<div class="modal fade text-left" id="RESPONSAVEL_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Registro de Responsaveis
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">

                    <div class="form-group col-12">

                        <div class="form-row">

                            <div class="form-group col-6">
                                <label for="form_responsavel"><span class="text-danger">*</span> Responsavel :</label>
                                <select name="form_responsavel" id="form_responsavel" class="form-control" required>
                                    <?
                                    $form_elemento = $erro ? $form_responsavel : "";
                                    include("../includes/inc_select_responsavel.php");
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Escolha o responsavel.
                                </div>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="form_mascara"><span class="text-danger">*</span>CPF: </label>
                                <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($erro) echo $form_mascara; ?>">
                                <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                            </div>
                            <div class="form-group col-12 col-md-3">
                                <label for="form_rg"><span class="text-danger">*</span> RG :</label>
                                <input required autocomplete="off" type="text" class="form-control" name="form_rg" id="form_rg" maxlength="100" value="<? if ($erro) echo $form_rg; ?>">
                            </div>
                        </div>


                    </div>

                    <div class="form-row">


                        <table class="table p-0 m-0">

                            <thead class="bg-light grey">

                                <tr>
                                    <th style="width: 25px;" class="px-1"></th>
                                    <th style="width: 150px;" class="px-1">Nome</th>
                                    <th style="width: 25px;" class="px-1">CPF</th>
                                    <th style="width: 25px;" class="px-1">RG</th>

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

                                    </tr>

                                <?

                                }

                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="modal-footer bg-light-2 text-center">
                    <button type="button" name="filter" id="filtro_modal" class="btn btn-light">
                        <i class="fa-solid fa-filter text-green"></i>
                        Filtrar
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>