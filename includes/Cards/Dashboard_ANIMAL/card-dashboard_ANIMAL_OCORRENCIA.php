<?
$query->exec("SELECT
                h.id_hospedagem,
                a.nro_ficha,
                a.nro_chip,
                h.endereco_recolhimento,
                b.descricao as bairro,
                r.nome,
                h.dt_entrada,
                h.dt_retirada,
                m.descricao,
                h.valor

            FROM
                hospedagem  h, 
                responsavel r,
                animal  a,
                pelagem p,
                especie e,
                bairro  b,
                motivo  m
            WHERE
                a.id_animal = $id_animal  
            AND
                h.id_responsavel = r.id_responsavel
            AND
                h.id_animal = a.id_animal
            AND
                p.id_pelagem = a.id_pelagem
            AND
                e.id_especie = a.id_especie
            AND 
                h.id_bairro = b.id_bairro
            AND
                m.id_motivo = h.id_motivo ");     

       
    $n = $query->rows();

    ?>

    <div class="card border">

        <div class="card-header bg-green">

            <div class="row">

                <div class="col-12">
                    <i class="fas fa-hand-paper"></i> OCORRÊNCIAS
                </div>

            </div>

        </div>

        <div class="card-body p-0 m-0">

            <div class="col-12 p-0 m-0" id="chart_info"></div>
<!-- Inicio -->
<?
                if($n == 0)
                {
                    ?>

                        <div class="col-12 text-center pt-5 text-light">

                            <h5 class="mb-5">Este Responsável ainda não possue nenhuma ocorrência</h5>

                           

                        </div>
                    <?
                }
                else
                {
                    ?>

                        <table class="table table-sm text-sm p-0 m-0 table-overflow" style=" width:auto;">
                                    
                            <thead class="bg-light grey table-responsive">
                            
                                <tr>
                                                                    
                                    <th style="width: 25px;" class="px-1" >Nro Ficha</th>
                                    <th style="width: 25px;" class="px-1" >Nro Chip</th>
                                    <th style="width: 25px;" class="px-1" >Endereço de recolhimento</th>
                                    <th style="width: 25px;" class="px-1" >Bairro</th>
                                    <th style="width: 25px;" class="px-1" >Responsável</th>
                                    <th style="width: 25px;" class="px-1" >Data de entrada</th>
                                    <th style="width: 25px;" class="px-1" >Data de retirada</th>
                                    <th style="width: 25px;" class="px-1" >Motivo</th>
                                    <th style="width: 25px;" class="px-1" >Valor(R$)</th>


                                </tr>

                            </thead>

                            <tbody style="height: 200px;  width:auto;">
                            
                                <?
                                    while($n--)
                                    {
                                        $query->proximo();
                                       
                                        ?>
                                            <tr class="entered">
                                                
                                                <td><?= $query->record[1]; ?></td>
                                                <td><?= $query->record[2]; ?></td>
                                                <td><?= $query->record[3]; ?></td>
                                                <td><?= $query->record[4]; ?></td>
                                                <td><?= $query->record[5]; ?></td>
                                                <td><?= $query->record[6]; ?></td>
                                                <td><?= $query->record[7]; ?></td>
                                                <td><?= $query->record[8]; ?></td>
                                                <td><?= $query->record[9]; ?></td>

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

                <div class="col-6"><a href='HOSPEDAGEM_form.php?id_animal=<?= $id_animal ?>'><i class="fa fa-plus"></i> Novo</a></div>             

            </div>

        </div>

    </div>
    