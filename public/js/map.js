/**
 * Created by salseeg on 03.04.16.
 */
$(function(){
    var $map = $('#mapPane');
    var $error = $('div.error');
    var $button = $('#panel').find('button');
    var $textarea = $('#panel').find('textarea');
    var gMap = null;
    var overlays = [];
    var mapNotams = {};
    var _wrongIcaoTpl = _.template('Wrong ICAO code : <b><%= code %></b>');
    var _serverErrorTpl = _.template('<b><%= error %></b>');

    var initMap = function(){
        var mapProp = {
            center:new google.maps.LatLng(51.508742,-0.120850),
            zoom:5,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        gMap  = new google.maps.Map($map.get(0),mapProp);
    };
    var clearOverlays = function(){
        console.log('should clear');
        while(overlays[0]) {
            overlays.pop().setMap(null);
        }
        mapNotams = {};
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
                position: point     // for correct placement
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
            overlays.push(marker);

            google.maps.event.addListener(marker, 'click', function() {
                makeInfo(point, content).open(gMap);
            });
        });
        gMap.fitBounds(bounding);
    };

    var showError = function(error){
        $error.html(error).show();
        window.scrollTo(0,0);
    };
    var clearError = function(){
        $error.hide().html('');
    };

    var onNotamsRecieved = function(x){
        clearOverlays();
        clearError();
        if (! x.error){
            _.each(x.notams, function(list){
                _.each(list, function(notam){
                    var coordinates = notam.coords;
                    if (! mapNotams[coordinates]){
                        mapNotams[coordinates] = {
                            spot: notam.spot,
                            messages: [notam.message]
                        };
                    }else{
                        mapNotams[coordinates].messages.push(notam.message);
                    }
                });
            });
            renderMarkers();
        }else{
            // show error
            showError(_serverErrorTpl({error: x.error}));
        }
        $button.prop('disabled', false);
    };
    var findMistake = function(codes){
        var re = /^[A-Z]{4}$/;
        codes = $.trim(codes).split("\n");
        var mistake = _.find(codes, function(c){
            c = $.trim(c);
            return !re.test(c) ;
        });

        return mistake ? _wrongIcaoTpl({ code: mistake}) : false;
    };
    var onGetNotams = function(){
        clearError();
        $button.prop('disabled', true);
        var codes = $textarea.val();
        var error = findMistake(codes);
        if (error){
            showError(error);
            $button.prop('disabled', false);
        }else{
            $.post(
                '/',
                {
                    codes: codes
                },
                onNotamsRecieved,
                'json'
            );
            clearOverlays();
        }
    };

    $button.click(onGetNotams);
    initMap();

    setTimeout(function(){
        $button.click();
    }, 5000);
});
