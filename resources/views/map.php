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
        }
        #panel {
            display: inline-block;
            margin-left: 2em;
            vertical-align: top;
        }
        #panel textarea {
            width:100%;
            height: 300px;
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
    <script src="http://maps.googleapis.com/maps/api/js"></script>
    <script>
        $(function(){
            var initMap = function(){
                
            };

            initMap();
        });


    </script>
</body>
</html>