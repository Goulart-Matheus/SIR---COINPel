<div class="modal fade text-left" id="APLICACAO_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <form  method="get" action="<?= $_SERVER['PHP_SELF'] ?>">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Aplicações
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-12">

                            <label for="form_superior">Superior</label>

                            <select class="form-control" name="form_superior" id="form_superior">
                                <option value="">-- Selecione um superior --</option>
                                <?
                                $query_filter = new Query($bd);
                                $query_filter->exec("SELECT * FROM aplicacao WHERE tipo='m' ORDER BY descricao");
                                $n_filter = $query_filter->rows();
                                while ($n_filter--) {
                                    $query_filter->proximo();
                                    echo "<option value='" . $query_filter->record[0] . "'>" . $query_filter->record[3] . "</option>";
                                }
                                ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="form_descricao">Descrição</label>
                            <input type="text" class="form-control" name="form_descricao" id="form_descricao" value="<? if($erro) echo $form_descricao; ?>">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="form_fonte">Fonte</label>
                            <input type="text" class="form-control" name="form_fonte" value="<? if($erro) echo $form_fonte; ?>">
                        </div>
                    </div>   
                    
                    <div class="form-row">

                        <div class="form-group col-12 col-md-6">
                            <label for="form_tipo">Tipo</label>
                            <select class="form-control" name="form_tipo" id="form_tipo">
                                <option value="">-- Selecione um tipo --</option>
                                <option value='a'>Aplicação</option>
                                <option value='m'>Menu</option>
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="form_situacao">Situação</label>
                            <select class="form-control" name="form_situacao" id="form_situacao">
                                <option value="">-- Selecione uma sitiação --</option>
                                <option value="0">Oculto</option>
                                <option value="1">Vísivel</option>
                            </select>
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