<?
    // preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)
$query_ocorrencia = new Query($bd);
$query_ocorrencia->exec("SELECT
                h.id_hospedagem,
                a.nro_ficha,
                a.nro_chip,
                h.endereco_recolhimento,
                b.descricao,
                r.nome,
                h.dt_entrada,
                h.dt_retirada,
                m.descricao,
                h.valor

            FROM
                hospedagem h,
                responsavel r,
                animal a,
                pelagem p,
                especie e,
                bairro b,
                motivo m
            WHERE
                r.id_responsavel    = $id_responsavel
            AND
                h.id_responsavel    = r.id_responsavel
            AND
                h.id_animal         = a.id_animal
            AND
                p.id_pelagem        = a.id_pelagem
            AND
                e.id_especie        = a.id_especie
            AND 
                h.id_bairro         = b.id_bairro
            AND
                m.id_motivo         = h.id_motivo
          

"
      
    );
    $query_ocorrencia->result($query_ocorrencia->linha);
    $id_hospedagem                  = $query_ocorrencia->record[0];
    $nro_ficha                      = $query_ocorrencia->record[1];
    $nro_chip                       = $query_ocorrencia->record[2];
    $endereco_recolhimento          = $query_ocorrencia->record[3];
    $bairro                         = $query_ocorrencia->record[4];
    $responsavel                    = $query_ocorrencia->record[5];
    $dt_entrada                     = $query_ocorrencia->record[6];
    $dt_retirada                    = $query_ocorrencia->record[7];
    $motivo                         = $query_ocorrencia->record[8];
    $valor                          = $query_ocorrencia->record[9];
    
    $n = $query_ocorrencia->rows();

    ?>

    <div class="card border">

        <div class="card-header bg-green">

            <div class="row">

                <div class="col-12">
                    <i class="fas fa-hand-paper"></i> Ocorrências 
                </div>

            </div>

        </div>

        <div class="card-body p-0 m-0" style="height: 175px;">

            <div class="col-12 p-0 m-0" id="chart_info"></div>
<!-- Inicio -->
<?
                if($n == 0)
                {
                    ?>

                        <div class="col-12 text-center pt-5 text-light">

                            <h5 class="mb-5">Este Responsável ainda não possue nenhuma ocorrência</h5>

                            <a href="HOSPEDAGEM_form.php?id_responsavel=<?= $id_responsavel ?>" class="btn btn-light gray text-green">Gerar ocorrência para este responsável</a>

                        </div>
                    <?
                }
                else
                {
                    ?>

                        <table class="table p-0 m-0">
                                    
                            <thead class="bg-#A9F5BC">
                            
                                <tr>
                                                               
                                    <th style="width: 25px;" class="px-1" >Nro Ficha</th>
                                    <th style="width: 25px;" class="px-1" >Nro Chip</th>
                                    <th style="width: 25px;" class="px-1" >Endereço de recolhimento</th>
                                    <th style="width: 25px;" class="px-1" >Bairro</th>
                                    <th style="width: 25px;" class="px-1" >Responsável</th>
                                    <th style="width: 25px;" class="px-1" >Data de entrada</th>
                                    <th style="width: 25px;" class="px-1" >Data de retirada</th>
                                    <th style="width: 25px;" class="px-1" >Motivo</th>
                                    <th style="width: 25px;" class="px-1" >Valor</th>


                                </tr>

                            </thead>

                            <tbody>
                            
                                <?
                                    while($n--)
                                    {
                                        $query_ocorrencia->proximo();
                                       
                                        ?>
                                            <tr>
                                               
                                                <td><?= $query_ocorrencia->record[1]; ?></td>
                                                <td><?= $query_ocorrencia->record[2]; ?></td>
                                                <td><?= $query_ocorrencia->record[3]; ?></td>
                                                <td><?= $query_ocorrencia->record[4]; ?></td>
                                                <td><?= $query_ocorrencia->record[5]; ?></td>
                                                <td><?= $query_ocorrencia->record[6]; ?></td>
                                                <td><?= $query_ocorrencia->record[7]; ?></td>
                                                <td><?= $query_ocorrencia->record[8]; ?></td>
                                                <td><?= $query_ocorrencia->record[9]; ?></td>

                                            </tr>
                                        <?

                                    }
                                ?>

                            </tbody>

                        </table>

                    <?
                }

            ?>


<!-- Fim-->
            
        </div>

        <div class="card-footer">

            <div class="row">

                <div class="col-6"><a href='HOSPEDAGEM_form.php?id_responsavel=<?= $id_responsavel ?>'><i class="fa fa-plus"></i> Novo</a></div>

             

            </div>

        </div>

    </div>
    