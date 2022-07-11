<?
    // preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

    $query->exec("SELECT
                        ar.id_responsavel,
                        ar.id_animal,
                        a.nro_ficha,
                        a.nro_chip,
                        p.descricao,
                        e.descricao

                FROM
                        responsavel r,
                        animal_responsavel ar,
                        animal a,
                        pelagem p,
                        especie e
                WHERE
                    r.id_responsavel = $id_responsavel
                AND
                    ar.id_responsavel = r.id_responsavel
                AND
                    ar.id_animal = a.id_animal
                AND
                    p.id_pelagem = a.id_pelagem
                AND
                    e.id_especie = a.id_especie
                              

                "
      
    );
    $query->result($query->linha);
         
     $id_responsavel                 = $query->record[0];
     $id_animal                      = $query->record[1];
     $nro_ficha                      = $query->record[2];
     $nro_chip                       = $query->record[3];
     $pelagem                        = $query->record[4];
     $especie                        = $query->record[5];
     
     $n = $query->rows();
    
    //$total_contato = $query->record[0];

    

   

   //$js_Onclick = "OnClick=javascript:window.location=('formOrgaoPedidoInformacao.php?search=true&id_orgao=$id_orgao&form_search_situacao=";

    ?>

    <div class="card border">

        <div class="card-header bg-green">

            <div class="row">

                <div class="col-12">
                    <i class="fas fa-list"></i> Animais vinculados 
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

                            <h5 class="mb-5">Este Responsável ainda não possue nenhum animal vinculado</h5>

                            <a href="ANIMAL_cover.php?id_responsavel=<?= $id_responsavel ?>" class="btn btn-light gray text-green">Vincule um ainimal para este responsável</a>

                        </div>
                    <?
                }
                else
                {
                    ?>

                        <table class="table p-0 m-0">
                                    
                            <thead class="bg-#A9F5BC">
                            
                                <tr>
                                    <th style="width: 5px;" >ID</th>                                
                                    <th style="width: 25px;" class="px-1" >Nro Ficha</th>
                                    <th style="width: 25px;" class="px-1" >Nro Chip</th>
                                    <th style="width: 25px;" class="px-1" >Pelagem</th>
                                    <th style="width: 25px;" class="px-1" >Especie</th>
                                    

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

                <div class="col-9"><a href='ANIMAL_cover.php?id_responsavel=<?= $id_responsavel ?>'><i class="fa fa-plus"></i> Novo</a></div>

               
            </div>

        </div>

    </div>
    