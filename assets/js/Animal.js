$(document).ready(function () {
    $("#busca_animal").on('click', function () {
        var descr$ (document).ready (function () {
            $("#busca_animal").on('click', function () {
                var descricao_animal = $("#form_descricao_animal").val()
                var nro_ficha = $("#form_nro_ficha").val()
                var nro_chip = $("#form_nro_chip").val()

               

                var form_data = new FormData();
                form_data.append("form_descricao_animal", descricao_animal);
                form_data.append("nro_ficha", nro_ficha);
                form_data.append("nro_chip", nro_chip);

                $.ajax({
                    type: "POST",
                    url: "ajax_busca_animal.php",
                    data: form_data,
                    method: "POST",
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function () {
                        $("#modal_loading").modal('show');
                        $("#modelId").modal('hide');
                    },
                    success: function (response) {

                        if (response['info'] == 6) {
                            $("#status_cadastro").text("Cadastro Realizado com sucesso!");
                        }
                        if (response['info'] == 2) {
                            $("#status_cadastro").text("Dados do Responsavel não Inseridos no Formulário!");
                        }
                        if (response['info'] == 0) {
                            $("#status_cadastro").text('Falha no Cadastro!');
                        }

                       
                    },

                    error: function (response) {

                        location.reload(true);

                    }
                });

                $("#modelId").modal('hide');

            });

        });
        
        
        
        request.done(function (data) {

            if (data[0].resultado == 1) {

                local.find('#id_animal').val(data[0].id_animal);
                local.find('#nro_ficha').val(data[0].nro_ficha);
                local.find('#nro_chip').val(data[0].nro_chip);
                local.find('#pelagem').val(data[0].pelagem);
                local.find('#especie').val(data[0].especie);
                local.find('#observacao').val(data[0].observacao);
                
                
                var table_animal= '';                
                    
                $.each(data[0].animal , function(indice , animal){
                    table_animal += "<tr>";                                      
                    table_animal += "    <td style='width: 5px;'>";
                    table_animal += "        <input " + animal['nro_ficha'] + "-" + animal['nro_chip'] + "-" + animal['pelagem'] + "-" + animal['especie'] +"-" + animal['observacao'] +"'>";
                    table_animal += "    </td>";                           
                    table_animal += "    <td>";
                    table_animal += "</tr>";
                });

                local.find('table>tbody').html(table_animal);

            }
            else {
                console.log("ERRO77");

            }

        });




    });
});