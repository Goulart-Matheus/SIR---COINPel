$(document).ready(function () {

    if($("div#campo_dinamico").length){

        $(document).on('click','a#novo_campo_dinamico',function(e){

            e.preventDefault();

            var conteudo = $(this).parents("div#campo_dinamico").html();

            if(conteudo.indexOf("select") > -1){

                $(this).parents("div#container_dinamico").append('<div class="input-group ml-0 mb-2" id="campo_dinamico">' + conteudo.replace(/selected="selected"/g," ").replace(/selected=""/g," ") + '</div>');
                $("div#campo_dinamico").last().find('input#form_tipo_contato,input#form_principal,').val('');
                $("div#campo_dinamico").last().find('label').text('Selecione um Arquivo');

            } else {

                $(this).parents("div#container_dinamico").append('<div class="input-group ml-0 mb-2" id="campo_dinamico">' + conteudo + '</div>');

                $("div#campo_dinamico").last().find('input#form_tipo_contato,input#form_principal,').val('');
                $("div#campo_dinamico").last().find('label').text('Selecione um Arquivo');

            }

            $(this).removeClass('btn-success').addClass('btn-danger').html('x').attr('id','remove_campo_dinamico');

        });

        $(document).on('click','a#remove_campo_dinamico',function(e){

            e.preventDefault();

            $(this).parents("div#campo_dinamico").remove();


        });

    }



});
