$(document).ready(function () {

    $("#cadastraAnimal").on('click', function () {


        //var id_animal               = $("#id_animal").val()
        var nro_chip                = $("#form_nro_chip").val()
        var nro_ficha               = $("#form_nro_ficha").val()
        var id_pelagem              = $("#form_pelagem").val()
        var id_especie              = $("#form_especie").val()
        var sexo                    = $("#form_sexo").val()
        var observação              = $("#form_observação").val()
                   
        var update                  = 0;


        var form_animal=new FormData();

        form_animal.append("form_nro_chip"    , nro_chip);
        form_animal.append("form_nro_ficha"   , nro_ficha);
        form_animal.append("form_pelagem"     , id_pelagem);
        form_animal.append("form_especie"     , id_especie);
        form_animal.append("form_sexo"        , sexo);               
        form_animal.append("form_observação"  , observação);
        form_animal.append("update"           , update);
        
       


        $.ajax({
            type: "POST",
            url: "ajax_busca_animal.php",
            data: form_animal,
            
            method:"POST",
            contentType: false,
            processData:false,
            cache:false,
            beforeSend: function(){
                $("#modal_loading").modal('show');
                $("#modelId").modal('hide');
            },
            
            success: function (response) {

                if (response['info'] == 6) {
                    $("#status_cadastro").text("Cadastro do a com sucesso!");
                }
                
                if (response['info'] == 0) {
                    $("#status_cadastro").text('Falha no Cadastro!');
                }

                location.reload(true);


            },

            error: function (response) {

                location.reload(true);

            }
        });

        $("#modelId").modal('hide');

    });



    $(".edit_animal_button").on('click', function (e) {


        e.preventDefault();

        var id_animal = $(this).attr('id_animal');

        var request = $.ajax({
            type: "POST",
            url: "ajax_busca_animal.php",
            data: { "id_animal": id_animal },
            dataType: "JSON",
            beforeSend: function(){

                $("#modal_loading").modal('show');                
            },
            success: function (response) {

                $("#modal_loading").modal('hide');  
            }
        });

        var local = $("#edit_animal_modal");

        request.done(function (data) {

            if (data[0].resultado == 1) {

                local.find('#id_animal_update').val(data[0].id_animal);
                local.find('#form_nro_chip_update').val(data[0].form_nro_chip);
                local.find('#form_nro_ficha_update').val(data[0].form_nro_ficha);
                local.find('#form_pelagem_update').val(data[0].form_pelagem);
                local.find('#form_especie_update').val(data[0].form_especie);
                local.find('#form_sexo_update').val(data[0].form_sexo);
                local.find('#form_observacao_update').val(data[0].form_observação);
                
                
                var table_animal = '';                
                    
                $.each(data[0].animal , function(indice , animal){
                    table_animal += "<tr>";                                      
                    table_animal += "    <td style='width: 5px;'>";
                    table_animal += "        <input type='checkbox' name='form_remove_animal[]' value='" + animal['nro_chip'] + "-" + animal['nro_ficha'] + "-"
                                                 + animal['id_pelagem'] + "-" + animal['sexo'] +  "-" + animal['observacao']+"'>";
                    table_animal += "    </td>";                           
                    table_animal += "</tr>";
                });

                local.find('table>tbody').html(table_animal);

            }
            else {
                console.log("ERRO77");

            }

        });

        $("#edit_animal_modal").modal('show');


    });



    $("#editaAnimal").on('click', function () {

        var nro_chip                        = $("#form_nro_chip_update").val()
        var nro_ficha                       = $("#form_nro_ficha_update").val()
        var id_pelagem                      = $("#form_pelagem_update").val()
        var id_especie                      = $("#form_especie_update").val()
        var sexo                            = $("#form_sexo_update").val()
        var observação                      = $("#form_observação_update").val() 
        var update                          = 1;
        var form_remove_animal              = [];
        $.each($("input[name='form_remove_animal[]']:checked"), function(){
            form_remove_animal.push($(this).val());
        });
        

        var form_animal=new FormData();
        form_animal.append("nro_chip"                     , nro_chip);
        form_animal.append("nro_ficha"                    , nro_ficha);
        form_animal.append("id_pelagem"                   , id_pelagem);
        form_animal.append("id_especie"                   , id_especie);
        form_animal.append("sexo"                         , sexo );
        form_animal.append("observação"                   , observação);
        form_animal.append("form_remove_animal"           , form_remove_animal);
        form_animal.append("update"                       , update);
        
       

        $.ajax({
            type: "POST",
            url: "ajax_busca_animal.php",
            data: form_animal,            
            method:"POST",
            contentType: false,
            processData:false,
            cache:false,     
            beforeSend: function(){
                $("#modal_loading").modal('show');
                $("#edit_animal_modal").modal('hide');
            },
            success: function (response) {

                if (response['info'] == 6) {
                    $("#status_cadastro").text("Cadastro Realizado com sucesso!");
                }
                
                if (response['info'] == 0) {
                    $("#status_cadastro").text('Falha no Cadastro!A');
                }
                location.reload(true);

            },

            error: function (response) {

                $("#status_cadastro").text('Falha no Cadastro!B');

            }
        });
    });


  
});
