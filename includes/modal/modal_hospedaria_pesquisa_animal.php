<div class="modal fade text-left" id="PESQUISA_ANIMAL_modal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header bg-light-2">
                <h5 class="modal-title">
                    <i class="fas fa-dog text-green"></i>
                    Pesquisar Animal
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_ficha"></span> Numero Ficha</label>
                        <input type="text" class="form-control select2_ficha_animal_pesquisa" name="form_nro_ficha" id="form_nro_ficha_ajax" maxlength="100" value="<? if ($erro) echo $form_nro_ficha; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_chip"></span> Numero Chip</label>
                        <input type="text" class="form-control select2_nro_chip_pesquisa" name="form_nro_chip" id="form_nro_chip_ajax" maxlength="100" value="<? if ($erro) echo $form_nro_chip; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_sexo"></span> Sexo</label>
                        <select name="form_sexo" id="form_sexo_ajax" class="form-control">
                            <option value="">Selecione o sexo:</option>
                            <option value="M">Macho</option>
                            <option value="F">Fêmea</option>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o sexo do animal.
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_pelagem"></span>Pelagem</label>
                        <select name="form_id_pelagem" id="form_id_pelagem_ajax" class="form-control select2_pelagem_pesquisa">
                            <?
                            $form_elemento = $erro ? $form_id_pelagem : "";
                            include("../includes/inc_select_pelagem.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha a Pelagem
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_especie"></span>Espécie</label>
                        <select name="form_id_especie" id="form_id_especie_ajax" class="form-control">
                            <?
                            $form_elemento = $erro ? $form_id_especie : "";
                            include("../includes/inc_select_especie.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha Especie
                        </div>
                    </div>



                </div>

                <div id="retorna_animal_ajax" name="retorna_animal_ajax"></div>


            </div>

            <div class="modal-footer bg-light-2 text-center">
                <div class="row col-12 p-0">
                    <div class="col-md-12 text-right">
                        <button type="button" id="btn_ajax_pesquisa_animal" name="btn_ajax_pesquisa_animal" class="btn btn-light btn-sm btn_ajax_pesquisa_animal">
                            <i class="fa-solid fa-filter text-green"></i>
                            Filtrar
                        </button>
                    </div>
                    <!---
                    <div class="col-md-6 text-right">
                        <button type="button" id="btn_ajax_vincula_animal_hospedaria" name="btn_ajax_vincula_animal_hospedaria" class="btn  btn-light btn-sm btn_ajax_vincula_animal_hospedaria">
                            <i class="fa-solid fa-filter text-green"></i>
                            Vincular
                        </button>
                    </div>

                    --->
                </div>

                <select hidden name="form_id_urm2" id="form_id_urm2" class="form-control">
                    <?
                    $form_elemento = $erro ? $form_id_urm2 : "";
                    include("../includes/inc_select_urm.php"); ?>
                </select>

            </div>

        </div>
    </div>
</div>

<?
$table_body = "";

$table_body .= "<tr class='entered'>";
$table_body .= "<td><button class='btn_vincula_animal2' type='button'  data-id-animal='37'><i class='text-green fa-solid fa-plus'></i></button></td>";


?>

<script type="text/javascript">
    $(document).ready(function() {

        $("#form_id_urm2").prop("selectedIndex", 1).val();
        $("#btn_ajax_pesquisa_animal").on('click', function() {
            var nro_ficha = $("#form_nro_ficha_ajax").val();
            var nro_chip = $('#form_nro_chip_ajax').val();
            var sexo = $('#form_sexo_ajax').val();
            var id_pelagem = $('#form_id_pelagem_ajax').val();
            var id_especie = $('#form_id_especie_ajax').val();


            $.ajax({
                type: 'POST',
                url: '../../includes/ajax_busca_animal.php',
                data: {
                    'nro_ficha': nro_ficha,
                    'nro_chip': nro_chip,
                    'id_especie': id_especie,
                    'id_pelagem': id_pelagem,
                    'sexo': sexo,

                },
                beforeSend: function() {

                },
                success: function(ret) {



                    if (ret[0]['resultado'] == '1') {


                        var monta_tabela = "";
                        monta_tabela += "<div class='card-body p-0 m-0'>";
                        monta_tabela += "<table class='table-responsive table-sm text-sm text-left' style='max-height:200px' id='btn_redefine'>";
                        monta_tabela += "<thead class='p-0 text-left'>";
                        monta_tabela += "<tr>";
                        monta_tabela += "<th style='width: 250px; background-color:#F3EFE7;'>Numero da Ficha:</th>";
                        monta_tabela += "<th style='width: 180px; background-color:#F3EFE7;'>Numero do Chip:</th>";
                        monta_tabela += "<th style='width: 180px; background-color:#F3EFE7;'>Espécie:</th>";
                        monta_tabela += "<th style='width: 240px; background-color:#F3EFE7;'>Pelagem:</th>";
                        monta_tabela += "<th style='width: 200px; background-color:#F3EFE7;'>Sexo:</th>";
                        monta_tabela += "<th style='width: 30px;   background-color:#F3EFE7; '></th>";
                        monta_tabela += "</tr>";
                        monta_tabela += "</tread>";

                        monta_tabela += "<tbody p-0>";

                        $.each(ret, function(indice, nome) {

                            monta_tabela += "<tr class='entered'>";
                            monta_tabela += "<td style='width: 250px;'>" + ret[indice].nro_ficha + "</td>";
                            monta_tabela += "<td style='width: 180px;'>" + ret[indice].nro_chip + "</td>";
                            monta_tabela += "<td style='width: 180px;'>" + ret[indice].especie + "</td>";
                            monta_tabela += "<td style='width: 240px;'>" + ret[indice].pelagem + "</td>";
                            monta_tabela += "<td style='width: 200px;'>" + ret[indice].sexo + "</td>";
                            monta_tabela += "<td style='width: 30px;'>";
                            monta_tabela += "<a class='btn_vincula_animal btn-sm' type='button' data-id-animal='" + ret[indice].id_animal + "'><i class='text-green fa-solid fa-plus'></i></a>";
                            monta_tabela += "</td>";
                            monta_tabela += "</tr>";


                        });

                        monta_tabela += "</tbody>";
                        monta_tabela += "<tfoot>";
                        monta_tabela += "<tr>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "<td></td>";
                        monta_tabela += "</tr>";
                        monta_tabela += "</tfoot>";
                        monta_tabela += " </table>";
                        monta_tabela += " </div>";


                        $("#retorna_animal_ajax").html(monta_tabela).addClass('bg-ligth').removeClass('bg-danger');



                        $('.btn_vincula_animal').on('click', function() {

                            var id_urm = $("#form_id_urm2").val();
                            var form_vincula_animal = $(this).attr('data-id-animal');

                            $.ajax({
                                type: 'POST',
                                url: '../../includes/ajax_hospedagem_vincula_animal.php',
                                data: {
                                    'id_animal': form_vincula_animal,
                                    'id_urm': id_urm,
                                },
                                beforeSend: function() {

                                },
                                success: function(ret) {

                                    if (ret.resultado = 1) {
                                        
                                        
                                        $("#PESQUISA_ANIMAL_modal").modal('hide');
                                        $("#form_id_animal").val(ret[0]['id_animal']);
                                        $("#form_nro_ficha").val(ret[0]['nro_ficha']);
                                        $("#form_nro_chip").val(ret[0]['nro_chip']);
                                        
                                        if(ret[0]['sexo'] == 'M'){
                                            ret[0]['sexo'] = "Macho";
                                        }else{
                                            ret[0]['sexo'] = "Femêa";
                                        }
                                       // $("#form_nro_chip").prop("selectedIndex", 1).val(ret[0]['nro_chip']).select2();
                                       // $("#form_nro_ficha").prop("selectedIndex", 1).val(ret[0]['nro_ficha']).select2();
                                        $("#form_valor").val(ret[0]['valor']);
                                        $("#form_reincidencias").val(ret[0]['reincidencias']);
                                        //$("#form_dados_animal").val("Especie: "+ ret[0]['especie']+ " Pelagem: "+ret[0]['pelagem'] +"- Sexo: " +ret[0]['sexo']);
                                        $("#form_especie").val(ret[0]['especie']);
                                        $("#form_pelagem").val(ret[0]['pelagem']);
                                        $("#form_sexo").val(ret[0]['sexo']);
                                        if (ret[0]['id_responsavel'] != 0) {
                                           // $("#form_id_responsavel").prop("selectedIndex", 1).val(ret[0]['id_responsavel']).select2();
                                        } else {
                                           // $("#form_id_responsavel").prop("selectedIndex", 0).select2();
                                        }
                                        
                                        
                                    }

                                }
                            });

                        });


                    } else {

                        var monta_tabela = "";
                        monta_tabela += "<div class='col-12 p-0 m-0'>";
                        monta_tabela += "<h5 class= 'col-12 text-center'>Animal não encontrado</h5>"
                        monta_tabela += " </div>";



                        monta_add = "";
                        monta_add += "<div class='card-header p-0 m-0'>";
                        monta_add += "<h5 class='text-center'>Animal não encontrado</h5>";

                        monta_add += " </div>";
                        monta_add += "<div class='card-body text-center p-0 m-0 my-2'>";
                        monta_add += " <a title='Adicionar Animal' class='btn btn-success text-light bg-green' data-toggle='modal' data-target='#ANIMAL_modal'>+ Adicionar animal <i class='fa fa-dog text-light'></i></a>";
                        monta_add += "</div>";

                        $("#retorna_animal_ajax").html(monta_add).addClass('bg-white').removeClass('bg-green');



                    }
                },
                error: function(erro) {
                    console.log(erro);
                }
            });



        });



    });
</script>