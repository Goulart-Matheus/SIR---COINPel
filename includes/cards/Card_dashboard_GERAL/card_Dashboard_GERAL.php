<?

include("../../../includes/mapbox.header.php");
$_data;
var_dump($filter);
if ($dados_ano != "") {
    $ano_corrente = $dados_ano;
   
} else {
    $ano_corrente = explode('-', $_data)[0];
 
}

$mes_corrente = explode('-', $_data)[1];

$query_quantidade = new Query($bd);

$query_quantidade->exec("SELECT count(id_hospedagem)        
              FROM hospedagem
              WHERE EXTRACT(YEAR FROM dt_entrada) = $ano_corrente ");

$query_quantidade->proximo();
$animais_atendidos = $query_quantidade->record[0];


$query_ativos = new Query($bd);
$query_ativos->exec("SELECT count(id_hospedagem)        
              FROM hospedagem WHERE situacao = 'S' AND EXTRACT(YEAR FROM dt_entrada) = $ano_corrente");

$query_ativos->proximo();
$animais_ativos = $query_ativos->record[0];

$query_permanencia = new Query($bd);


$query_permanencia->exec("SELECT SUM(ABS(DATE_PART('day', dt_retirada) - DATE_PART('day', dt_entrada)))
              FROM hospedagem WHERE situacao = 'N' AND EXTRACT(YEAR FROM dt_entrada) = $ano_corrente");

$query_permanencia->proximo();
$animais_permanencia = $query_permanencia->record[0] / ($animais_atendidos - $animais_ativos);


$query_bairros = new Query($bd);
$query_bairros_individual = new Query($bd);

$bairros_atendimentos = [];
$bairros_atendimentos_individual = [];
$mes = 1;
$mes_bairro = 1;
while ($mes <= $mes_corrente) {

    $query_bairros->exec("SELECT count(a.id_animal) 
                      FROM hospedagem as h, animal as a	
					  WHERE a.id_animal = h.id_animal AND 
                            EXTRACT(MONTH FROM h.dt_entrada)=$mes AND EXTRACT(YEAR FROM h.dt_entrada) = $ano_corrente");

    $c = $query_bairros->rows();
    if ($query_bairros->rows() > 0) {

        while ($c--) {
            $query_bairros->proximo();
            $bairros_atendimentos[$ano_corrente][$mes] = $query_bairros->record[0];
        }
        $mes++;
    }


    $query_bairros_individual->exec("SELECT b.descricao, (SELECT count(a.id_animal) FROM animal as a,hospedagem as h WHERE a.id_animal = h.id_animal AND b.id_bairro = h.id_bairro AND EXTRACT(MONTH FROM h.dt_entrada)=$mes_bairro AND EXTRACT(YEAR FROM h.dt_entrada) = $ano_corrente)
                      FROM bairro as b 			  			  
                      ORDER BY b.descricao");

    $c2 = $query_bairros_individual->rows();

    if ($c2 > 0) {

        $controle = 0;
        while ($c2--) {
            $query_bairros_individual->proximo();
            if ($mes_bairro == 1) {

                $bairros_atendimentos_individual[$controle][$mes_bairro - 1] = $query_bairros_individual->record[0];
                $bairros_atendimentos_individual[$controle][$mes_bairro] = intval($query_bairros_individual->record[1]);
            } else {

                $bairros_atendimentos_individual[$controle][$mes_bairro] = intval($query_bairros_individual->record[1]);
            }

            $controle++;
        }

        $mes_bairro++;
    }
}

$query_bairros_total = new Query($bd);
$query_bairros_total->exec("SELECT b.descricao, (SELECT count(a.id_animal) FROM animal as a,hospedagem as h WHERE a.id_animal = h.id_animal AND b.id_bairro = h.id_bairro AND EXTRACT(YEAR FROM h.dt_entrada) = $ano_corrente)
                      FROM bairro as b 			  			  
                      ORDER BY b.descricao");

$bairros_total = [];
$c = $query_bairros_total->rows();
if ($query_bairros_total->rows() > 0) {

    while ($c--) {
        $query_bairros_total->proximo();
        $bairros_total[$query_bairros_total->record[0]] = $query_bairros_total->record[1];
    }
}

$query_especies = new Query($bd);
$query_especies->exec("SELECT e.descricao, (SELECT count(a.id_especie) FROM animal as a,hospedagem as h WHERE a.id_animal = h.id_animal AND a.id_especie = e.id_especie AND EXTRACT(YEAR FROM h.dt_entrada) = $ano_corrente)
                       FROM especie as e 			  			  
                       ORDER BY e.descricao");

$especies = [];
$c = $query_especies->rows();
if ($query_especies->rows() > 0) {

    while ($c--) {
        $query_especies->proximo();
        $especies[$query_especies->record[0]] = $query_especies->record[1];
    }
}

?>

<div class="card">

    <div class="card-body" style="@height: 400px;">

        <div class="row">

            <div class="col-12 col-md-3 text-center rounded">

                <div class="info-box shadow">

                    <span class="info-box-icon  bg-green"><i class="fa-solid fa-calendar-days"></i></span>

                    <div class="info-box-content">

                        <span class="info-box-text text-green">Ano</span>

                        <?= $ano_corrente ?>

                    </div>

                </div>

            </div>

            <div class="col-12 col-md-3 text-center rounded">

                <div class="info-box shadow">

                    <span class="info-box-icon  bg-green"><i class="fa-solid fa-house-medical-circle-check"></i></span>

                    <div class="info-box-content">

                        <span class="info-box-text text-green">Total de Atendimentos</span>

                        <?= $animais_atendidos ?>

                    </div>

                </div>

            </div>

            <div class="col-12 col-md-3 text-center rounded">

                <div class="info-box shadow">

                    <span class="info-box-icon  bg-green"><i class="fa-solid fa-house-medical-circle-exclamation"></i></span>

                    <div class="info-box-content">

                        <span class="info-box-text text-green">Atualmente Recolhidos</span>

                        <?= $animais_ativos ?>

                    </div>

                </div>

            </div>

            <div class="col-12 col-md-3 text-center rounded">

                <div class="info-box shadow">

                    <span class="info-box-icon  bg-green"><i class="fas fa-history"></i></span>

                    <div class="info-box-content">

                        <span class="info-box-text text-green">Permanência Média</span>

                        <? if ((intval($animais_permanencia)) >= 1) {
                            if (($animais_permanencia - intval($animais_permanencia)) * 10 > 1) {
                                echo intval($animais_permanencia) . 'D  ' . intval(($animais_permanencia - intval($animais_permanencia)) * 24) . 'H';
                            } else {
                                echo intval($animais_permanencia) . 'D';
                            }
                        } else {
                            if (($animais_permanencia - intval($animais_permanencia)) * 10 > 1) {
                                echo intval(($animais_permanencia - intval($animais_permanencia)) * 24) . 'H';
                            }
                        }
                        ?>

                    </div>

                </div>

            </div>

        </div>
        <div class="card border">

            <div class="card-header bg-gradient-green pt-2 pb-2 text-left">
                <div class="row">
                    <div class="col-6 col-md-6 text-left pt-1">
                        <i class="fas fa-chart-area"></i>
                        Atendimentos <? echo  $ano_corrente ?>
                    </div>
                    <div class="col-6 col-md-6 text-right pr-0">
                        <button type="button" class="btn btn-sm btn-light text-light" data-toggle="modal" data-target="#atualiza_ano">
                            <i class="fas fa-search text-green"></i>
                        </button>
                    </div>
                </div>

            </div>


            <div class="card-body">
                <div id="grafico_bairro"></div>
            </div>

        </div>

        <div class="row">

            <div class="col-12 col-md-6" style="padding-right: 8px;">

                <div class="card border">

                    <div class="card-header bg-gradient-green pt-2 pb-2 text-left">
                        <i class="fas fa-chart-area"></i>
                        Atendimentos por Bairro
                    </div>

                    <div class="card-body">
                        <div id="grafico_bairro_total"></div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-md-6" style="padding-left: 8px;">

                <div class="card border">

                    <div class="card-header bg-gradient-green pt-2 pb-2 text-left">
                        <i class="fas fa-chart-area"></i>
                        Atendimentos por Espécie
                    </div>

                    <div class="card-body">
                        <div id="grafico_especie"></div>
                    </div>

                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-12 col-md-12">
                <div class="card border">

                    <div class="card-header bg-gradient-green pt-2 pb-2 text-left">
                        <i class="fa-solid fa-map-location-dot"></i>
                        Mapa de Marcas
                    </div>

                    <div class="card-body">
                        <div id="map2" style='min-height: 300px; height: 100% !important'>

                          

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade text-left" id="atualiza_ano" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-md" role="document">

        <div class="modal-content">

            <form method="post" action="<?= $_SERVER['PHP_SELF'] . "?dados_ano=".$ano_corrente_filtro."" ?> ">

                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Ano para Exibição
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">

                        <div class="form-group col-12 col-md-12">
                            <label for="ano_corrente_filtro"></span> Ano</label>

                            <select name="ano_corrente_filtro" id="ano_corrente_filtro" class="form-control">
                                <?
                                $query->exec("SELECT DISTINCT EXTRACT(YEAR FROM dt_entrada)
                                                FROM hospedagem
                                                ORDER BY EXTRACT(YEAR FROM dt_entrada) DESC	  			  
                                                ");
                                if ($query->rows() > 0) {
                                    $ax = $query->rows();

                                    while ($ax--) {
                                        $query->proximo();
                                ?>
                                        <option value=<? echo $query->record[0] ?>><? echo $query->record[0] ?></option>
                                <?
                                    }
                                }

                                ?>
                            </select>

                        </div>

                    </div>


                </div>

                <div class="modal-footer bg-light-2 text-center">
                    <button type="submit" name="filter" class="btn btn-light" value="1">
                        <i class="fa-solid fa-filter text-green"></i>
                        Filtrar
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js'></script>


<script src="https://d3js.org/d3.v6.min.js"></script>
<script src="../../../assets/js/billboard.js"></script>

<script type="text/javascript">
    var meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

    var bairros_atendimentos_individual = <?= json_encode($bairros_atendimentos_individual) ?>;
   
    var ano_corrente = <?= $ano_corrente ?>;

    // Total
    var bairros_atendimentos = <?= json_encode($bairros_atendimentos) ?>;
    var grafico_atendimento = [];
    var dados = [];
    var dados_data = [];
    var data1 = [];

    Object.keys(bairros_atendimentos[ano_corrente]).forEach(function(valores, index) {
        grafico_atendimento.push(Number(bairros_atendimentos[ano_corrente][valores]));
        data1.push(meses[index]);
    })

    grafico_atendimento.unshift("Total");
    data1.unshift("x");
    dados[0] = grafico_atendimento;
    dados_data[0] = data1;

    // Bairros
    var bairros_total = <?= json_encode($bairros_total) ?>;
    var dados_bairro = [];
    var dados_bairro_ax = [];

    Object.keys(bairros_total).forEach(function(valores, index) {

        dados_bairro_ax.push(Number(bairros_total[valores]));
        dados_bairro_ax.unshift(valores);
        dados_bairro[index] = dados_bairro_ax;
        dados_bairro_ax = [];

    })



    // Espécies
    var especies_total = <?= json_encode($especies) ?>;
    var dados_especie = [];
    var dados_especie_ax = [];

    Object.keys(especies_total).forEach(function(valores, index) {

        dados_especie_ax.push(Number(especies_total[valores]));
        dados_especie_ax.unshift(valores);
        dados_especie[index] = dados_especie_ax;
        dados_especie_ax = [];

    })

    // Total
    var chart = bb.generate({
        data: {
            x: "x",
            columns: [
                dados_data[0],
                dados[0], <?

                            $contador = sizeof($bairros_atendimentos_individual);
                            $indice = 0;
                            while ($contador--) {

                                echo "bairros_atendimentos_individual[$indice]" . ',';

                                $indice++;
                            }
                            ?>

            ],
            types: {
                Total: "line",

            },
            labels: true,
        },
        axis: {
            x: {
                type: "category",

            }
        },
        zoom: {
            enabled: true, // for ESM specify as: zoom()
            type: "drag"
        },

        bindto: "#grafico_bairro"
    });

    // Bairro
    var chart = bb.generate({
        data: {
            columns: dados_bairro,
            type: "donut",

        },
        donut: {
            label: {
                format: function(value, ratio, id) {
                    return value;
                }
            },
            title: "",
            padAngle: 0.1
        },

        bindto: "#grafico_bairro_total"
    });

    // Espécie
    var chart = bb.generate({
        data: {
            columns: dados_especie,
            type: "bar",
            labels: true,
        },

        bindto: "#grafico_especie"
    });
    
</script>

<script language="javascript">
        // var map;
        // var markers = [<?php
        //     include('../../function/function.query_foreach.php');

        //     $select =
        //         "SELECT l.*, p.id_proprietario, p.nome as nome_proprietario, p.desenho_marca
        //         FROM propriedade l JOIN proprietario p ON l.id_proprietario = p.id_proprietario";

        //     query_foreach($select, function($q) {
        //         // E.g: "(-31.760635190792186,-52.2732359795348)" => [-52.27..., -31.76...]
        //         $coords = $q->record['coordenadas'];
        //         $coords = substr($coords, 1, strlen($coords) - 2);
        //         $coords = explode(',', $coords);
        //         $coords = [ (float)$coords[1], (float)$coords[0] ];

        //         $icon_url = "../assets/images/marcas/{$q->record['desenho_marca']}";
        //         $icon_size = getimagesize($icon_url);
        //         $icon_max_px = 40;
        //         $icon_scale = $icon_max_px / max([$icon_size[0], $icon_size[1]]);

        //         echo json_encode([
        //             "coords" => $coords,
        //             "icon" => $icon_url,
        //             "icon_scale" => $icon_scale,
        //             "name" => $q->record['nome_proprietario'],
        //             "property_name" => $q->record['nome'],
        //         ]);

        //         echo ",";
        //     });
        // ?>];

        $(document).ready(function() {

            mapboxgl.accessToken = 'pk.eyJ1IjoicmFmYWVsZHR4IiwiYSI6ImNsODNlYTQ1cTA0eHczbm1mOHJ5eGVqZWgifQ.KBhV05aSsZP1Kp-hMLE2cA';

            map = new mapboxgl.Map({
                container: 'map2',
                style    : 'mapbox://styles/mapbox/streets-v11',
                center   : [-52.34395415359246, -31.75916486759182],
                zoom     : 11,
            });

            var sources = {};
            var layers = {};
            var images = [];

            // 1 proprietario = 1 layer = 1 source = 1 ponto por propriedade
            // markers.forEach(marker => {
            //     layer_name = `layer-${marker.name}`;
            //     source_name = `source-${marker.name}`;
            //     image_name = `img-${marker.name}`;

            //     if(!sources.hasOwnProperty(source_name)) {
            //         sources[source_name] = {
            //             'type': 'geojson',
            //             'data': {
            //                 'type': 'FeatureCollection',
            //                 'features': []
            //             }
            //         }
            //     }
            //     if(!layers.hasOwnProperty(layer_name)) {
            //         layers[layer_name] = {
            //             'id': layer_name,
            //             'type': 'symbol',
            //             'source': source_name,
            //             'layout': {
            //                 'icon-image': image_name,
            //                 'icon-size': marker.icon_scale,
            //             }
            //         };
            //     }
            //     if(!(image_name in images)) {
            //         images.push(image_name);
            //         (function (image_name) {
            //             map.loadImage(marker.icon, (error, image) => {
            //                 if (error) throw error;
            //                 if(!map.hasImage(image_name)) { map.addImage(image_name, image); }
            //             });
            //         })(image_name);
            //     }

            //     sources[source_name].data.features.push({
            //         'type': 'Feature',
            //         'geometry': {
            //             'type': 'Point',
            //             'coordinates': marker.coords
            //         }
            //     });
            // });

            map.on('load', function() {
                for (const [source_name, geojson] of Object.entries(sources)) {
                    map.addSource(source_name, geojson);
                }

                for(const [_, layer_settings] of Object.entries(layers)) {
                    map.addLayer(layer_settings)
                }
            });
        });
    </script>