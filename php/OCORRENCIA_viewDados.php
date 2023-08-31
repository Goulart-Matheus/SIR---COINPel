<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');


$where = "";
$where .= $form_protocolo   != "" ? " AND ot.protocolo  ilike '%" . $form_protocolo . "%'" : "";
$where .= $form_comunicante   != "" ? " AND o.nome_denunciante  ilike '%" . $form_comunicante . "%'" : "";
$where .= $form_email   != "" ? " AND o.email  ilike '%" . $form_email . "%'" : "";
$where .= $form_telefone   != "" ? " AND o.telefone_contato  ilike '%" . $form_telefone . "%'" : "";
$where .= $form_data   != "" ? " AND o.data = $form_data" : "";
$where .= $form_descricao   != "" ? " AND o.descricao  ilike '%" . $form_descricao . "%'" : "";
$where .= $form_endereco   != "" ? " AND o.endereco  ilike '%" . $form_endereco . "%'" : "";
$where .= $form_regiao_administrativa  != "" ? " AND ra.id_regiao_administrativa = $form_regiao_administrativa " : "";
$where .= $form_status     != "" ? " AND o.status ilike '%" . $form_status . "%'" : "";


$query->exec("SELECT distinct (o.id_ocorrencia), o.email, o.nome_denunciante,o.telefone_contato, o.data, o.hora, o.descricao, o.status,
o.id_microrregiao, o.endereco, mr.nome as microrregiao, ot.protocolo, ra.nome as regiao_administrativa,
(select id_ocorrencia_tramitacao from denuncias.ocorrencia_tramitacao  where o.id_ocorrencia = id_ocorrencia order by id_ocorrencia_tramitacao  DESC LIMIT 1)
FROM denuncias.ocorrencia as o left join denuncias.microrregiao as mr on o.id_microrregiao = mr.id_microrregiao 
left join denuncias.ocorrencia_tramitacao as ot on o.id_ocorrencia = ot.id_ocorrencia,
denuncias.regiao_administrativa as ra,
denuncias.tipo_ocorrencia as toc,  orgao as org
WHERE mr.id_regiao_administrativa = ra.id_regiao_administrativa
and ot.id_tipo_ocorrencia = toc.id_tipo_ocorrencia
and toc.id_orgao = org.id_orgao
and org.id_orgao = $_id_orgao " . $where);

// var_dump($query->sql);




// WHERE nome ilike '%" . $form_nome . "%' " . $condicao);

$query->proximo();


$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

if (!$sort_by)   $sort_by  = 13;
if (!$sort_dir)   $sort_dir = 1;

$sort->sortItem($sort_by, $sort_dir);

$report_subtitulo   = "Id_animal";
$report_periodo     = date('d/m/Y');

if ($print) {
    include('../class/class.report.php');

    unset($_GET['print']);

    $report_cabecalho = array(
        array('Código',     10, 0),
        array('Id_animal',     10, 1),
        array('Endereco_recolhimento',    190, 1),
        array('Id_airro',     19, 1),
        array('Observacao',    190, 1),
        array('Dt_entrada',     19, 1),
        array('Dt_retirada',     19, 1),
        array('Id_responsavel',     19, 1),
        array('Id_motivo',     19, 1),
        array('Id_urm',     19, 1),
        array('Valor',     19, 1),
        array('Nro_boleto',     19, 1),
        array('Situacao',     19, 1)

    );

    $query->exec($query->sql);

    $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);

    exit;
} else {
    $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);

    if (isset($remove)) {

        if (!isset($id_ocorrencia)) {

            $erro = 'Nenhum item selecionado!';
        } else {

            $querydel = new Query($bd);

            for ($c = 0; $c < sizeof($id_ocorrencia); $c++) {

                $where = array(0 => array('id_ocorrencia', $id_ocorrencia[$c]));
                $querydel->deleteTupla('denuncias.ocorrencia', $where);
            }

            unset($_POST['id_ocorrencia']);
        }
    }

    $paging->exec($query->sql . $sort->sort_sql);
}

include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Atendimento', 'fa-solid fa-house-chimney-medical', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);

$n = $paging->query->rows();
include('OCORRENCIA_view.php');

?>

