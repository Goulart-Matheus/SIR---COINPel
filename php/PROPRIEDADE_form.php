<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Propriedades', 'fas fa-house', 'PROPRIEDADE_viewDados.php');
$tab->setTab('Nova Propriedade', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);
?>

<script src='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.0.0/mapbox-gl.css' rel='stylesheet' />
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.3.0/mapbox-gl-geocoder.css' type='text/css' />
<style type="text/css">
    .mapboxgl-ctrl-geocoder--input {
        font: inherit !important;
        width: 100% !important;
        border: 0 !important;
        background-color: transparent !important;
        margin: 0 !important;
        height: 32px !important;
        color: rgba(0, 0, 0, 0.75) !important;
        padding: 6px 45px !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        overflow: hidden !important;
    }
</style>

<section class="content">

    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">
                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?

                        if (isset($add)) {
                            include "../class/class.valida.php";

                            $valida_nome = new Valida($form_nome, 'Nome');
                            $valida_nome->TamMinimo(2);
                            $erro .= $valida_nome->PegaErros();
                        }

                        if (!$erro && isset($add)) {
                            $form_bovino = $form_bovino ?: 'N';
                            $form_equino = $form_equino ?: 'N';
                            $form_bubalino = $form_bubalino ?: 'N';

                            $query->begin();
                            $query->insertTupla(
                                'propriedade',
                                array(
                                    $form_proprietario,
                                    $form_nome,
                                    $form_coordenada,
                                    $form_bovino,
                                    $form_equino,
                                    $form_bubalino,
                                    $form_area,
                                    $_login, $_ip, $_datahora,
                                )
                            );
                            $query->commit();
                        }

                        if ($erro) echo callException($erro, 2);
                        ?>
                    </div>
                </div>
            </div>

            <?
            $coordenada = $form_coordenada ?: "-31.760486508611237,-52.33785820699613";
            $zoom = 9;
            ?>

            <div class="card-body pt-0">
                <div class="form-row">
                    <div class="form-group col-6">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="form_proprietario" class="mb-0 mt-1">Proprietário</label>
                                <select name="form_proprietario" class="form-control select2_proprietario">                                    
                                    <?
                                    $where="";
                                    include "../includes/inc_select_proprietario.php";
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="form_coordenada">Coordenada</label>
                                <input autocomplete="off" type="text" class="form-control" id="form_coordenada" name="form_coordenada" value="<?= $coordenada ?>" maxlength="100">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="form_nome">Nome da propriedade</label>
                                <input autocomplete="off" type="text" class="form-control" id="form_nome" name="form_nome" maxlength="50" value="<? if ($erro) echo $form_nome ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="form_area">Área (em Hectares)</label>
                                <input autocomplete="off" type="number" class="form-control" id="form_area" name="form_area" maxlength="10" step="0.01">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="form-check form-check-inline col-sm-3">
                                    <input type="checkbox" class="form-check-input" name="form_bovino" value="S">
                                    <label for="form_bovino">Bovino</label>
                                </div>
                                <div class="form-check form-check-inline col-sm-3">
                                    <input type="checkbox" class="form-check-input" name="form_equino" value="S">
                                    <label for="form_equino">Equino</label>
                                </div>
                                <div class="form-check form-check-inline col-sm-3">
                                    <input type="checkbox" class="form-check-input" name="form_bubalino" value="S">
                                    <label for="form_bubalino">Bubalino</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <div id='map' style='min-height: 300px; height: 100%' meta="marker"></div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light-2">
                <?
                $btns = array('clean', 'save');
                include('../includes/dashboard/footer_forms.php');
                ?>
            </div>
        </div>
    </form>
</section>

<? include_once('../includes/dashboard/footer.php'); ?>

<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.3.0/mapbox-gl-geocoder.min.js'></script>
<script language="javascript">
    var centerCoordinates;
    var map;
    var marker;
    var lngLat;
    var geocoder;

    $(document).ready(function() {

        initialize(<?= $coordenada ?>)

        mapboxgl.accessToken = 'pk.eyJ1IjoiZWR1YXJkb2NhcnBlbmEiLCJhIjoiY2w3OTRhMzUxMDFyMTNucGNxdWF2M3h4ciJ9.LBhlBKBp4UGM-dgg3NUe2A';

        map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/satellite-streets-v11',
            center: centerCoordinates,
            zoom: <?= $zoom ?> // starting zoom
        });

        if ($("div#map").attr('meta') == "marker") {
            map.on('click', addMarker);
        }

        marker = new mapboxgl.Marker({

            draggable: true

        }).setLngLat(centerCoordinates).addTo(map);

        marker.on('dragend', onDragEnd);

        geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            marker: true,
            mapboxgl: mapboxgl
        }).on('keyup', addMarker);

        if ($("div#map").attr('meta') == "marker") {
            map.addControl(geocoder, 'top-left');
        }
    });

    function addMarker(e) {
        lngLat = marker.getLngLat();
        console.log("LON: " + e.lngLat.lng, "\nLAT: " + e.lngLat.lat);
        marker.setLngLat([e.lngLat.lng, e.lngLat.lat]);
        $('#form_coordenada').val(e.lngLat.lat + ',' + e.lngLat.lng);
    }

    function onDragEnd() {
        lngLat = marker.getLngLat();
        $('#form_coordenada').val(lngLat.lat + ',' + lngLat.lng);
    }

    function initialize(x, y) {
        centerCoordinates = [y, x];
        console.log("Coordenadas:" + centerCoordinates);
    }

    $('#form_coordenada').on('change', function(event) {
        var lnglat = $(this).val().split(',');
        lnglat = {
            lng: lnglat[1],
            lat: lnglat[0]
        };
        marker.setLngLat(lnglat);
        map.flyTo({
            center: lnglat
        });
    });
    $(document).ready(function() {

        if ($(".select2_proprietario").length > 0) {
            $(".select2_proprietario").attr('data-live-search', 'true');

            $(".select2_proprietario").select2({
                width: '100%'
            });
        }
    });
</script>