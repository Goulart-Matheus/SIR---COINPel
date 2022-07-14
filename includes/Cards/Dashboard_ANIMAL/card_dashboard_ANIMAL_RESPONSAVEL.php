<?
// preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

$query->exec(
                "SELECT
                ar.id_responsavel,
                ar.id_animal,
                r.nome,
                r.cpf,
                r.rg,
                r.dt_nascimento,
                r.endereco,
                b.descricao

            FROM
                responsavel r,
                animal_responsavel ar,
                animal a,
                bairro b
            WHERE
            a.id_animal = $id_animal
            AND
            ar.id_responsavel = r.id_responsavel
            AND
            ar.id_animal = a.id_animal
            AND
            b.id_bairro = r.id_bairro
             "

);
//$total_contato = $query->record[0];
$n = $query->rows();

//$js_Onclick = "OnClick=javascript:window.location=('formOrgaoPedidoInformacao.php?search=true&id_orgao=$id_orgao&form_search_situacao=";

?>

<div class="card border">

    <div class="card-header bg-green">

        <div class="row">

            <div class="col-12">
                <i class="fas fa-list"></i> Responsáveis Vinculados
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

                            <h5 class="mb-5">Este animal ainda não possue nenhum responsável vinculado</h5>

                          

                        </div>
                    <?
                }
                else
                {
                    ?>

                        <table class="table p-0 m-0">
                                    
                            <thead class="bg-light grey">
                            
                                <tr>
                                                                   
                                    <th style="width: 25px;" class="px-1" >Nome</th>
                                    <th style="width: 25px;" class="px-1" >CPF</th>
                                    <th style="width: 25px;" class="px-1" >RG</th>
                                    <th style="width: 25px;" class="px-1" >Data de nascimento</th>
                                    <th style="width: 25px;" class="px-1" >Endereço</th>
                                    <th style="width: 25px;" class="px-1" >Bairro</th>

                                </tr>

                            </thead>

                            <tbody>
                            
                                <?
                                    while($n--)
                                    {
                                        $query->proximo();
                                       
                                        ?>
                                            <tr>
                                                <td><?= $query->record[2]; ?></td>
                                                <td><?= $query->record[3]; ?></td>
                                                <td><?= $query->record[4]; ?></td>
                                                <td><?= $query->record[5]; ?></td>
                                                <td><?= $query->record[6]; ?></td>
                                                <td><?= $query->record[7]; ?></td>
                                               
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

        <div class="col-9"><a href='RESPONSAVEL_form.php?id_responsavel=<?= $id_animal ?>'><i class="fa fa-plus"></i> Novo</a></div>
        
        
        
        
        
        
        
        </div>

            <!-- <div class="col-6 text-right"><a href='ANIMAL_viewDados.php?id_animal=<?= $id_animal ?>'>Editar informações</a></div> -->

        </div>

    </div>

</div>