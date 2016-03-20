<html>
<head>
    <title>
        RocketRoute API test
    </title>
    <style>
        #mapPane{
            display: inline-block;
            width: 80%;
            height: 90%;
            border: 1px solid black;
            min-width: 500px;
            min-height: 300px;;
        }
        #panel {
            display: inline-block;
            margin-left: 2em;
            vertical-align: top;
            text-align: center;
        }
        #panel textarea {
            display: block;
            width: 100%;
            height: 15em;
            padding: 0.7ex;
            text-align: left;
        }

        #panel button {
            margin: 1em;
        }

        @media (max-width: 1024px) {
            #mapPane {
                width: 100%;
            }
            #panel {
                margin-top: 1ex;
            }
        }

    </style>
</head>
<body>
    <div id="mapPane"></div>
    <div id="panel">
        <textarea>EGLL
EGGW
EGLF
EGHI
EGKA
EGMD
EGMC
        </textarea>
        <button>Show NOTAMs</button>
    </div>


    <script
        src="https://code.jquery.com/jquery-1.12.2.min.js"
        integrity="sha256-lZFHibXzMHo3GGeehn1hudTAP3Sc0uKXBXAzHX1sjtk="
        crossorigin="anonymous"
    ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js" ></script>
    <script src="http://maps.googleapis.com/maps/api/js"></script>
    <script>
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

                var makeMarker = function(point){
                    return new google.maps.Marker({
                        position: point,
                        icon:'exclamation-icon.png'
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
                        makeInfo(point, content).open(gMap, marker);
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
                                    spot: notam.geoSpot,
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


    </script>
</body>
</html>