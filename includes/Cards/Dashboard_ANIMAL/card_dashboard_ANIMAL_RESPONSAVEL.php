<?
// preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

$query->exec(
    "SELECT
                 ar.id_animal_responsavel,
                 a.id_animal,
                 ar.id_responsavel,
                 rc.valor_contato,
                 rc.principal

                FROM
                        
                        animal_responsavel ar,
                        responsavel_contato rc,
                        animal a,
                        responsavel r
                WHERE
                    r.nome ilike '%" . $form_animal . "%'
                   
                and 
                  a.id_animal = ar.id_animal  
                and
                    rc.id_responsavel = r.id_responsavel
                
                                   

                "

);
//$total_contato = $query->record[0];


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



        <!-- Fim-->

    </div>

    <div class="card-footer">

        <div class="row">

            <div class="col-6"><a href='RESPONSAVEL_form.php'><i class="fa fa-plus"></i> Novo</a>
        
        
        
        
        
        
        
        </div>

            <!-- <div class="col-6 text-right"><a href='ANIMAL_viewDados.php?id_animal=<?= $id_animal ?>'>Editar informações</a></div> -->

        </div>

    </div>

</div>