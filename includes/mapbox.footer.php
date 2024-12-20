<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.3.0/mapbox-gl-geocoder.min.js'></script>
<script language="javascript">
    var centerCoordinates;
    var map;
    var marker;
    var lngLat;
    var geocoder;

    $(document).ready(function(){

        initialize(<?= $coordenada ?>)

        mapboxgl.accessToken = 'pk.eyJ1IjoiZWR1YXJkb2NhcnBlbmEiLCJhIjoiY2w3OTRhMzUxMDFyMTNucGNxdWF2M3h4ciJ9.LBhlBKBp4UGM-dgg3NUe2A';

        map = new mapboxgl.Map({
            container: 'map',
            style    : 'mapbox://styles/mapbox/satellite-streets-v11',
            center   : centerCoordinates,
            zoom     : <?= $zoom ?> // starting zoom
        });

        if($("div#map").attr('meta') == "marker"){
            map.on('click', addMarker);
        }

        marker = new mapboxgl.Marker({

            draggable: true

        }).setLngLat(centerCoordinates).addTo(map);

        marker.on('dragend', onDragEnd);

        geocoder = new MapboxGeocoder({
            accessToken   : mapboxgl.accessToken,
            marker        : true,
            mapboxgl      : mapboxgl
        }).on('keyup',addMarker);

        if($("div#map").attr('meta') == "marker"){
            map.addControl(geocoder,'top-left');
        }
    });

    function addMarker(e){
        lngLat = marker.getLngLat();
        console.log("LON: "+e.lngLat.lng, "\nLAT: "+e.lngLat.lat);
        marker.setLngLat([e.lngLat.lng, e.lngLat.lat]);
        $('#form_coordenada').val(e.lngLat.lat + ',' + e.lngLat.lng);
    }

    function onDragEnd() {
        lngLat = marker.getLngLat();
        $('#form_coordenada').val(lngLat.lat + ',' + lngLat.lng);
    }

    function initialize(x,y){
        centerCoordinates = [y,x];
        console.log("Coordenadas:" + centerCoordinates);
    }

    $('#form_coordenada').on('change', function(event) {
        var lnglat = $(this).val().split(',');
        lnglat = {lng: lnglat[1], lat: lnglat[0]};
        marker.setLngLat(lnglat);
        map.flyTo({center: lnglat});
    });
</script>
