<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');


$query->exec("SELECT distinct(o.id_ocorrencia), o.email, o.nome_denunciante,o.telefone_contato, o.data, o.hora, o.descricao, o.status,
                     o.id_microrregiao, o.endereco, ra.nome as regiao_administrativa, ot.protocolo
                     FROM denuncias.ocorrencia as o, denuncias.regiao_administrativa as ra, denuncias.ocorrencia_tramitacao as ot
                     WHERE o.id_regiao_administrativa = ra.id_regiao_administrativa and
                    o.id_ocorrencia = ot.id_ocorrencia and
                    o.status = 'N'");


// WHERE nome ilike '%" . $form_nome . "%' " . $condicao);

$query->proximo();


$sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

if (!$sort_by)   $sort_by  = 1;
if (!$sort_dir)   $sort_dir = 0;

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

    $query->exec($query->sql . $sort->sort_sql);

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

$tab->setTab('Novas Ocorrências', 'fas fa-bell', $_SERVER['PHP_SELF']);

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


                            $js_onclick = "OnClick=javascript:window.location=('OCORRENCIA_cover.php?id_ocorrencia=" . $paging->query->record[0] . "')";

                            echo "<tr class='entered'>";

                            echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_ocorrencia[]' value=" . $paging->query->record[0] . "></td>";

                            echo "<td valign='top'    " . $js_onclick . ">" . ($paging->query->record['status'] == 'N' ? "<i class='fas fa-circle text-dark'</i>" : " ") . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record['protocolo'] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record['nome_denunciante'] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record['email'] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . formatarTelefone($paging->query->record['telefone_contato']) . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . date('d/m/Y', strtotime($paging->query->record['data'])) . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . ($paging->query->record['hora'] != '' ? $paging->query->record['hora'] : "-") . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record['descricao'] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . ($paging->query->record['endereco'] != '' ? $paging->query->record[9] : "-") . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" .  $paging->query->record['regiao_administrativa'] . "</td>";

                            echo "</tr>";
                        }

                        ?>

                    </tbody>

                    <tfoot>

                        <tr>
                            <td colspan="12">

                                <span>Situação: </span>
                                <span><i class='fas fa-circle text-green'></i>Nova Ocorrencia</span>

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