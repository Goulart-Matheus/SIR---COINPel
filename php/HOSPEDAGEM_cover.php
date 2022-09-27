<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
include('../function/function.date.php');


$query_responsavel = new Query($bd);
$query_responsavel->exec("SELECT h.id_responsavel FROM hospedagem as h WHERE h.id_hospedagem = $id_hospedagem");
$query_responsavel->result($query->linha);


if ($query_responsavel->record[0] == null) {
    $query_hospedagem = new Query($bd);
    $query_hospedagem->exec("SELECT 
                                h.id_hospedagem , a.nro_ficha , a.nro_chip , h.dt_entrada , h.endereco_recolhimento, b.descricao , h.dt_retirada, 
                                u.mes_referencia, u.ano_referencia,h.valor,e.descricao,p.descricao,h.id_animal, h.situacao, a.sexo, m.descricao,
                                b.descricao, h.id_responsavel, h.nro_boleto
                                FROM 
                                animal a,  hospedagem as h, bairro as b , urm as u, especie as e, responsavel as r, pelagem as p, motivo as m, responsavel_contato as rc , tipo_contato as tc 
                                WHERE 
                                h.id_hospedagem = $id_hospedagem
                                AND
                                h.id_animal = a.id_animal 
                                AND 
                                h.id_bairro = b.id_bairro
                                AND
                                h.id_urm = u.id_urm
                                AND
                                h.id_motivo = m.id_motivo
                                AND
                                e.id_especie = a.id_especie
                                AND 
                                p.id_pelagem = a.id_pelagem

                                ");

    $query_hospedagem->result($query_hospedagem->linha);

    if (isset($query_hospedagem->record[6])) {
        $dt_retirada = date('d/m/Y', strtotime($query_hospedagem->record[6]));
    } else {
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
    $pelagem                      = $query_hospedagem->record[11];
    $id_animal_count              = $query_hospedagem->record[12];
    $situacao                     = $query_hospedagem->record[13];
    $sexo                         = $query_hospedagem->record[14];
    $motivo                       = $query_hospedagem->record[15];
} else {
    $query_hospedagem = new Query($bd);
    $query_hospedagem->exec("SELECT 
        h.id_hospedagem , a.nro_ficha , a.nro_chip , h.dt_entrada , h.endereco_recolhimento, b.descricao , h.dt_retirada, 
        u.mes_referencia, u.ano_referencia,h.valor,e.descricao, r.nome,p.descricao,h.id_animal, h.situacao, a.sexo, m.descricao,
        r.cpf , r.rg, r.dt_nascimento, r.endereco, b.descricao, tc.descricao, rc.valor_contato, h.id_responsavel, h.nro_boleto
        FROM 
        animal a,  hospedagem as h, bairro as b , urm as u, especie as e, responsavel as r, pelagem as p, motivo as m, responsavel_contato as rc , tipo_contato as tc 
        WHERE 
        h.id_hospedagem = $id_hospedagem
        AND
        h.id_animal = a.id_animal 
        AND 
        h.id_bairro = b.id_bairro
        AND
        h.id_urm = u.id_urm
        AND
        h.id_motivo = m.id_motivo
        AND
        h.id_responsavel = r.id_responsavel
        AND 
        tc.id_tipo_contato = rc.id_tipo_contato
        AND
        e.id_especie = a.id_especie
        AND 
        p.id_pelagem = a.id_pelagem

        ");

    $query_hospedagem->result($query_hospedagem->linha);

    if (isset($query_hospedagem->record[6])) {
        $dt_retirada = date('d/m/Y', strtotime($query_hospedagem->record[6]));
    } else {
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
    $pelagem                      = $query_hospedagem->record[12];
    $id_animal_count              = $query_hospedagem->record[13];
    $situacao                     = $query_hospedagem->record[14];
    $sexo                         = $query_hospedagem->record[15];
    $motivo                       = $query_hospedagem->record[16];

    //Responsavel

    $responsavel_nome             = $query_hospedagem->record[11];
    $cpf                          = $query_hospedagem->record[17];
    $rg                           = $query_hospedagem->record[18];
    $dt_nascimento_responavel     = $query_hospedagem->record[19];
    $endereco_responsavel         = $query_hospedagem->record[20];
    $tipo_contato                 = $query_hospedagem->record[22];
    $valor_contato                = $query_hospedagem->record[23];
    $id_responsavel               = $query_hospedagem->record[24];
    $nro_boleto                   = $query_hospedagem->record[25];

    $contatos = new Query($bd);
    $contatos->exec("SELECT t.descricao, rc.valor_contato, rc.principal, rc.id_responsavel_contato, b.descricao
                                              FROM responsavel as r, responsavel_contato as rc, tipo_contato as t, bairro as b
                                              WHERE r.id_responsavel = rc.id_responsavel AND
                                                    b.id_bairro = 1 AND
                                                    t.id_tipo_contato = rc.id_tipo_contato AND
                                                    r.id_responsavel = " . $id_responsavel);
    $qnt = $contatos->rows() == 0 ? 1 : $contatos->rows();

    $query_bairro_responsavel = new Query($bd);
    $query_bairro_responsavel->exec("SELECT b.id_bairro ,b.descricao FROM bairro as b, responsavel as r WHERE b.id_bairro = r.id_bairro AND r.id_responsavel = $id_responsavel");
    $query_bairro_responsavel->proximo();
    $id_bairro_responsavel = $query_bairro_responsavel->record[0];
    $bairro_responsavel = $query_bairro_responsavel->record[1];
}

//Contagem de Reincidencias

$query_reincidencias = new Query($bd);

$query_reincidencias->exec("SELECT count(id_animal) FROM hospedagem WHERE  id_animal = '" . $id_animal_count . "' AND dt_retirada <= '" . $dt_entrada_reincidencia . "'");
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
            <div class="row">
                <div class="col-1">
                    <h2 class="info-box-icon " style="width: 30px;height:30px;">
                        <?
                        if ($situacao == "S") {
                            echo "<i class='fas fa-circle text-yellow'></i>";
                        } else {
                            echo "<i class='fas fa-circle text-green'></i>";
                        }
                        ?>
                    </h2>
                </div>
                <div class="col-12 col-md-3 px-3 ">
                    <div class="row">
                        <p class="mb-0"><b>Numero do atendimento:</b> <?= $id_hospedagem ?></p>
                    </div>
                    <div class="row">
                        <p class="mb-0"><b>Numero de Ficha: </b><?= $nro_ficha ?> </p>
                    </div>
                    <div class="row">
                        <p class="mb-0"><b>Numero do chip: </b><?= $nro_chip ?></p>
                    </div>

                    <div class="row">
                        <p><b>Situação:</b>
                            <? if ($situacao == "S") {
                                echo "<span class='text-yellow'>Em Atendimento</span>";
                            } else {
                                echo "<span class='text-green'>Atendimento Finalizado</span>";
                            } ?></p>
                    </div>

                </div>

               
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
                    <div class="card border">
                        <div class="card-header bg-green">
                        <i class="fas fa-dog pr-1"></i>
                            Dados do Animal
                        </div>

                        <div class="card-body">
                            <div class="row mx-0">
                                <div class="col-12 col-md-6">
                                    <div class="row">
                                        <p><b>Espécie:</b> <?= $especie_descricao; ?></p>
                                    </div>
                                    <div class="row">
                                        <p><b>Pelagem:</b> <?= $pelagem; ?></p>
                                    </div>
                                    <div class="row">
                                        <p><b>Sexo:</b> <?
                                                        if ($sexo == "F") {
                                                            echo "Fêmea";
                                                        } else {
                                                            echo "Macho";
                                                        };
                                                        ?>
                                        </p>
                                    </div>

                                    <div class="row">
                                        <p><b>Endereço de Recolhimento: </b><?= $endereco_recolhimento; ?> - <?= $bairro; ?></p>
                                    </div>

                                    <div class="row">
                                        <p><b>Data de Entrada:</b> <?= $dt_entrada; ?></p>
                                    </div>

                                    <div class="row">
                                        <p><b>Data de Retirada:</b> <?= $dt_retirada ?></p>
                                    </div>

                                </div>

                                <div class="col-12 col-md-6 ">
                                    <div class="row">
                                        <p><b>Responsável: </b> <? if (isset($id_responsavel)) echo $responsavel_nome; ?></p>
                                    </div>

                                    <div class="row">
                                        <p><b>Motivo: </b><?= $motivo ?></p>
                                    </div>

                                    <div class="row">
                                        <p><b>Reincidências: </b><?= $nro_reincidencias; ?> </p>
                                    </div>

                                    <div class="row">
                                        <p><b>URM Referência:</b> <?
                                                                    switch ($urm_mes) {
                                                                        case '1':
                                                                            echo "Janeiro" . $urm_ano;
                                                                            break;
                                                                        case '2':
                                                                            echo "Fevereiro/" . $urm_ano;
                                                                            break;
                                                                        case '3':
                                                                            echo "Março/" . $urm_ano;
                                                                            break;
                                                                        case '4':
                                                                            echo "Abril/" . $urm_ano;
                                                                            break;
                                                                        case '5':
                                                                            echo "Maio/" . $urm_ano;
                                                                            break;
                                                                        case '6':
                                                                            echo "Junho/" . $urm_ano;
                                                                            break;
                                                                        case '7':
                                                                            echo "Julho/" . $urm_ano;
                                                                            break;
                                                                        case '8':
                                                                            echo "Agosto/" . $urm_ano;
                                                                            break;
                                                                        case '9':
                                                                            echo "Setembro/" . $urm_ano;
                                                                            break;
                                                                        case '10':
                                                                            echo "Outubro/" . $urm_ano;
                                                                            break;
                                                                        case '11':
                                                                            echo "Novembro/" . $urm_ano;
                                                                            break;
                                                                        case '12':
                                                                            echo "Dezembro";
                                                                            break;
                                                                        default:
                                                                            "Nenhuma Urm de Referência";
                                                                            break;
                                                                    }
                                                                    ?>
                                        </p>
                                    </div>
                                    <div class="row">
                                        <p><b>Valor da Multa:</b> R$ <? if($nro_reincidencias == 0){echo $hospedagem_valor;}else{
                                           echo number_format($hospedagem_valor * $nro_reincidencias,2,',','.') ;} ?></p>
                                    </div>
                                    <div class="row">
                                        <p><b>Numero do Boleto:</b> <?= $nro_boleto; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?
            if (isset($id_responsavel)) {
            ?>
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card border">
                            
                                <div class="card-header bg-green col-12">
                                    <div class="row">
                                        <div class="col-md-6 text-left">
                                        <i class="fas fa-person pr-1"></i>
                                            Dados do Responsavel
                                        </div>
                                        <div class="col-md-6 text-right">

                                            <button type="button" class="btn bg-light btn-light btn-sm text-light" data-toggle="modal" data-target="#RESPONSAVEL_edit_modal" title="Editar Responsável">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>
                                        </div>
                                        
                                        
                                    </div>
                                
                                </div>

                            <div class="card-body">
                                <div class="row mx-0">
                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <p><b>Nome:</b> <?= $responsavel_nome; ?></p>
                                        </div>
                                        <div class="row">
                                            <p><b>Cpf:</b> <?= $cpf; ?></p>
                                        </div>
                                        <div class="row">
                                            <p><b>Rg:</b> <?= $rg; ?></p>
                                        </div>
                                        <div class="row">
                                            <p><b>Data de Nascimento:</b> <? if ($dt_nascimento_responavel != "null") {
                                                                                echo date('d/m/Y', strtotime($dt_nascimento_responavel));
                                                                            }  ?>
                                            </p>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <div class="">
                                            <p><b>Endereço:</b> <?= $endereco_responsavel ?> - <?= $bairro_responsavel ?> </p>
                                        </div>
                                        
                                        <?
                                            for ($c = 0; $c < $qnt; $c++) {
                                                $contatos->proximo();
                                                if (isset($contatos->record[0])) {
                                                    echo "<p><b>" . $contatos->record[0] . "</b>: " . $contatos->record[1];
                                                    if ($contatos->record[2] == "S") {
                                                        echo " <b class='text-green'> - Principal</b>";
                                                    }
                                                    "   </p>";
                                                }
                                            };
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?   }; ?>

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

</section>


<?
include_once('../includes/dashboard/footer.php');
include('../includes/modal/modal_hospedaria_edit_responsavel.php');

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

                    console.log(erro);

                }
            });
        });



    });
</script>