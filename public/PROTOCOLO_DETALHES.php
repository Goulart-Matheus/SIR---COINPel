<?
include("../includes/connection.php");

require_once("../class/Layout.php");
$layout = new Layout("Denúncias", " Secretaria Municipal de Desenvolvimento Rural ", "Histórico do atendimento");

extract($_GET);
extract($_POST);

echo $layout->getHeader();

?>

<div>
    <div class="row">
        <div class="col-10">
            <h4>
                Protocolo: <?= $id_protocolo ?>
            </h4>
        </div>
        <div class="col-2 text-end">

            <button class="btn btn-light btn_print" onclick="printPage()"><i class="fa-solid fa-print"></i></button>

        </div>
    </div>
</div>




<div class="row botao_voltar pt-3">
    <div class="col-10"></div>
    <div class="col-2 text-end">
        <button class="btn border border-secondary" id="voltar">
            <span class="fas fa-backward text-secondary"></span>
            <span class="value text-secondary">Voltar</span>
        </button>
    </div>
</div>



<div class="row" style="height: 480px;">

    <div class="col-12 col-md-12 pt-5" id="container_print">

        <div class="row list-group-item active" style="background-color: #F4A14E;">
            Tramitação
        </div>

        <div class="table-responsive" style="height: 380px;">
            <table class="table table-striped table-hover">
                <thead style="position: sticky; top: 0; z-index: 1;">
                    <tr class="py-2 border-bottom text-sm">
                        <th class="col-12 col-md-2">Situação</th>
                        <th class="col-12 col-md-3">Tipo de Ocorrência</th>
                        <th class="col-12 col-md-4">Descrição</th>
                        <th class="col-12 col-md-2">Data</th>
                        <th class="col-12 col-md-1">Hora</th>
                    </tr>
                </thead>
                <tbody id="result" style="max-height: 200px; overflow-y: auto;">

                    <?

                    $query_tramitacao = new Query($bd);

                    $query_tramitacao->exec("SELECT (select toc.descricao from denuncias.tipo_ocorrencia as toc where ot.id_tipo_ocorrencia = toc.id_tipo_ocorrencia) as tipo_ocorrencia_descricao, ot.descricao, ot.status, ot.id_ocorrencia_tramitacao, (select org.descricao from orgao as org, denuncias.tipo_ocorrencia as toc where org.id_orgao = toc.id_orgao and ot.id_tipo_ocorrencia = toc.id_tipo_ocorrencia) as orgao, (select descricao from denuncias.ocorrencia where id_ocorrencia = ot.id_ocorrencia) as relato, ot.id_tipo_ocorrencia, ot.hr_alteracao as hora,login,dt_alteracao as data from denuncias.ocorrencia_tramitacao as ot where ot.protocolo = '" . $id_protocolo . "' order by ot.id_ocorrencia_tramitacao asc");

                    $n_2 = $query_tramitacao->rows();

                    $numero_etapa = $n_2;

                    while ($n_2--) {


                        $query_tramitacao->proximo();
                        $id_ocorrencia_tramitacao = $query_tramitacao->record['id_ocorrencia_tramitacao'];
                        $descricao_tramitacao = $query_tramitacao->record['descricao'];

                        switch ($query_tramitacao->record['status']) {
                            case 'A':
                                $situacao = 'Em Atendimento';
                                $sit_class = 'text-info';
                                break;
                            case 'F':
                                $situacao = 'Concluído';
                                $sit_class = 'text-green';
                                break;
                            case 'X':
                                $situacao = 'Dados Recebidos';
                                $sit_class = 'text-info';
                                break;
                            case 'N':
                                $situacao = 'Dados Recebidos';
                                $sit_class = 'text-info';
                                break;
                        }

                    ?>

                        <tr class="py-2 border-bottom text-sm overflow-auto" style="max-height: 300px;">
                            <td class="col-12 col-md-2" style="font-size: 0.70em">
                                <?php
                                echo $situacao;
                                ?>
                            </td>
                            <td class="col-12 col-md-3" style="font-size: 0.70em">
                                <?php
                                echo $query_tramitacao->record['tipo_ocorrencia_descricao'];
                                ?></p>
                            </td>
                            <td class="col-12 col-md-4" style="font-size: 0.70em">
                                <?php
                                echo  $descricao_tramitacao = $query_tramitacao->record['descricao'];
                                ?>
                            </td>
                            <td class="col-12 col-md-2" style="font-size: 0.70em">
                                <?php
                                echo date('d/m/Y', strtotime($query_tramitacao->record['data']));
                                ?>
                            </td>
                            <td class="col-12 col-md-1" style="font-size: 0.70em">
                                <?php
                                echo $query_tramitacao->record['hora'];
                                ?>
                            </td>
                        </tr>


                    <?
                    }
                    ?>

                </tbody>
            </table>
        </div>

    </div>

</div>


<div class="row" style="position: absolute; bottom:0">
    <?

    echo $layout->getFooter();

    ?>
</div>

<script>
    $("#voltar").on("click", function() {
        window.history.back();
    });

    $("#container_print").on("click", function() {
        print.back();
    });

    function printPage() {
        $(".botao_voltar").addClass('d-none')
        $(".btn_print").addClass('d-none')
        $("footer").addClass('d-none')
        window.print();
        $(".botao_voltar").removeClass('d-none')
        $(".btn_print").removeClass('d-none')
        $("footer").removeClass('d-none')
    }
</script>