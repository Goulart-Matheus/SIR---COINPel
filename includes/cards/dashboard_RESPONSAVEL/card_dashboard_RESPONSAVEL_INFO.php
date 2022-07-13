
<?
   
     // preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

    $query->exec("SELECT
                        r.id_responsavel,
                        rc.valor_contato,
                        rc.principal
                FROM
                        responsavel r,
                        responsavel_contato rc
                WHERE
                    r.id_responsavel = $id_responsavel
                AND                                               
                    r.id_responsavel = rc.id_responsavel
                "
      
    );
    
    
    
    
    
    
    
    
    
    
    $n = $query->rows();







    //$total_contato = $query->record[0];
   //$js_Onclick = "OnClick=javascript:window.location=('formOrgaoPedidoInformacao.php?search=true&id_orgao=$id_orgao&form_search_situacao=";

    ?>

    <div class="card border">

        <div class="card-header bg-green">

            <div class="row">

                <div class="col-12">
                    <i class="fas fa-info-circle"></i> Contatos do  Responsável
                </div>

            </div>

        </div>
<!-- inicio -->
        <div class="card-body overflow-auto p-0 m-0 table-responsive" style="height: 175px;">

        <table class="table">
            
            <tbody>
            <thead class="bg-light grey"> 
                <tr>
                   
                <th style="width: 5px;" >Contatos(s)</th>   
                   
                </tr>
            </thead>
                <?
                    while($n--)
                    {
                        $query->proximo();

                        ?>
                            <tr>
                               
                                <td><?= $query->record[1]; ?></td>
                                
                            </tr>
                        <?

                    }
                ?>

            </tbody>

        </table>

    </div>
<!-- fim -->
        <div class="card-footer">

            <div class="row">

                <div class="col-6"><a href='RESPONSAVEL_form.php'><i class="fa fa-plus"></i> Novo</a></div>

               <!-- <div class="col-6 text-right"><a href='RESPONSAVEL_viewDados.php?id_responsavel=<?= $id_responsavel ?>'>Editar informações</a></div> -->

            </div>

        </div>

    </div>
    