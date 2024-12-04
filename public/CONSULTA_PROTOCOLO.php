<?

require_once("../class/Layout.php");

$layout = new Layout("Denúncias", " Secretaria Municipal de Desenvolvimento Rural ", "Consulta Comunicados");

echo $layout->getHeader();

?>

<!-- <div>
    <h4>
        Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC.
    </h4>
</div> -->

<div class="row">
    <div class="col-10"></div>
    <div class="col-2 text-end">
        <a href="index.php" class="btn border border-secondary">
            <span class="fas fa-backward text-secondary"></span>
            <span class="value text-secondary">Voltar</span>
            </button>
        </a>
    </div>
</div>

<div class="row">

    <div class="col-md-8 offset-md-2 p-0 pt-3" id="btns_option_pesquisa">

        <div class="row p-0">
            <div class="col-12 text-center mt-2">
                <p>
                    Para consultar a situação da comunicação informe abaixo o Protoloco:
                </p>
            </div>
        </div>

        <div class="row p-0 pt-3">

            <div class="input-group">

                <input type="text" name="form_protocolo" id="form_protocolo" class="form-control" placeholder="Digite seu Protoloco" maxlength="20" onkeypress="return event.charCode >= 48 && event.charCode <= 57">

                <div class="input-group-prepend">
                    <button class="btn btn-pelotas" type="button" id="btn_form_consulta"> Pesquisar <i class="fas fa-search"></i></button>
                </div>

            </div>

            <small>
                Informe os 20 caracteres do seu protocolo
            </small>

        </div>



    </div>

    <div class="col-md-8 offset-2 pt-5" id="container_result">
        <div class="row list-group-item active" style="background-color: #F4A14E;">
            Resultados da Busca
        </div>
        <div class="table-responsive" style="height: 380px;">
            <table class="table table-striped table-hover">
                <thead class="overflowremove">
                    <tr class="py-2 border-bottom text-sm">
                        <th class="col-12 col-md-2">Situação</th>
                        <th class="col-12 col-md-3">Protocolo</th>
                        <th class="col-12 col-md-5">Descrição</th>
                        <th class="col-12 col-md-1">Data</th>
                        <th class="col-12 col-md-1">Hora</th>
                    </tr>
                </thead>
                <tbody id="result" style="max-height: 200px; overflow-y: auto;">
                    <!-- The table data will be populated dynamically by jQuery AJAX -->
                </tbody>
            </table>
        </div>
    </div>


</div>

<?

echo $layout->getFooter();

?>

<script>
    $("#btn_form_consulta").on("click", function() {

        var protocolo = $('#form_protocolo').val();

        $.ajax({
            type: "post",
            url: "AJAX_DADOS_DENUNCIA.php",
            data: {
                "protocolo": protocolo
            },
            dataType: "json",
            success: function(response) {
                var ret = ''; // Inicializa a variável para armazenar o conteúdo da tabela
                var sit_class = ''; // Defina a classe para a situação aqui
                var situacao = ''; // Defina a situação aqui                         

                $.each(response, function(i, obj) {

                    if (obj.status == 1) {

                        switch (obj.situacao) {
                            case 'A':
                                situacao = 'Em Atendimento';
                                sit_class = 'text-info';
                                break;
                            case 'F':
                                situacao = 'Concluído';
                                sit_class = 'text-green';
                                break;
                            case 'X':
                                situacao = 'Dados Recebidos';
                                sit_class = 'text-info';
                                break;
                            case 'N':
                                situacao = 'Dados Recebidos';
                                sit_class = 'text-info';
                                break;
                        }
                        // Dividindo a string em partes
                        const partesData = obj.data.split("-");

                        const dataFormatada = `${partesData[2]}-${partesData[1]}-${partesData[0]}`;


                        ret += '<tr>';
                        ret += '    <td class="col-12 col-md-2" style="font-size: 0.75em;">' + situacao + '</td>';
                        ret += '    <td class="col-12 col-md-3" style="font-size: 0.75em;">';
                        ret += '        <a href="PROTOCOLO_DETALHES.php?id_protocolo=' + obj.protocolo + '">' + obj.protocolo + '</a>';
                        ret += '    </td>';
                        ret += '    <td class="col-12 col-md-5" style="font-size: 0.75em;">' + obj.descricao + '</td>';
                        ret += '    <td class="col-12 col-md-1" style="font-size: 0.75em;">' + dataFormatada + '</td>';
                        ret += '    <td class="col-12 col-md-1" style="font-size: 0.75em;">' + obj.hora + '</td>';
                        ret += '</tr>';
                    } else {
                        ret += '<tr>';
                        ret += '    <td colspan="5" class="col-12 text-center">Nenhum registro encontrado para a pesquisa. Tente novamente</td>';
                        ret += '</tr>';
                    }

                });


                document.getElementById("result").innerHTML = ret;
            },
            error: function(response) {

                var ret = '';
                ret += '<div class="col-12 border-bottom">';
                ret += '    <div class="row">';
                ret += '        <div class="col-12 text-center">';
                ret += '            Nenhum registro encontrado para a pesquisa. Tente novamente';
                ret += '        </div>';
                ret += '    </div>';
                ret += '</div>';

                document.getElementById("result").innerHTML = ret;

            }
        });

    });
</script>