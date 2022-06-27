<div class="modal Animal" role="dialog" id="modal_animal_cadastro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">

    <div class="modal-dialog modal-md">

        <div class="modal-content">

           
                <div class="modal-header bg-gradient-info-purple">
                    <h5 class="modal-title"><i class="fas fa-project-diagram"></i> Adicionar Animal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                   

                        <div class="form-row">

                            <div class="form-group col-12 col-md-6 ">
                                <label for="form_nro_ficha"><span class="text-danger">*</span> Numero da ficha:</label>
                                <input type="text" name="form_nro_ficha" id="form_nro_ficha" class="form-control">
                            </div>

                            <div class="form-group col-12 col-md-6 ">
                                <label for="form_nro_chip"><span class="text-danger">*</span> Numero do chip:</label>
                                <input type="text" name="form_nro_chip" id="form_nro_chip" class="form-control">
                            </div>

                        </div>                   

                    
                    <div class="form-row">
                    
                        <div class="form-group col-12 col-md-6">
                            <label for="form_tipo_pelagem"><span class="text-danger">*</span> Pelagem:</label>
                            <select name="form_tipo_pelagem" id="form_tipo_pelagem" class="form-control">
                                <?
                                    $form_elemento = $erro ? $form_tipo_pelagem : "";
                                    include("../includes/inc_select_pelagem.php");
                                ?>
                            </select>
                        </div>
                
                        <div class="form-group col-12 col-md-6">
                            <label for="form_tipo_especie"><span class="text-danger">*</span> Espécie:</label>
                            <select name="form_tipo_especie" id="form_tipo_especie" class="form-control">
                                <?
                                    $form_elemento = $erro ? $form_tipo_escola : "";
                                    include("../includes/inc_select_especie.php");
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                    
                        <div class="form-group col-12 col-md-6">
                            <label for="form_sexo"><span class="text-danger">*</span> Sexo</label>
                            <select name="form_sexo" required id="form_sexo" class="form-control">
                                <option value="" selected>Selecione o sexo:</option>
                                <option value="M">Macho</option>
                                <option value="F">Femia</option>
                            </select>
                            <div class="invalid-feedback">
                                Escolha o sexo do animal.
                        </div>
                        


                    </div>
                
                </div>    


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="add_animal_modal" class="btn btn-info">
                        <i class="fas fa-check"></i>&nbsp;
                        Salvar
                    </button>
                </div>

            

        </div>

    </div>

</div>