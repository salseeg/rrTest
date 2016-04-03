/**
 * Created by salseeg on 03.04.16.
 */
$(function(){
    var $map = $('#mapPane');
    var $button = $('#panel button');
    var gMap = null;
    var overlays = [];
    var mapNotams = {};

    var initMap = function(){
        var mapProp = {
            center:new google.maps.LatLng(51.508742,-0.120850),
            zoom:5,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        gMap  = new google.maps.Map($map.get(0),mapProp);
    };
    var clearOverlays = function(){
        mapNotams = {};
        while(overlays[0]) {
            overlays.pop().setMap(null);
        }
    };

    var renderMarkers = function(){
        var bounding = new google.maps.LatLngBounds();
        var icon = {
            url: '/exclamation-icon.png',
            size: new google.maps.Size(32, 28),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(15, 20)
        };
        var shape = {
            coords: [1, 28, 15, 0, 31, 28],
            type: 'poly'
        };

        var makeMarker = function(point){
            return new google.maps.Marker({
                position: point,
//                        icon:'exclamation-icon.png'
                icon: icon,
                shape: shape
            });
        };
        var makeCircle = function(point, radius){
            return new google.maps.Circle({
                center: point,
                radius: radius,
                strokeColor:"#ff0",
                strokeOpacity: 0.8,
                strokeWeight: 1.5,
                fillColor: "#ff0",
                fillOpacity: 0.45
            });
        };
        var makeInfo = function(point, content){
            return new google.maps.InfoWindow({
                content: content,
                position: point     // for correct placement on circle
            });
        };

        _.each(mapNotams, function(place){
            var point = new google.maps.LatLng(place.spot.latitude, place.spot.longitude);
            bounding.extend(point);
            var marker = place.spot.radius
                    ? makeCircle(point, place.spot.radius)
                    : makeMarker(point)
                ;
            var content = place.messages.join('<hr>');

            marker.setMap(gMap);

            google.maps.event.addListener(marker, 'click', function() {
                makeInfo(point, content).open(gMap);
            });
        });
        gMap.fitBounds(bounding);
    };

    var onNotamsRecieved = function(x){
//                console.log(x);
        clearOverlays();
        if (! x.error){
//                    mapNotams = {};
            _.each(x.notams, function(list){
                _.each(list, function(notam){
                    var coordinates = notam.coords;
                    if (! mapNotams[coordinates]){
                        mapNotams[coordinates] = {
                            spot: notam.spot,
                            messages: [notam.message]
                        };
//                                console.log(mapNotams);
                    }else{
                        mapNotams[coordinates].messages.push(notam.message);
                    }
                });
            });
        }
//                console.log(mapNotams);
        renderMarkers();
        $button.prop('disabled', false);
    };
    var onGetNotams = function(){
        $button.prop('disabled', true);
        $.post(
            '/',
            {
                codes: $('#panel textarea').val()
            },
            onNotamsRecieved,
            'json'
        );
        clearOverlays();
    };

    $button.click(onGetNotams);
    initMap();

    setTimeout(function(){
        $button.click();
    }, 5000);
});
