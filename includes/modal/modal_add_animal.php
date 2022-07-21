<?

$query_animal->insertTupla('animal', $dados);
$id_animal_last = $query_animal->last_insert[0];

if ($form_animal == "") {

    $query->exec("SELECT id_animal, nro_ficha, nro_chip, p.descrição, e.descricao, sexo
        
        FROM 
            animal, pelagem p, especie e
        WHERE 
            id_animal = $id_animal
        AND 
            p.id_pelagem = id_pelagem
        AND
            e.id_especie = id_especie
    ");

    $query->result($query->linha);

    $id_animal                   = $query->record[0];
    $nro_ficha                   = $query->record[1];
    $nro_chip                    = $query->record[2];
    $pelagem                     = $query->record[3];
    $esoecie                     = $query->record[4];
    $sexo                        = $query->record[5];
    
}
?>

<div class="modal fade show" id="modal_add_animal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <form action="<? echo $_SERVER['PHP_SELF'] . "?id_animal=" . $id_animal ?>" method="post">

                <div class="modal-header bg-gradient-yellow-orange">
                    <h5 class="modal-title"><i class="fas fa-project-diagram"></i> Listar Animais Cadastrados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group col-12">

                        <div class="form-row">



                        <table class="table table-striped responsive">

                    

                    <tbody>

                        <tr>

                            <td width="5px"></td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Nro_ficha'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Nro_chip'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Id_pelagem'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Id_especie'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Sexo'); ?> </td>
                            <td style=' <? echo $sort->verifyItem(1); ?>'> <? echo $sort->printItem(1, $sort->sort_dir, 'Observacao'); ?> </td>
                        </tr>

                        <?

                        while ($n--) {

                            $paging->query->proximo();

                            //$js_onclick = "OnClick=javascript:window.location=('ANIMAL_cover.php?id_animal=" . $paging->query->record[0] . "')";



                            echo "<tr>";

                            echo "<td valign='middle'><input type=checkbox class='form-check-value' name='id_animal[]' value=" . $paging->query->record[0] . "></td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[1] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[2] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[3] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[4] . "</td>";
                            echo "<td valign='middle' " . $js_onclick . ">" . $paging->query->record[5] . "</td>";
                           
                            echo "</tr>";
                        }

                        ?>

                    </tbody>

                    

                </table>
                           
                            

                        </div>
                    </div>

                </div>
        </div>
    </div>
</div>


<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
    <!-- <button type="button" id="add_escola_modal" class="btn btn-info">
        <i class="fas fa-check"></i>&nbsp;
        Salvar
    </button> -->
</div>