<?

include('../includes/session.php');
include('../class/class.report.php');
include('../includes/variaveisAmbiente.php');

$query->exec("SELECT COUNT(*) 
                FROM canil.tutor_animais 
                WHERE ativo<>'E';");
$query->proximo();
$TotalAnimaisCadastrados = $query->record[0];

$query->exec("SELECT COUNT(*) 
                FROM canil.tutor_animais
                WHERE dt_castracao IS NULL AND ativo<>'E';");
$query->proximo();
$TotalAnimaisAguardando = $query->record[0];

$query->exec("SELECT COUNT(*) 
                FROM canil.tutor_animais 
                WHERE nro_chip IS NOT NULL AND ativo<>'E';");
$query->proximo();
$TotalAnimaisCastrados = $query->record[0];

$query->exec("SELECT COUNT(*) 
                    FROM canil.tutor
                    WHERE tipo='T';");
$query->proximo();
$TotalTutores = $query->record[0];

$query->exec("SELECT COUNT(*) 
                    FROM canil.tutor
                    WHERE tipo='P';");
$query->proximo();
$TotalProtetores = $query->record[0];

include('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Painel de Controle do Canil', 'fas fa-file-code', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

?>

<style>
    * {
        margin: 3;
        padding: 3;
    }

    #chart-container {
        position: relative;
        width: 80vh;
        height: 40vh;
        overflow: hidden;
    }

    #chart-container2 {
        position: relative;
        width: 80vh;
        height: 40vh;
        overflow: hidden;
    }

    #chart-container3 {
        position: relative;
        width: 80vh;
        height: 40vh;
        overflow: hidden;
    }

    #chart-container4 {
        position: relative;
        width: 80vh;
        height: 40vh;
        overflow: hidden;
        
    }

</style>

<div class="card p-0 mx-2">

    <div class="card-header bg-light-2">
        <i class="fas fa-paw text-green"></i> Informações do Canil
    </div>

    <div class="card-body">

        <div class="form-row">

            <div class="form-group col-12 col-md-6">
                <label for="form_vendames">Animais Cadastrados por Espécie</label>
                <div class="col-12" id="chart-container"></div>
            </div>

            <div class="form-group col-12 col-md-6">
                <label for="form_vendames">Animais Cadastrados por Sexo</label>
                <div class="col-12" id="chart-container2"></div>
            </div>

        </div>

        <div class="form-row mt-3">

            <div class="form-group col-12 col-md-6">
                <label for="form_vendames">Total Castrações Público Alvo</label>
                <div class="col-12" id="chart-container3"></div>
            </div>

            <div class="form-group col-12 col-md-6">
                <label for="form_vendames">Total Castrações por Bairro (10+)</label>
                <div class="col-12" id="chart-container4"></div>
            </div>

        </div>

    </div>

</div>

<script src="https://d3js.org/d3.v6.min.js"></script>
<script src="https://fastly.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js"></script>

<style>
    /* Estilo para as células e linhas */
    table.texto td,
    table.texto th {
        border-bottom: 1px solid #E6E6FA;
        /* Apenas a borda inferior */
        padding: 5px;
        /* Espaçamento interno */
    }
</style>

<div class="card p-0 mx-2">
    <div class="card-body table-responsive">
        <table width="95%" class="texto" border="0" cellspacing="1" cellpadding="3" align="center">
            <tr bgcolor="<?php echo $_GLOBALS['color_select']; ?>" class="texton bg-light">
                <td align="left" class="text-green"><strong>Parâmetros</strong></td>
                <td align="left" class="text-green"><strong>Quant.</strong></td>
            </tr>

            <tr class="entered">
                <td align="left" width="200">Animais Cadastrados</td>
                <td><strong><?= $TotalAnimaisCadastrados ?></strong></td>
            </tr>
            <tr class="entered">
                <td align="left">Animais Aguardando </td>
                <td><strong><?= $TotalAnimaisAguardando ?></strong></td>
            </tr>
            <tr class="entered">
                <td align="left">Total de Castrações </td>
                <td><strong><?= $TotalAnimaisCastrados ?></strong></td>
            </tr>
            <tr class="entered">
                <td align="left">Tutores</td>
                <td><strong><?= $TotalTutores ?></strong></td>
            </tr>
            <tr class="entered">
                <td align="left">Protetores</td>
                <td><strong><?= $TotalProtetores ?></strong></td>
            </tr>
        </table>
    </div>
    <?
    $query->exec("SELECT TO_CHAR(dt_agendamento,'dd/mm/yyyy') as dt_agendamento_f,count(*) as total,dt_agendamento
                from canil.tutor_animais
                where dt_agendamento>current_date
                group by dt_agendamento
                order by dt_agendamento");
    $n = $query->rows();
    if ($n > 0) { ?>

        <div class="card-body table-responsive">
            <table width="95%" class="texto" border="0" cellspacing="1" cellpadding="3" align="center">
                <tr class="texton">
                    <h5 class="text-center" colspan="2"><strong>Próximos Agendamentos</strong></h5>
                </tr>
                <tr bgcolor="<?php echo $_GLOBALS['color_select']; ?>" class="texton bg-light">
                    <td width="200" align="left" class="text-green"><strong>Data</strong></td>
                    <td align="left" class="text-green"><strong>Quantidade</strong></td>
                </tr>
                <?
                while ($n--) {
                    $query->proximo();
                    $js_onclick = "onclick=javascript:window.location=('viewCanilConsultaCastracoesDados.php?form_dt_a_i=" . $query->record[2] . "&form_dt_a_f=" . $query->record[2] . "')";
                ?>
                    <tr class="entered">
                        <td <?= $js_onclick ?> align="left"><?= $query->record["dt_agendamento_f"]; ?></td>
                        <td <?= $js_onclick ?> align="left"><strong><?= $query->record["total"]; ?></strong></td>
                    </tr><?
                        }
                            ?>
            </table>
            <br />

        </div>
    <?
    }
    ?>
    <?
    $query->exec("SELECT m2.id_macroregiao as id,m2.descricao,COUNT(*)  as animais_aguardando_castracao
              FROM canil.tutor t inner join microregiao m1 using(id_microregiao) 
                                 inner join macroregiao m2 using(id_macroregiao) 
                                 inner join canil.tutor_animais ta using(id_tutor)
              WHERE ta.ativo='S' AND
                    ta.dt_castracao IS NULL
                    group by 1
              ORDER BY 1");
    $n = $query->rows();
    if ($n > 0) { ?>

        <div class="card-body table-responsive">
            <table width="95%" class="texto mb-5" border="0" cellspacing="1" cellpadding="3" align="center">
                <tr class="texton">
                    <h5 class="text-center" colspan="2"><strong>Animais Aguardando Castração</strong></h5>
                </tr>
                <tr bgcolor="<?php echo $_GLOBALS['color_select']; ?>" class="texton bg-light">
                    <td width="200" align="left" class="text-green"><strong>Macroregião</strong></td>
                    <td align="left" class="text-green"><strong>Quantidade</strong></td>
                </tr>
                <?
                while ($n--) {
                    $query->proximo();
                    $js_onclick = "onclick=javascript:window.location=('CANILCOSULTA_viewDados.php?form_id_macroregiao=" . $query->record["id"] . "&form_status=A')";
                ?>
                    <tr class="entered">
                        <td <?= $js_onclick ?> align="left"><?= $query->record["descricao"]; ?></td>
                        <td <?= $js_onclick ?> align="left"><strong><?= $query->record["animais_aguardando_castracao"]; ?></strong></td>
                    </tr><?
                        }
                            ?>
            </table>
            <br />
        </div>

</div>
<?
    }
?>



<?
$query->exec("SELECT CASE 
                        WHEN sexo='M' THEN 'Macho'
                        WHEN sexo='F' THEN 'Fêmea'
                        ELSE 'Erro' END AS sexo,
                        COUNT(*) 
                FROM canil.tutor_animais 
                WHERE ativo<>'E'
                GROUP BY 1;");
$n = $query->rows();

$aux1 = "";
while ($n--) {
    $query->proximo();
    $aux1 .= "{value:" . $query->record[1] . ", name:'" . $query->record[0] . "'},";
    
}

$query->exec("SELECT especie,
                    COUNT(*) 
                FROM canil.tutor_animais 
                WHERE ativo<>'E'
                GROUP BY 1;");
$n = $query->rows();
$aux2 = "";

while ($n--) {
    $query->proximo();
    $aux2 .= "{value:" . $query->record[1] . ", name:'" . $query->record[0] . "'},";
}


$query->exec("  SELECT CASE 
                        WHEN a.tipo='T' THEN 'Tutor'
                        WHEN a.tipo='P' THEN 'Protetor'
                        WHEN a.tipo='F' THEN 'Fiscalização'
                        WHEN a.tipo='C' THEN 'Canil'
                        ELSE 'Erro' END AS tipo,
                        COUNT(*) 
                FROM canil.tutor a INNER JOIN canil.tutor_animais b USING(id_tutor)
                WHERE b.nro_chip IS NOT NULL AND b.ativo<>'E'
                GROUP BY 1;");
$n = $query->rows();
$aux3 = "";

while ($n--) {
    $query->proximo();
    $aux3 .= "{value:" . $query->record[1] . ", name:'" . $query->record[0] . "'},";
}
$query->exec(" SELECT c.descricao,
                        COUNT(*) 
                FROM canil.tutor a 
                    INNER JOIN canil.tutor_animais b USING(id_tutor)
                    INNER JOIN microregiao c USING(id_microregiao)

                WHERE b.nro_chip IS NOT NULL AND b.ativo<>'E'
                GROUP BY 1
                ORDER BY 2 DESC
                LIMIT 15;");
$n = $query->rows();
$aux4 = "";
$contador = 0;
$TotalOutros = 0;
while ($n--) {
    $query->proximo();
    if ($contador < 10)  $aux4 .= "{value:" . $query->record[1] . ", name:'" . $query->record[0] . "'},";
    else $TotalOutros += $query->record[1];
    $contador++;
}
$aux4 .= "['Outros'" . "," . $TotalOutros . "],";

?>

<script>

    // Gráfico 1

    var dom = document.getElementById('chart-container');
    var myChart = echarts.init(dom, null, {
    renderer: 'canvas',
    useDirtyRect: false
    });
    var app = {};

    var option;

    option = {
    tooltip: {
        trigger: 'item'
    },
    legend: {
        orient: 'vertical',
        left: 'left'
    },
    series: [
        {
        type: 'pie',
        radius: '50%',
        
        data: [
            <? echo($aux1) ?>
        ],
        emphasis: {
            itemStyle: {
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
        }
        }
    ]
    };


    if (option && typeof option === 'object') {
    myChart.setOption(option);
    }

    window.addEventListener('resize', myChart.resize);

    // Gráfico 2

    var dom = document.getElementById('chart-container2');
    var myChart = echarts.init(dom, null, {
    renderer: 'canvas',
    useDirtyRect: false
    });
    var app = {};

    var option;

    option = {
    tooltip: {
        trigger: 'item'
    },
    legend: {
        orient: 'vertical',
        left: 'left'
    },
    series: [
        {
       
        type: 'pie',
        radius: '50%',
        data: [
            <? echo($aux2) ?>
        ],
        emphasis: {
            itemStyle: {
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
        }
        }
    ]
    };


    if (option && typeof option === 'object') {
    myChart.setOption(option);
    }

    window.addEventListener('resize', myChart.resize);

    // Gráfico 3

    var dom = document.getElementById('chart-container3');
    var myChart = echarts.init(dom, null, {
    renderer: 'canvas',
    useDirtyRect: false
    });
    var app = {};

    var option;

    option = {
    tooltip: {
        trigger: 'item'
    },
    legend: {
        orient: 'vertical',
        left: 'left'
    },
    series: [
        {
       
        type: 'pie',
        radius: '50%',
        data: [
            <? echo($aux3) ?>
        ],
        emphasis: {
            itemStyle: {
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
        }
        }
    ]
    };


    if (option && typeof option === 'object') {
    myChart.setOption(option);
    }

    window.addEventListener('resize', myChart.resize);

        // Gráfico 4

        var dom = document.getElementById('chart-container4');
    var myChart = echarts.init(dom, null, {
    renderer: 'canvas',
    useDirtyRect: false
    });
    var app = {};

    var option;

    option = {
    tooltip: {
        trigger: 'item'
    },
    legend: {
        orient: 'horizontal',
        left: 'left'
        
    },
    series: [
        {
        type: 'pie',
        radius: '50%',
        center: ['50%', '60%'],
        data: [
            <? echo($aux4) ?>
        ],
        emphasis: {
            itemStyle: {
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
        }
        }
    ]
    };


    if (option && typeof option === 'object') {
    myChart.setOption(option);
    }

    window.addEventListener('resize', myChart.resize);
    
</script>
<? include('../includes/dashboard/footer.php'); ?>