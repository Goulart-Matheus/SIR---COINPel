<?
    // preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

    $query->exec("SELECT
                        a.id_animal,
                        r.id_responsavel,
                        rc.valor_contato,
                        rc.principal,
                         m.descricao,
                         b.descricao
                         tc.id_tipo_contato 
                FROM

                        animal a ,
                        responsavel r,
                        responsavel_contato rc,
                        motivo m,
                        bairro b,
                        tipo_contato tc

                       
                WHERE
                    r.nome ilike '%" . $form_animal . "%'
                   
                and 
                    b.id_bairro =r.id_bairro 
                and
                    rc.id_responsavel = r.id_responsavel
                and 
                    rc.id_tipo_contato = tc.id_tipo_contato                

                "
      
    );
    //$total_contato = $query->record[0];

   
   //$js_Onclick = "OnClick=javascript:window.location=('formOrgaoPedidoInformacao.php?search=true&id_orgao=$id_orgao&form_search_situacao=";

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



<!-- Fim-->
            
        </div>

        <div class="card-footer">

            <div class="row">

                <div class="col-6"><a href='ANIMAL_form.php'><i class="fa fa-plus"></i> Novo</a></div>

               <!-- <div class="col-6 text-right"><a href='ANIMAL_viewDados.php?id_animal=<?= $id_animal ?>'>Editar informações</a></div> -->

            </div>

        </div>

    </div>
    