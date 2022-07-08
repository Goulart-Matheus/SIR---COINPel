<?
    // preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

$query->exec("SELECT
                a.nro_ficha,
                a.nro_chip,
                h.endereco_recolhimento,
                b.descricao,
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
                r.id_responsavel = $id_responsavel
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
                m.id_motivo = h.id_motivo
          

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

                <div class="col-6"><a href='HOSPEDAGEM_form.php?id_responsavel=<?= $id_responsavel ?>'><i class="fa fa-plus"></i> Novo</a></div>

               <!-- <div class="col-6 text-right"><a href='RESPONSAVEL_viewDados.php?id_responsavel=<?= $id_responsavel ?>'>Editar informações</a></div> -->

            </div>

        </div>

    </div>
    