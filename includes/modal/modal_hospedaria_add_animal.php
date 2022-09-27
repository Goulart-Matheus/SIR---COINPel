<div class="modal fade text-left" id="ANIMAL_modal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header bg-light-2">
                <h5 class="modal-title">
                    <i class="fas fa-dog text-green"></i>
                    Cadastrar Animal
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">


                <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_ficha"><span class="text-danger">*</span> Numero Ficha</label>
                        <input type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha_ajax_cadastro" maxlength="100" value="<? if ($erro) echo $form_nro_ficha; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_chip"><span class="text-danger">*</span> Numero Chip</label>
                        <input type="text" class="form-control" name="form_nro_chip" id="form_nro_chip_ajax_cadastro" maxlength="100" value="<? if ($erro) echo $form_nro_chip; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_sexo"><span class="text-danger">*</span> Sexo</label>
                        <select name="form_sexo_ajax_cadastro" id="form_sexo_ajax_cadastro" class="form-control">
                            <option value="" selected>Selecione o sexo:</option>
                            <option value="M">Macho</option>
                            <option value="F">Fêmea</option>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o sexo do animal.
                        </div>
                    </div>
                </div>
                <div class="form-row">


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_pelagem"><span class="text-danger">*</span>Pelagem</label>
                        <select name="form_id_pelagem" id="form_id_pelagem_ajax_cadastro" class="form-control select2_pelagem">
                            <?
                            $form_elemento = $erro ? $form_id_pelagem : "";
                            include("../includes/inc_select_pelagem.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha a Pelagem
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_especie"><span class="text-danger">*</span>Espécie</label>
                        <select name="form_id_especie" id="form_id_especie_ajax_cadastro" class="form-control select2_especie">
                            <?
                            $where = "";
                            $form_elemento = $erro ? $form_especie : "";
                            include("../includes/inc_select_especie.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha Especie
                        </div>
                    </div>


                </div>
                <div class="form-row">

                    <div class="form-group col-12">
                        <label for="form_observacao">Observação</label>
                        <input type="text" class="form-control" name="form_observacao" id="form_observacao_ajax_cadastro" maxlength="200" value="<? if ($erro) echo $form_observacao; ?>">
                    </div>

                </div>

                <script>
                    <?
                    if ($id_responsavel != "") {
                        link('RESPONSAVEL_cover.php?id_responsavel=', $id_responsavel);
                    }
                    ?>
                </script>



            </div>

            <div class="modal-footer bg-light-2 text-center">
                <button type="button" id="btn_ajax_add_animal" name="btn_ajax_add_animal" class="btn btn-light btn_ajax_add_animal">
                    <i class="fa-solid fa-check text-green"></i>
                    Save
                </button>



            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function() {

            $(".btn_ajax_add_animal").on('click', function() {
                var nro_ficha = $("#form_nro_ficha_ajax_cadastro").val();
                var nro_chip = $('#form_nro_chip_ajax_cadastro').val();
                var sexo = $('#form_sexo_ajax_cadastro').val();
                var id_pelagem = $('#form_id_pelagem_ajax_cadastro').val();
                var id_especie = $('#form_id_especie_ajax_cadastro').val();
                var observacao = $('#form_observacao_ajax_cadastro').val();


                $.ajax({
                    type: 'POST',
                    url: '../../includes/ajax_hospedagem_add_animal.php',
                    data: {
                        "nro_ficha": nro_ficha,
                        "nro_chip": nro_chip,
                        "sexo": sexo,
                        "id_pelagem": id_pelagem,
                        "id_especie": id_especie,
                        "observacao": observacao
                    },
                    beforeSend: function() {

                        //$("#ANIMAL_modal").modal('hide');
                    },
                    success: function(ret) {

                        if (ret[0]['resultado'] === '1') {

                            $("#form_nro_chip").val(ret[0]['nro_chip']);
                            $("#form_nro_ficha").val(ret[0]['nro_ficha']);
                            $("#form_especie").val(ret[0]['especie']);
                            $("#form_pelagem").val(ret[0]['pelagem']);
                            if(ret[0]['sexo']=='M'){
                                ret[0]['sexo'] = "Macho";
                            }else{
                                ret[0]['sexo'] = "Fêmea";
                            }
                            $("#form_sexo").val(ret[0]['sexo']);
                            //$("#form_nro_chip").prop("selectedIndex", 1).val(ret[0]['nro_chip']).select2();
                            //$("#form_nro_ficha").prop("selectedIndex", 1).val(ret[0]['nro_ficha']).select2();
                            if (ret[0]['id_responsavel'] != 0) {
                               // $("#form_id_responsavel").prop("selectedIndex", 1).val(ret[0]['id_responsavel']).select2();
                            } else {
                               // $("#form_id_responsavel").prop("selectedIndex", 0).select2();
                            }

                            $("#PESQUISA_ANIMAL_modal").modal('hide');
                            $("#ANIMAL_modal").modal('hide');
                            
                            
                            console.log(ret[0]);
                        } else {
                            $("#msg").text("Animal ja Existente");



                        }
                    },
                    error: function(erro) {
                        console.log(erro);
                    }
                });



            });
        });

        $(document).ready(function() {


            if ($(".select2_pelagem").length > 0) {
                $(".select2_pelagem").attr('data-live-search', 'true');

                $(".select2_pelagem").select2({
                    width: '100%'
                });
            }

            if ($(".select2_especie").length > 0) {
                $(".select2_especie").attr('data-live-search', 'true');

                $(".select2_especie").select2({
                    width: '100%'
                })
            }
        });
    </script>