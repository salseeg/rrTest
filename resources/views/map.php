<html>
<head>
    <title>
        RocketRoute API test
    </title>
    <style>
        #mapPane{
            display: inline-block;
            width: 70%;
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
            width:100%;
            height: 300px;
            padding: 0.7ex;
            text-align: left;
        }

        #panel button {
            margin: 1em;
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
            var initMap = function(){
                var mapProp = {
                    center:new google.maps.LatLng(51.508742,-0.120850),
                    zoom:5,
                    mapTypeId:google.maps.MapTypeId.ROADMAP
                };
                gMap  = new google.maps.Map($map.get(0),mapProp);
            };
            var clearOverlays = function(){
                while(overlays[0]) {
                    overlays.pop().setMap(null);
                }    
            };
            var addMarker = function(notam){
                console.log(notam);
            };
            var onNotamsRecieved = function(x){
//                console.log(x);
                clearOverlays();
                if (! x.error){
                    _.each(x.notams, function(list){
                        _.each(list, function(notam){
                            addMarker(notam);
                        });
                    });
                }
            };
            var onGetNotams = function(){
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
        });


    </script>
</body>
</html>