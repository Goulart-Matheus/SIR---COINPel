$(document).ready(function () {
    $("#cadastraResponsavel").on('click', function () {
        var descr$(document).ready(function () {
            $("#cadastraResponsavel").on('click', function () {
                var descricao_responsavel = $("#form_descricao_responsavel").val()
                var cpf = $("#form_cpf").val()
                var rg = $("#form_rg").val()

                // var form_arquivo            = $("#form_arquivo_relatorio").prop('files')[0]       
                //var update                  = 0;

                var form_data = new FormData();
                form_data.append("descricao_responsavel", descricao_responsavel);
                form_data.append("cpf", cpf);
                form_data.append("rg", rg);

                $.ajax({
                    type: "POST",
                    url: "ajax_busca_dados_responsavel.php",

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
                            $("#status_cadastro").text('Falha no Cadastro!A');
                        }

                        //location.reload(true);
                    },

                    error: function (response) {

                        location.reload(true);

                    }
                });

                $("#modelId").modal('hide');

            });

        });
    });
});
