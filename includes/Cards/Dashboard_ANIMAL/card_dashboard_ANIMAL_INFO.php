<?

// preparando a listagem dos contatos (endereço, bairro, fone,celular, e-mail e se o contato é principal ou não)

$query->exec(
    "SELECT
                        a.id_animal,
                        r.id_responsavel, 
                        rc.id_responsavel_contato,
                        rc.valor_contato,
                        rc.principal,
                        e. especie
                FROM
                        responsavel r,
                        responsavel_contato rc,
                        especie e,
                        animal a, 
                WHERE
                    r.id_responsavel = $id_responsavel and a.id_animal = $id_animal and 
                    
                AND                                               
                    r.id_responsavel = rc.id_responsavel
                AND  

                    e.id_especie = a.id_especie


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
                <i class="fas fa-info-circle"></i> Responsável Animal
            </div>

        </div>

    </div>
    <!-- inicio -->
    <div class="card-body overflow-auto p-0 m-0 table-responsive" style="height: 175px;">

        <table class="table">

            <tbody>
                <tr>
                    <td>Contato(s)</td>
                    <td>Principal</td>
                </tr>
                <?
                while ($n--) {
                    $query->proximo();

                ?>
                    <tr>

                        <td><?= $query->record[1]; ?></td>
                        <td><?= $query->record[2]; ?></td>
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

            <div class="col-6"><a href='ANIMAL_form.php'><i class="fa fa-plus"></i> Novo</a></div>

            <!-- <div class="col-6 text-right"><a href='ANIMAL_viewDados.php?id_animal=<?= $id_animal ?>'>Editar informações</a></div> -->

        </div>

    </div>

</div>