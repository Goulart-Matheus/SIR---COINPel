<div class="modal fade text-left" id="PROPRIEDADE_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">




            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Propriedades
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="form_proprietario" class="">Proprietário</label>
                            <select name="form_proprietario" class="form-control">
                                <option value="">Selecione um proprietário...</option>
                                <?

                                $query_proprietario = new Query($bd);
                                $query_proprietario->exec(
                                    "SELECT id_proprietario, nome FROM proprietario ORDER BY nome"
                                );
                                $n2 = $query_proprietario->rows();
                                while ($n2--) {
                                    $query_proprietario->proximo();
                                    if ($erro)
                                        if ($form_proprietario == $query_proprietario->record[0]) $flag = 'selected';
                                        else unset($flag);
                                    echo "<option value='{$query_proprietario->record[0]}' $flag > {$query_proprietario->record[1]} </option>";
                                }

                                ?>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-6">
                                <label for="form_nome">Nome da propriedade</label>
                                <input autocomplete="off" type="text" class="form-control" name="form_nome" id="form_nome">
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
</div>


