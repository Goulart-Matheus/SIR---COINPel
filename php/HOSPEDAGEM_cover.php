<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
include('../function/function.date.php');

$query_hospedagem = new Query($bd);
$query_hospedagem->exec("SELECT 
h.id_hospedagem , a.nro_ficha , a.nro_chip , h.dt_entrada , h.endereco_recolhimento, b.descricao , h.dt_retirada, u.mes_referencia, u.ano_referencia,h.valor,e.descricao, r.nome,p.descricao,h.id_animal, h.situacao
FROM 
animal a,  hospedagem as h, bairro as b , urm as u, especie as e, responsavel as r, pelagem as p
 WHERE 
h.id_hospedagem = $id_hospedagem
AND
h.id_animal = a.id_animal 
AND 
h.id_bairro = b.id_bairro
AND
h.id_urm = u.id_urm


");




$query_hospedagem->result($query->linha);

if(isset($query_hospedagem->record[6])){
    $dt_retirada = date('d/m/Y',strtotime($query_hospedagem->record[6]));
}else{
    $dt_retirada =  $query_hospedagem->record[6];
}

$id_hospedagem                = $query_hospedagem->record[0];
$nro_ficha                    = $query_hospedagem->record[1];
$nro_chip                     = $query_hospedagem->record[2];
$dt_entrada                   = date('d/m/Y', strtotime($query_hospedagem->record[3]));
$dt_entrada_reincidencia      = $query_hospedagem->record[3];
$endereco_recolhimento        = $query_hospedagem->record[4];
$bairro                       = $query_hospedagem->record[5];
$urm_mes                      = $query_hospedagem->record[7];
$urm_ano                      = $query_hospedagem->record[8];
$hospedagem_valor             = number_format($query_hospedagem->record[9], 2, ',', '.');
$especie_descricao            = $query_hospedagem->record[10];
$responsavel_nome             = $query_hospedagem->record[11];
$pelagem                      = $query_hospedagem->record[12];
$id_animal_count              = $query_hospedagem->record[13];
$situacao                     = $query_hospedagem->record[14];



//Contagem de Reincidencias
$query_reincidencias = new Query($bd);

$query_reincidencias->exec("SELECT count(id_animal) FROM hospedagem WHERE  id_animal = '".$id_animal_count."' AND dt_retirada <= '".$dt_entrada_reincidencia."'");
$query_reincidencias->proximo();

if ($query_reincidencias->record[0] > 0) {

    $nro_reincidencias = $query_reincidencias->record[0];
} else {
    $nro_reincidencias = 0;
}



$tab = new Tab();

$tab->setTab('Atendimentos', 'fa-solid fa-house-chimney-medical', 'HOSPEDAGEM_viewDados.php');
$tab->setTab($query_hospedagem->record[1], $query_hospedagem->record[5], $_SERVER['PHP_SELF'] . '?id_hospedagem='   . $id_hospedagem);
$tab->setTab('Editar', 'fas fa-pencil-alt', 'HOSPEDAGEM_edit.php?id_hospedagem=' . $id_hospedagem);
$tab->printTab($_SERVER['PHP_SELF'] . '?id_hospedagem=' . $id_hospedagem);

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

                    <? include("../includes/cards/card_dashboard_HOSPEDAGEM/card_dashboard_HOSPEDAGEM_FICHA.php"); ?>

                </div>

            </div>

            <div class="row">

                <div class="col-12 col-md-12">

                    <? include("../includes/cards/card_dashboard_HOSPEDAGEM/card_dashboard_HOSPEDAGEM_ANEXO.php"); ?>

                </div>

                <div class="col-12 col-md-12">

                    <?  ?>

                </div>

            </div>

        </div>

    </div>

    <div class="col-12 col-md-12">

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
    $(document).ready(function() {

        $("#add_hospedagem_anexo").on('change', function() {

            var id_hospedagem = <?= $id_hospedagem ?>;
            var nome_arquivo = $("#nome_arquivo_hospedagem_anexo").val();
            var arquivo = $("#arquivo_hospedagem_anexo").val();

            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_atualiza_valor_urm.php',
                data: {

                    "id_hospedagem": id_hospedagem,
                    "nome_arquivo": nome_arquivo,
                    "arquivo": arquivo

                },
                beforeSend: function() {

                    console.log("Enviado ok");
                    $("HOSPEDAGEM_ANEXO_ADD").modal('hide');

                },
                success: function(response) {

                    $("#form_valor").val(response['valor']);

                },
                error: function(erro) {

                    // console.log(erro);

                }
            });
        });
    });
</script>