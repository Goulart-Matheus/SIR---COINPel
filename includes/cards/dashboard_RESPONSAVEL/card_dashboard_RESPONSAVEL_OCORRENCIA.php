<?
    


$query->exec("SELECT
                h.id_responsavel,
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
                hospedagem h,
                responsavel r,
                animal a,
                pelagem p,
                especie e,
                bairro b,
                motivo m
            WHERE
                r.id_responsavel    =  $id_responsavel
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
                h.id_motivo         = m.id_motivo    
            
            
          
");
    
   

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
                if($query->rows()== 0)
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

                        <table class="table p-0 m-0">
                                    
                            <thead class="bg-light grey">
                            
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

                            <tbody>
                            
                                <?
                                    $query->all();
                                    foreach($query->record as $animal)
                                    {
                                        
                                       
                                        ?>
                                            <tr>
                                              
                                                <td><?= $animal['nro_ficha']; ?></td>
                                                <td><?= $animal['nro_chip']; ?></td>
                                                <td><?= $animal['endereco_recolhimento']; ?></td>
                                                <td><?= $animal['bairro']; ?></td>
                                                <td><?= $animal['nome']; ?></td>
                                                <td><?= $animal['dt_entrada']; ?></td>
                                                <td><?= $animal['dt_retirada']; ?></td>
                                                <td><?= $animal['descricao']; ?></td>
                                                <td><?= $animal['valor']; ?></td>

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
    