<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <div class="card p-0">
            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="row">

                    <div class="col-12 col-md-4 offset-md-4 text-center">
                        <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                    </div>

                    <div class="col-12 col-md-4 text-center text-md-right mt-2 mt-md-0">

                        <!-- Geração de Relatório -->
                        <button type="button" class="btn btn-sm btn-green text-light">
                            <i class="fas fa-print"></i>
                        </button>

                        <!-- Abre Modal de Filtro -->
                        <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#OCORRENCIA_view">
                            <i class="fas fa-search"></i>
                        </button>

                    </div>

                </div>

            </div>
            <div class="card-body pt-0">
                <table class="table table-sm text-sm">
                    <thead>
                        <tr>
                            <th colspan="7">Resultados de
                                <span class="range-resultados"><? echo $paging->getResultadoInicial() . "-" . $paging->getResultadoFinal() . "</span> 
                                    sobre <span class='numero-paginas'>" . $paging->getRows() . "</span>"; ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>

                            <td style=' <? echo $sort->verifyItem(0); ?>' width="5px"></td>
                            <td style=' <? echo $sort->verifyItem(10); ?>' width="5px"> <? echo $sort->printItem(10, $sort->sort_dir, ''); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Protocolo'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(3); ?>'> <? echo $sort->printItem(3, $sort->sort_dir, 'Nome'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(2); ?>'> <? echo $sort->printItem(2, $sort->sort_dir, 'E-mail'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(4); ?>'> <? echo $sort->printItem(4, $sort->sort_dir, 'Telefone'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(5); ?>'> <? echo $sort->printItem(5, $sort->sort_dir, 'Data'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(6); ?>'> <? echo $sort->printItem(6, $sort->sort_dir, 'Hora'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(7); ?>'> <? echo $sort->printItem(7, $sort->sort_dir, 'Descrição'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(8); ?>'> <? echo $sort->printItem(8, $sort->sort_dir, 'Endereço'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(9); ?>'> <? echo $sort->printItem(9, $sort->sort_dir, 'Região Administrativa'); ?> </td>

                        </tr>

                        <?
                        while ($n--) {

                            $paging->query->proximo();

                            if ($query->record[7] > 0) {

                                $paging->query->record[7] = date('d/m/Y', strtotime($query->record[7]));
                            }

                            switch ($query->record['status']) {
                                case 'A':
                                    $situacao = 'Em Atendimento';
                                    $sit_class = 'text-warning';
                                    break;
                                case 'F':
                                    $situacao = 'Concluído';
                                    $sit_class = 'text-green';
                                    break;
                                case 'X':
                                    $situacao = 'Dados Recebidos';
                                    $sit_class = 'text-dark';
                                    break;
                                case 'N':
                                    $situacao = 'Dados Recebidos';
                                    $sit_class = 'text-dark';
                                    break;
                            }
                            $query_verifica_visualizado = new Query($bd);
                            $query_verifica_visualizado->exec("SELECT id_ocorrencia FROM denuncias.ocorrencia_orgao where id_orgao = $_id_orgao and id_ocorrencia = " . $query->record[0] . "");

                            if ($query_verifica_visualizado->rows() > 0) {
                                $classe_view = "table";
                            } else {
                                $classe_view = "alert-2";
                            }

                            $js_onclick = "OnClick=javascript:window.location=('OCORRENCIA_cover.php?id_ocorrencia=" . $paging->query->record[0] . "')";

                            echo "<tr class='entered $classe_view'>";

                            echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_ocorrencia[]' value=" . $paging->query->record[0] . "></td>";

                            echo "<td valign='top' " . $js_onclick . ">" . ("<i class='fas fa-circle $sit_class'</i>") . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record['protocolo'] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . formatarTelefone($paging->query->record[3]) . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" .  date('d/m/Y', strtotime($paging->query->record[4])) . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" .  ($paging->query->record[5] != '' ? $paging->query->record[5] : "-") . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[6] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" .  ($paging->query->record[9] != '' ? $paging->query->record[9] : "-") . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" .  $paging->query->record['regiao_administrativa'] . "</td>";

                            echo "</tr>";
                        }

                        ?>

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="12">

                                <span>Situação: </span>
                                <span><i class='fas fa-circle text-dark'></i> Nova Ocorrência</span>
                                <span><i class='fas fa-circle text-green'></i> Atendimento Finalizado</span>
                                <span><i class='fas fa-circle text-warning'></i> Em Atendimento</span>

                            </td>
                        </tr>

                        <tr>
                            <td colspan="12">
                                <div class="text-center pt-2">
                                    <? echo $paging->viewTableSlice(); ?>
                                </div>
                            </td>
                        </tr>

                    </tfoot>

                </table>

            </div>

            <div class="card-footer bg-light-2">
                <?
                if ($paging->query->rows()) {
                    $btns = array('selectAll', 'remove');
                    include('../includes/dashboard/footer_forms.php');
                }
                ?>

            </div>

        </div>

    </form>

</section>

<?
include_once('../includes/dashboard/footer.php');
include('DENUNCIA_view.php');

function formatarTelefone($telefone)
{
    // Remover caracteres indesejados
    $telefone = preg_replace("/[^0-9]/", "", $telefone);

    // Aplicar a máscara
    if (strlen($telefone) == 11) {
        // Formato para números de telefone com DDD (11 dígitos)
        $telefoneFormatado = preg_replace("/(\d{2})(\d{4,5})(\d{4})/", "$1 $2-$3", $telefone);
    } else {
        // Formato para números de telefone sem DDD (10 dígitos)
        $telefoneFormatado = preg_replace("/(\d{2})(\d{4})(\d{4})/", "$1 $2-$3", $telefone);
    }

    return $telefoneFormatado;
}
?>

<script type="text/javascript">
    $(document).ready(function() {

        if ($(".select2_id_regiao_administrativa").length > 0) {
            $(".select2_id_regiao_administrativa").attr('data-live-search', 'true');

            $(".select2_id_regiao_administrativa").select2({
                width: '100%'
            });
        }

        $('.telefone').on('input', function() {
            var telefone = $(this).val().replace(/\D/g, '');
            var tamanho = telefone.length;

            if (tamanho === 10) {
                $(this).val(telefone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3'));
            } else if (tamanho === 11) {
                $(this).val(telefone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3'));
            }
        });
    });
</script>