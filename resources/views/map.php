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

        div.error{
            margin: 1em;
            border: 0.13em solid red;
            background-color: #fdd;
            color: #a00;
            padding: 1ex;
            text-align: center;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 1em;
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
    <div class="error" style="display: none;">test</div>
    <div id="mapPane"></div>
    <div id="panel">
        <textarea
        >EGLL
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
    <script src="/js/map.js"></script>
</body>
</html>