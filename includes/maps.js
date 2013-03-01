<script type="text/javascript">
// Создание обработчика для события window.onLoad
YMaps.jQuery(function () {
    // Создание экземпляра карты и его привязка к созданному контейнеру
    var map = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);
    // Установка для карты ее центра и масштаба

	map.setCenter(new YMaps.GeoPoint(39.03014, 45.02587), 13);
    // Создание стиля для содержимого балуна
    var s = new YMaps.Style();
    s.balloonContentStyle = new YMaps.BalloonContentStyle(
        new YMaps.Template("<div style=\"float:left\"><img src=\"ext/build/images_base/fasad_ver2_small.jpg\"></div><div style=\"color:red\">$[description]</div>")
    );

    // Создание метки с пользовательским стилем и добавление ее на карту
    var placemark = new YMaps.Placemark(new YMaps.GeoPoint(39.065845,45.005354), {style: s} );
    placemark.description = "Магазин Жирафик,<br /> ул. Парусная, 20";
    map.addOverlay(placemark);

    map.addControl(new YMaps.TypeControl());

    map.addControl(new YMaps.Zoom());
    map.addControl(new YMaps.MiniMap());
    
    
    // Открытие балуна
    placemark.openBalloon();
});
</script>
