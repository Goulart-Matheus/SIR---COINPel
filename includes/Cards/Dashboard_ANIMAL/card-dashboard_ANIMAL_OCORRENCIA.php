<?
    // preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)
    //Sandra alterei o teu where para testar pelo $id_animal esta variavel foi criada no ANIMAL_form.php
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

    //echo $query-> sql;
    // $query->result($query->linha);
    // $id_hospedagem                  = $query->record[0];
    // $nro_ficha                      = $query->record[1];
    // $nro_chip                       = $query->record[2];
    // $endereco_recolhimento          = $query->record[3];
    // $bairro                         = $query->record[4];
    // $responsavel                    = $query->record[5];
    // $dt_entrada                     = $query->record[6];
    // $dt_retirada                    = $query->record[7];
    // $motivo                         = $query->record[8];
    // $valor                          = $query->record[9];
    
    $n = $query->rows();

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
                                    while($n--)
                                    {
                                        $query->proximo();
                                       
                                        ?>
                                            <tr>
                                                
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

                <div class="col-6"><a href='HOSPEDAGEM_form.php?id_responsavel=<?= $id_responsavel ?>'><i class="fa fa-plus"></i> Novo</a></div>

             

            </div>

        </div>

    </div>
    