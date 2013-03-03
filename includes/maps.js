<script type="text/javascript">
function fid_135179447786766516408(ymaps) {
    var map = new ymaps.Map("ymaps-map-id_135179447786766516408", {
        center: [39.029177444091786, 45.027891149811886],
        zoom: 12,
        type: "yandex#map"
    });
    map.controls
        .add("zoomControl")
        .add("mapTools")
        .add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
    map.geoObjects
        .add(new ymaps.Placemark([39.076041, 45.027495], {
            balloonContent: "Магазин Жирафик"
        }, {
            preset: "twirl#orangeDotIcon"
        }));
};
</script>
