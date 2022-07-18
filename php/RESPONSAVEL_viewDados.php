<?

    include('../includes/session.php');
    include('../includes/variaveisAmbiente.php');

        $where  = "";
        if($form_mascara!=""){
            $where.=" and r.cpf ilike '{$form_mascara}' ";
        }
        if($form_rg!=""){
            $where.= " and r.rg ilike '{$form_rg}'";
        }
       
         
    $query->exec("SELECT
                        r.id_responsavel,
                        r.nome,
                        r.cpf,
                        r.rg,
                        r.dt_nascimento,
                        r.endereco,
                        b.descricao,
                        rc.valor_contato,
                        rc.principal
                FROM
                        responsavel r,
                        bairro b,
                        tipo_contato tc,
                        responsavel_contato rc
                WHERE
                    r.nome ilike '%" . $form_responsavel . "%'
                   
                and 
                    b.id_bairro =r.id_bairro 
                and
                    rc.id_responsavel = r.id_responsavel
                and 
                    rc.id_tipo_contato = tc.id_tipo_contato                

                ".$where
      
    );

    $sort = new Sort($query, $sort_icon, $sort_dirname, $sort_style);

    if (!$sort_by)   $sort_by  = 1;
    if (!$sort_dir)   $sort_dir = 0;

    $sort->sortItem($sort_by, $sort_dir);

    $report_subtitulo   = "Descrição";
    $report_periodo     = date('d/m/Y');

    if ($print) {
        include('../class/class.report.php');

        unset($_GET['print']);

        $report_cabecalho = array(
            array('Código',     10, 0),
            array('Descricao',     190, 1)
        );

        $query->exec($query->sql . $sort->sort_sql);

        $report = new PDF($query, $report_titulo, $report_subtitulo, $report_periodo, $report_cabecalho, $report_orientation, $report_unit, $report_format, $report_flag);

        exit;
    } else {
        $paging = new Paging($query, $paging_maxres, $paging_maxlink, $paging_link, $paging_page, $paging_flag);

        if (isset($remove)) {

            if (!isset($id_responsavel)) {

                $erro = 'Nenhum item selecionado!';
            } else {

                $querydel = new Query($bd);

                for ($c = 0; $c < sizeof($id_responsavel); $c++) {
                    
                   
                    $query->exec ("SELECT  rc.id_responsavel_contato, rc.id_responsavel,rc.id_tipo_contato,rc.valor_contato,rc.principal,
                                           r.nome,r.cpf,r.rg,r.dt_nascimento 
                                  FROM   responsavel_contato rc, responsavel r ,animal_responsavel ar
                                  WHERE  rc.id_responsavel = ". $id_responsavel[$c],
                                  "AND   r.id_responsavel =". $id_responsavel[$c],
                                  "AND  ar.id_responsavel =".$id_responsavel[$c],

                                   
                                
                                 ); 

                        
                    if($query->rows() > 0)
                    {
                        $n = $query->rows();

                        while($n --)
                        {

                                                       
                            $where = array(0 => array('id_responsavel', $id_responsavel[$c]));
                            $querydel->deleteTupla('animal_responsavel', $where);

                            $where = array(0 => array('id_responsavel', $id_responsavel[$c]));
                            $querydel->deleteTupla('responsavel_contato', $where);

                            
                        };    
                            
                        $where = array(0 => array('id_responsavel', $id_responsavel[$c]));
                        $querydel->deleteTupla('responsavel', $where);
      
                    };
                    

                    
                    
                }

                unset($_POST['id_responsavel']);
            }
        }

        $paging->exec($query->sql . $sort->sort_sql);
    }

    include_once('../includes/dashboard/header.php');
    include('../class/class.tab.php');

    $tab = new Tab();

    $tab->setTab('Adicionar', 'fas fa-plus', 'RESPONSAVEL_form.php');
    $tab->setTab('Pesquisar', 'fas fa-search', 'RESPONSAVEL_view.php');
    $tab->setTab('Gerenciar', 'fas fa-cog', $_SERVER['PHP_SELF']);

    $tab->printTab($_SERVER['PHP_SELF']);

    $n = $paging->query->rows();

    ?>

    <section class="content">

        <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

            <div class="card p-0">

                <div class="card-header border-bottom-1 mb-3 bg-light-2">

                    <div class="text-center">
                        <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                    </div>

                    <div class="row text-center">

                        <div class="col-12 col-sm-4 offset-sm-4">
                            <?
                            if (!$n) {
                                echo callException('Nenhum registro encontrado!', 2);
                            }

                            if ($erro) {
                                echo callException($erro, 1);
                            }

                            if ($remove) {
                                $querydel->commit();
                                unset($_POST['remove']);
                            }

                            ?>

                        </div>

                    </div>

                </div>

                <div class="card-body pt-0">

                    <table class="table table-striped responsive">

                        <thead>

                            <tr>
                                <th colspan="2">

                                    Resultados de

                                    <span class="range-resultados">
                                        <? echo $paging->getResultadoInicial() . "-" . $paging->getResultadoFinal(); ?>
                                    </span>

                                    sobre

                                    <span class='numero-paginas'>
                                        <? echo $paging->getRows(); ?>
                                    </span>

                                    <a href="<? echo $_SERVER['PHP_SELF']; ?>?print=1<? echo $paging->verificaVariaveis(); ?>" target="_new">
                                        <i class="fas fa-print"></i>
                                    </a>

                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td width="5px"></td>
                                <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Nome: '); ?> </td>
                                <td style=' <? echo $sort->verifyItem(2); ?>'> <? echo $sort->printItem(2, $sort->sort_dir, 'CPF: '); ?> </td>
                                <td style=' <? echo $sort->verifyItem(3); ?>'> <? echo $sort->printItem(3, $sort->sort_dir, 'RG: '); ?> </td>
                                <td style=' <? echo $sort->verifyItem(4); ?>'> <? echo $sort->printItem(4, $sort->sort_dir, 'Data de nascimento: '); ?> </td>
                                <td style=' <? echo $sort->verifyItem(5); ?>'> <? echo $sort->printItem(5, $sort->sort_dir, 'Endereço: '); ?> </td>
                                <td style=' <? echo $sort->verifyItem(6); ?>'> <? echo $sort->printItem(6, $sort->sort_dir, 'Bairro: '); ?> </td>
                                <td style=' <? echo $sort->verifyItem(7); ?>'> <? echo $sort->printItem(7, $sort->sort_dir, 'Contato '); ?> </td>
                                <td style=' <? echo $sort->verifyItem(8); ?>'> <? echo $sort->printItem(8, $sort->sort_dir, 'Principal: '); ?> </td>

                            </tr>

                            <?

                            while ($n--) {

                                $paging->query->proximo();

                                $js_onclick = "OnClick=javascript:window.location=('RESPONSAVEL_cover.php?id_responsavel=" . $paging->query->record[0] . "')";

                                echo "<tr>";

                                echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_responsavel[]' value=" . $paging->query->record[0] . "></td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[6] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[7] . "</td>";
                                echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[8] . "</td>";
                                echo "</tr>";
                            }

                            ?>

                        </tbody>

                        <tfoot>

                            <tr>
                                <td colspan="9">

                                    <div class="text-center pt-2">
                                        <? echo $paging->viewTableSlice(); ?>
                                    </div>

                                    <? if ($paging->query->rows()) { ?>

                                        <div class="text-right pt-2">
                                            <input name='remove' type='submit' value='Remover' class='btn btn-danger'>
                                            <input class="btn btn-warning" type="button" id="selectButton" value="Selecionar Todos" onClick="toggleSelect(); return false">
                                        </div>

                                    <? } ?>

                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </form>

    </section>

    <?
    include_once('../includes/dashboard/footer.php');
?>