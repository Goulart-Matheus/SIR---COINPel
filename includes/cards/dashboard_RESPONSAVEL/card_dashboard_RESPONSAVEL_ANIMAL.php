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



<!-- Fim-->
            
        </div>

        <div class="card-footer">

            <div class="row">

                <div class="col-9"><a href='ANIMAL_form.php?id_responsavel=<?= $id_responsavel ?>'><i class="fa fa-plus"></i> Novo</a></div>

                <!-- <div class="col-6 text-right"><a href='RESPONSAVEL_viewDados.php?id_responsavel=<?= $id_responsavel ?>'>Editar informações</a></div>  -->

            </div>

        </div>

    </div>
    