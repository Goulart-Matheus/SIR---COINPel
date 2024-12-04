<?

    include('../includes/session.php'               );
    include('../includes/variaveisAmbiente.php'     );

    $query->exec("SELECT id_orgao , descricao , orgao_gestor , sigla
                    FROM orgao 
                   WHERE (descricao ilike '%".$form_descricao."%' OR sigla ilike '%".$form_descricao."%')
                     AND id_cliente = $_id_cliente");

    $sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

    if(!$sort_by )   $sort_by  = 1;
    if(!$sort_dir)   $sort_dir = 0;

    $sort->sortItem($sort_by, $sort_dir);

    $report_subtitulo   = "Orgão";
    $report_periodo     = date('d/m/Y');

    if ($print)
    {
        include('../class/class.report.php');

        unset($_GET['print']);

        $report_cabecalho =array(
            array('Código'   ,     10 , 0),
            array('Descricao',     190, 1)
        );

        $query->exec($query->sql . $sort->sort_sql);

        $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);
        
        exit;
    } 
    else 
    {
        $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);

        if (isset($remove))
        {

            if (!isset($id_orgao))
            {
                
                $erro = 'Nenhum item selecionado!';
            }
            else
            {

                $querydel = new Query($bd);

                for ($c = 0; $c < sizeof($id_orgao); $c++)
                {

                    $where = array(0 => array('id_orgao', $id_orgao[$c]));
                    $querydel->deleteTupla('orgao', $where);

                }

                unset($_POST['id_orgao']);
            }
        }

        $paging->exec($query->sql . $sort->sort_sql);
    }

    include_once('../includes/dashboard/header.php');
    include('../class/class.tab.php');

    $tab = new Tab();

    $tab->setTab('Órgãos'       ,'fas fa-hotel'     , $_SERVER['PHP_SELF']);
    $tab->setTab('Novo Órgão'   ,'fas fa-plus'      , 'ORGAO_form.php'   );

    $tab->printTab($_SERVER['PHP_SELF']);

    $n =$paging->query->rows();

    // INCLUSÂO DO ARQUIVO VIEW COM A MODAL DE PESQUISA
    include 'ORGAO_view.php'

?>

    <section class="content">

        <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

            <div class="card p-0">

                <div class="card-header border-bottom-1 mb-3 bg-light-2">

                    <div class="row">

                        <div class="col-12 col-md-4 offset-md-4 text-center">
                            <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                        </div>

                        <div class="col-12 col-md-4 text-center text-md-right mt-2 mt-md-0">

                            <!-- Geração de Relatório -->
                            <a href="<?echo $_SERVER['PHP_SELF'];?>?print=1<?echo $paging->verificaVariaveis();?>" target="_new" class="btn btn-sm btn-green text-light">
                                <i class="fas fa-print"></i>
                            </a>

                            <!-- Abre Modal de Filtro -->
                            <button type="button" class="btn btn-sm btn-green text-light" data-toggle="modal" data-target="#ORGAO_view">
                                <i class="fas fa-search"></i>
                            </button>

                        </div>

                    </div>

                    <div class="row text-center">

                        <div class="col-12 col-sm-4 offset-sm-4">
                            <?
                                if(!$n)   { echo callException('Nenhum registro encontrado!', 2); }
                                
                                if($erro) { echo callException($erro, 1); }

                                if ($remove) {
                                    $querydel->commit();
                                    unset($_POST['remove']);
                                }
                                
                            ?>

                        </div>

                    </div>

                </div>

                <div class="card-body pt-0">

                    <table class="table table-sm text-sm">

                        <thead>

                            <tr>
                                <th colspan="4">

                                    Resultados de

                                    <span class="range-resultados"> 
                                        <? echo $paging->getResultadoInicial() . "-" . $paging->getResultadoFinal(); ?>
                                    </span>

                                    sobre

                                    <span class='numero-paginas'> 
                                        <? echo $paging->getRows(); ?>
                                    </span>

                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td style=' <? echo $sort->verifyItem(0); ?>' width="5px"></td>
                                <td style=' <? echo $sort->verifyItem(0); ?>'> <? echo $sort->printItem(0, $sort->sort_dir, 'Sigla'        ); ?> </td>
                                <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Descrição'    ); ?> </td>
                                <td style=' <? echo $sort->verifyItem(2); ?>'> <? echo $sort->printItem(2, $sort->sort_dir, 'Órgão Gestor' ); ?> </td>

                            </tr>

                            <?
                            
                                while ($n--) {

                                    $paging->query->proximo();

                                    $js_onclick = "OnClick=javascript:window.location=('ORGAO_edit.php?id_orgao=" . $paging->query->record[0] . "')";

                                    $gestor = $paging->query->record[2] == "S" ? "Sim" : "Não";
                                    
                                    echo "<tr class='entered'>";

                                        echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_orgao[]' value=" . $paging->query->record[0] ."></td>";
                                        echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                                        echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                                        echo "<td valign='middle' " . $js_onclick . ">" . $gestor                   . "</td>";
                                    
                                    echo "</tr>";
                                }

                            ?>

                        </tbody>

                        <tfoot>

                            <tr>
                                <td colspan="4">

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
                        if($paging->query->rows())
                        {
                            $btns = array('selectAll','remove');
                            include('../includes/dashboard/footer_forms.php');
                        } 
                    ?>
                </div>

            </div>

        </form>

    </section>

<? 
    include_once('../includes/dashboard/footer.php');
?>