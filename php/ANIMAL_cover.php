<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
include('../function/function.date.php');

$query->exec("SELECT 
                    a.id_animal , a.nro_ficha , a.nro_chip , a.sexo , a.observacao, p. descricao , e.descricao
                    FROM 
                        animal a, pelagem p , especie e
                    WHERE 
                        id_animal = $id_animal
                        AND 
                        a.id_pelagem = p.id_pelagem
                        AND 
                        a.id_especie = e.id_especie

                ");

$query->result($query->linha);

$id_animal         = $query->record[0];
$nro_ficha         = $query->record[1];
$nro_chip          = $query->record[2];
$id_pelagem        = $query->record[3];
$id_especie        = $query->record[4];
$sexo              = $query->record[5];
$observacao        = $query->record[6];

$tab = new Tab();

$tab->setTab('Pesquisar', 'fas fa-search', 'ANIMAL_view.php?id_animal='                  . $id_animal);
$tab->setTab($query->record[1], $query->record[5], $_SERVER['PHP_SELF'] . '?id_animal='   . $id_animal);
$tab->setTab('Editar', 'fas fa-pencil-alt', 'ANIMAL_edit.php?id_animal='                 . $id_animal);
$tab->printTab($_SERVER['PHP_SELF'] . '?id_animal=' . $query->record[0]);

?>


<section class="content">

    <div class="card p-0">

        <div class="card-header border-bottom-1 mb-3 bg-light-2">

            <div class="text-center">
                <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
            </div>

            <div class="row text-center">
                <div class="col-12 col-sm-4 offset-sm-4">
                    <? if ($erro) echo callException($erro, 2); ?>
                </div>
            </div>

        </div>


        <div class="card-body pt-3">


            <div class="row">

                <div class="col-12">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card_dashboar_ANIMAL_FICHA.php"); ?>

                </div>

            </div>

            <div class="row">

                <div class="col-12 col-md-4">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card_dashboard_ANIMAL_RESPONSAVEL.php"); ?>


                </div>



                <div class="col-12 col-md-12">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card-dashboard_ANIMAL_OCORRENCIA.php"); ?>

                </div>

            </div>

        </div>

    </div>

</section>


<?
include_once('../includes/dashboard/footer.php');

function isNum($val)
{
    if (is_numeric($val)) {
        if (strtoupper($val) != "NAN") {
            return intval($val);
        }
    }
    return 0;
}
?>


<script>
    $(document).on('click', '.ad_duplicidade', function() {

        $.ajax({
            type: "GET",
            url: "../includes/ajax_valida_dup_responsavel_animal.php",
            data: {
                'nome': nome,
                'cpf': cpf
            },
            success: function(r) {

                if (r[0]["status"] == 1) {

                    var modal = '<div class="modal" tabindex="-1" role="dialog" id="modal_dup_responsavel_animal">';
                    modal += '<div class="modal-dialog modal-xl" role="document">';
                    modal += '<div class="modal-content">';
                    modal += '<div class="modal-header">';
                    modal += '<h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning"></i> Possível Duplicidade</h5>';
                    modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    modal += '<span aria-hidden="true">&times;</span>';
                    modal += '</button>';
                    modal += '</div>';
                    modal += '<div class="modal-body">';
                    modal += '<table class="table table-striped">';
                    modal += '<thead>';
                    modal += '<tr>';
                    modal += '<th>ID</th>';
                    modal += '<th>Nome</th>';
                    modal += '<th class="text-center">CPF</th>';
                    modal += '<th class="text-center">RG(a)</th>';
                    modal += '<th class="text-center">Dt_nascimento</th>';
                    modal += '<th class="text-center">Endereço</th>';
                    modal += '<th class="text-center">Id_bairro</th>';
                    modal += '<th class="text-center">Opções</th>';
                    modal += '<tr>';
                    modal += '<tbody>';

                    r.forEach(function(valor, chave) {

                        modal += '<tr>';
                        modal += '<td><b>' + valor['id_responsavel'] + '</b></td>';
                        modal += '<td>' + valor['nome'] + '</td>';
                        modal += '<td class="text-center">' + valor['cpf'] + '</td>';
                        modal += '<td class="text-center">' + valor['rg'] + '</td>';
                        modal += '<td class="text-center">' + valor['dt_nascimento'] + '</td>';
                        modal += '<td class="text-center">' + valor['endereco'] + '</td>';
                        modal += '<td class="text-center">' + valor['id_bairro'] + '</td>';


                        if (valor['duplicidade'] == "S") {
                            modal += '<td class="text-center">';
                            modal += '<button class="btn btn_green bg-green btn-sm ad_duplicidade" ';
                            modal += 'data-id-resp="' + valor['id_responsavel'] + '" ';
                            modal += 'data-id-nome="' + valor['nome'] + '" ';
                            modal += 'data-id-cpf="' + valor['cpf'] + '" ';
                            modal += 'data-id-rg="' + valor['rg'] + '" ';
                            modal += 'data-id-dt_nascimento="' + valor['dt_nascimento'] + '" ';
                            modal += 'data-id-endereco="' + valor['endereco'] + '" ';
                            modal += 'data-id-bairro="' + valor['bairro'] + '" ';
                            modal += '>Adicionar Duplicidade</button>';

                            modal += '</td>';
                        } else {
                            modal += '<td class="text-center">Sem Ações</td>';
                        }

                        modal += '</tr>';
                    });

                    modal += '</tbody>';
                    modal += '</table>';
                    modal += '</div>';
                    modal += '</div>';
                    modal += '</div>';
                    modal += '</div>';

                    if ($(".modal-backdrop").length > 0) {
                        $(".modal-backdrop").remove();
                    }

                    if ($("#modal_dup_responsavel_animal").length > 0) {
                        $("#modal_dup_responsavel_animal").remove();
                    }

                    $('body').append(modal);
                    $("#modal_dup_responsavel_animal").modal('show');
                    console.log(r);

                    return;
                }

            },
            error: function(r) {
                alert("Erro ao buscar os dados dos responsaveis");

                console.log(r);
            }
        });

    });

    $(document).on('click', '.ad_duplicidade', function() {

        //var sexo = "Masculino";
        //if ($(this).attr('data-id-sexo') == "F") {
        //sexo = "Feminino";
        //}

        var modal = '<div class="modal" tabindex="-1" role="dialog" id="modal_reincidencia">';
        modal += '<div class="modal-dialog modal-md" role="document">';
        modal += '<div class="modal-content">';
        modal += '<div class="modal-header bg-warning">';
        modal += '<h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger"></i> Adicionar Reincidencia? </h5>';
        modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        modal += '<span aria-hidden="true">&times;</span>';
        modal += '</button>';
        modal += '</div>';
        modal += '<div class="modal-body">';
        modal += '<div class="row">';

        modal += '<div class="col-12 text-justify">';
        modal += 'Deseja cadastrar a reincidencia para o responsavel abaixo? <br><small>Este procedimento tornará o responsavel <b>ID ' + $(this).attr('data-id-reincidencia') + '</b> inalterável, possibilitando alterações apenas no novo registro</small>';
        modal += '</div>';

        modal += '<div class="col-12 text-justify">';

        modal += '<div class="row p-2">';

        modal += '<div class="col-4 text-justify py-1 bg-warning-secondary">ID</div>';
        modal += '<div class="col-8 text-right py-1 bg-warning-secondary"><b>' + $(this).attr('data-id-reincidencia') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1">Nome</div>';
        modal += '<div class="col-8 text-right py-1"><b>' + $(this).attr('data-id-nome') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1">CPF</div>';
        modal += '<div class="col-8 text-right py-1"><b>' + $(this).attr('data-id-cpf') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1 bg-warning-secondary">RG</div>';
        modal += '<div class="col-8 text-right py-1 bg-warning-secondary"><b>' + $(this).attr('data-id-rg') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1">Dt_nascimento</div>';
        modal += '<div class="col-8 text-right py-1"><b>' + $(this).attr('data-id-dt_nascimento') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1 bg-warning-secondary">Endereco</div>';
        modal += '<div class="col-8 text-right py-1 bg-warning-secondary"><b>' + $(this).attr('data-id-endereco') + '</b></div>';

        modal += '<div class="col-4 text-justify py-1 bg-warning-secondary">Id_bairro</div>';
        modal += '<div class="col-8 text-right py-1 bg-warning-secondary"><b>' + $(this).attr('data-id-bairro') + '</b></div>';

        modal += '</div>';

        modal += '</div>';

        modal += '<div class="col-12 text-right">';
        modal += '<button class="btn btn-warning" id="vincular_responsavel" data-id-responsavel="' + $(this).attr('data-id-reincidencia') + '">Vincular</a>';
        modal += '</div>';

        modal += '</div>';

        modal += '</div>';

        modal += '</div>';

        modal += '</div>';

        modal += '</div>';

        if ($(".modal-backdrop").length > 0) {
            $(".modal-backdrop").remove();
        }

        if ($("#modal_reincidencia").length > 0) {
            $("#modal_reincidencia").remove();
        }

        if ($("#modal_dup_responsavel_animal").length > 0) {
            $("#modal_dup_responsavel_animal").remove();
        }

        $('body').append(modal);
        $("#modal_reincidencia").modal('show');

        return;

    });




    $(document).on("click", "#vincular_responsavel", function() {

        var id_responsavel = $(this).attr('data-id-responsavel');

        $.ajax({
            type: "GET",
            url: "../includes/ajax_vincula_responsavel_animal.php",
            data: {
                'id_responsavel_base': id_responsavel

            },

            success: function(r) {

                if (r[0]["status"] == 1) {

                    var modal = '<div class="modal" tabindex="-1" role="dialog" id="modal_reincidencia_vinculado">';
                    modal += '<div class="modal-dialog modal-md" role="document">';
                    modal += '<div class="modal-content">';
                    modal += '<div class="modal-header bg-green">';
                    modal += '<h5 class="modal-title"><i class="fas fa-check-circle text-light"></i> Vinculo adicionado com sucesso! </h5>';
                    modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    modal += '<span aria-hidden="true">&times;</span>';
                    modal += '</button>';
                    modal += '</div>';
                    modal += '<div class="modal-body">';
                    modal += '<div class="row">';

                    modal += '<div class="col-12 text-center py-3">';
                    modal += '<h1 class="text-center"><i class="fas fa-check-circle text-green"></i></h1>';
                    modal += '</div>';

                    modal += '<div class="col-12 text-justify">';
                    modal += 'O responsavel de ID <b>' + id_responsavel + '</b> foi vinculado ao ID <b>' + r[0]["id_responsavel_new"] + '</b> sendo este seu novo registro.';
                    modal += '</div>';

                    modal += '<div class="col-12 text-center py-3">';
                    modal += '<a class="btn btn-green bg-green" href="infoResponsavel.php?id_responsavel=' + r[0]["id_responsavel_new"] + '">Acessar responsavel <b>' + r[0]["id_responsavel_new"] + '</b></a>';
                    modal += '</div>';

                    modal += '</div>';

                    modal += '</div>';

                    modal += '</div>';

                    modal += '</div>';

                    modal += '</div>';

                    if ($(".modal-backdrop").length > 0) {
                        $(".modal-backdrop").remove();
                    }

                    if ($("#modal_responsavel_vinculado").length > 0) {
                        $("#modal_responsavel_vinculado").remove();
                    }

                    if ($("#modal_reincidencia").length > 0) {
                        $("#modal_reincidencia").remove();
                    }

                    if ($("#modal_dup_responsavel_animal").length > 0) {
                        $("#modal_dup_responsavel_animal").remove();
                    }

                    $('body').append(modal);
                    $("#modal_responsavel_vinculado").modal('show');
                    console.log(r);

                    return;
                }

            },
            error: function(r) {
                alert("Erro ao vincular os dados do responsavel");
            }
        });
    });
</script>