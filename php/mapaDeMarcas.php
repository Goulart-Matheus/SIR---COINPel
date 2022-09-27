<?php

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title><? echo $system->getTitulo(); ?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta content="SIR <? echo $system->getTitulo(); ?>" name="description">
    <meta content="Frederico Bueno Da Silva Schaun" name="author">

    <link rel="shortcut icon" href="../assets/images/logo-short.png">
    <link href="../assets/css/app.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/app.js"></script>
    <script src="../assets/js/script.js"></script>

    <?php include("../includes/mapbox.header.php")?>

    <style>
        body { margin: 0; padding: 0; width: 100%; height: 100%;}
	    #map { position: absolute; top: 0; bottom: 0; width: 100%; height: 100vh; }
        .marker {
            background-image: url('../img/alarme.png');
            background-size: cover;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
        }

    </style>
</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <div id="map">
                <?
                    $header = !isset($noHeader) ? true : false;

                    if($header){

                ?>
                    <div style="position: absolute; left: 0; z-index: 9999" class="col-12 m-0 p-0">
                        <nav class="navbar navbar-expand-lg navbar-dark bg-dark col-12">
                            <a href="../index.php" class="brand-link navbar-dark text-sm">
                                <img src="../assets/images/logo-short.png"
                                    alt="Logo <? echo $system->getTitulo(); ?>"
                                    class="brand-image"
                                    style="opacity: .8; margin-left: 0; margin-right: 8;">
                                <span class="brand-text font-weight-light"><? echo $system->getTitulo(); ?></span>
                            </a>

                            <div class="collapse navbar-collapse">
                                <ul id="filters" class="navbar-nav">
                                </ul>
                            </div>

                        </nav>

                    </div>

                <? } else { ?>

                        <div class="collapse navbar-collapse">
                            <ul id="filters" class="navbar-nav">
                            </ul>
                        </div>

                <? } ?>

            </div>
        </div>
    </div>

    <script language="javascript">
        var map;
        var markers = [<?php
            include('../function/function.query_foreach.php');

            $select =
                "SELECT l.*, p.id_proprietario, p.nome as nome_proprietario, p.desenho_marca
                FROM propriedade l JOIN proprietario p ON l.id_proprietario = p.id_proprietario";

            query_foreach($select, function($q) {
                // E.g: "(-31.760635190792186,-52.2732359795348)" => [-52.27..., -31.76...]
                $coords = $q->record['coordenadas'];
                $coords = substr($coords, 1, strlen($coords) - 2);
                $coords = explode(',', $coords);
                $coords = [ (float)$coords[1], (float)$coords[0] ];

                $icon_url = "../assets/images/marcas/{$q->record['desenho_marca']}";
                $icon_size = getimagesize($icon_url);
                $icon_max_px = 40;
                $icon_scale = $icon_max_px / max([$icon_size[0], $icon_size[1]]);

                echo json_encode([
                    "coords" => $coords,
                    "icon" => $icon_url,
                    "icon_scale" => $icon_scale,
                    "name" => $q->record['nome_proprietario'],
                    "property_name" => $q->record['nome'],
                ]);

                echo ",";
            });
        ?>];

        $(document).ready(function() {

            mapboxgl.accessToken = 'pk.eyJ1IjoicmFmYWVsZHR4IiwiYSI6ImNsODNlYTQ1cTA0eHczbm1mOHJ5eGVqZWgifQ.KBhV05aSsZP1Kp-hMLE2cA';

            map = new mapboxgl.Map({
                container: 'map',
                style    : 'mapbox://styles/mapbox/streets-v11',
                center   : [-52.34395415359246, -31.75916486759182],
                zoom     : 11,
            });

            var sources = {};
            var layers = {};
            var images = [];

            // 1 proprietario = 1 layer = 1 source = 1 ponto por propriedade
            markers.forEach(marker => {
                layer_name = `layer-${marker.name}`;
                source_name = `source-${marker.name}`;
                image_name = `img-${marker.name}`;

                if(!sources.hasOwnProperty(source_name)) {
                    sources[source_name] = {
                        'type': 'geojson',
                        'data': {
                            'type': 'FeatureCollection',
                            'features': []
                        }
                    }
                }
                if(!layers.hasOwnProperty(layer_name)) {
                    layers[layer_name] = {
                        'id': layer_name,
                        'type': 'symbol',
                        'source': source_name,
                        'layout': {
                            'icon-image': image_name,
                            'icon-size': marker.icon_scale,
                        }
                    };
                }
                if(!(image_name in images)) {
                    images.push(image_name);
                    (function (image_name) {
                        map.loadImage(marker.icon, (error, image) => {
                            if (error) throw error;
                            if(!map.hasImage(image_name)) { map.addImage(image_name, image); }
                        });
                    })(image_name);
                }

                sources[source_name].data.features.push({
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Point',
                        'coordinates': marker.coords
                    }
                });
            });

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
</body>
</html>
