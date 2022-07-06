<?
    // preparando a listagem dos animal Responsavel com o contato principal

    $query->exec("SELECT
                         ar.id_animal_responsavel,
                         ar.id_animal,
                         r.id_responsavel,
                         rc.id_responsavel_contato,
                         tc.id_tipo_contato,
                         rc.valor_contato,

                FROM
                        responsavel r,
                        tipo_contato tc,
                        responsavel_contato rc
                WHERE
                    r.nome ilike '%" . $form_responsavel . "%'
                   
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
                    <i class="fas fa-info-circle"></i> Animal Informações
                </div>

            </div>

        </div>

        <div class="card-body p-0 m-0" style="height: 405px;">

            <div class="col-12 p-0 m-0" id="chart_info"></div>
<!-- Inicio -->



<!-- Fim-->
            
        </div>

        <div class="card-footer">

            <div class="row">

                <div class="col-6"><a href='RESPONSAVEL_form.php'><i class="fa fa-plus"></i> Novo</a></div>

               <!-- <div class="col-6 text-right"><a href='RESPONSAVEL_viewDados.php?id_responsavel=<?= $id_responsavel ?>'>Editar informações</a></div> -->

            </div>

        </div>

    </div>
    