<?php
require 'class.php';
$datos = new Datos();
?> 
<html>
<html lang="es">

<head>
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.0/mapsjs-ui.css?dp-version=1549984893" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
    <style>
        #mapa{
            width:100%;
            height:100%;
            padding:0;
            margin:0;
        }
    </style>

</head>

<body>
    <div id="mapa"></div>


    <script type="text/javascript" charset="UTF-8">

        //Crea un mapa por defecto que contiene la dirección de la empresa
        function MapaDefecto() {

            //La variable platform necesita estos parámetros para hacer conexión con la API.
            var platform = new H.service.Platform({
                app_id: 'EK6iyMjMfQijKE2UA3vH',
                app_code: '5Y8v3buLpU4vcXsW5TM-mw'
            });

            //Opciones por defecto.
            var pixelRatio = window.devicePixelRatio || 1;
            var defaultLayers = platform.createDefaultLayers();

            //Inicializo el mapa pasándole un bloque div a través de su ID
            var map = new H.Map(document.getElementById('mapa'),
                defaultLayers.normal.map, { pixelRatio: pixelRatio });

            

            //Hacemos el mapa interactivo
            // Behavior implementa por defecto los botones de zoom in/ zoom out
            var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

            // Crea los componentes principales de la interfaz
            var ui = H.ui.UI.createDefault(map, defaultLayers);
            ui.getControl('zoom').setVisibility(false);
            ui.getControl('mapsettings').setVisibility(false);

            // Creamos el mapa pasando como objeto la latitud y longitud
            map.setCenter({ lat: 36.7294, lng: -6.09108 });
            map.setZoom(16);

            //Creamos un marcador en el mapa
            var icon = new H.map.Icon('img/location-pin.png');
            var marcador = new H.map.Marker({ lat: 36.7294, lng: -6.09108 },{icon: icon});
            
            map.addObject(marcador);

        }

        //Le pasamos una dirección y haciendo una petición por AJAX a la API nos devuelve un array con una latitud y longitud
        function ObtenerCoordenadas(address) {
            
            //En esta función hacemos una petición a la API de Geocoding para obtener la latitud y longitud
            var array = new Array();
            $.ajax({
                dataType: "json",
                async:false,
                url: "https://geocoder.api.here.com/6.2/geocode.json?app_id=EK6iyMjMfQijKE2UA3vH&app_code=5Y8v3buLpU4vcXsW5TM-mw&searchtext=" + address + "",
                type: 'GET',
                success: function (data) {
                    
                    array[0] = data.Response.View[0].Result[0].Location.DisplayPosition.Latitude;
                    array[1] = data.Response.View[0].Result[0].Location.DisplayPosition.Longitude;                     
                    
                    return array;

                },
                error: function () {
                    alert("Problema en la petición al servidor");
                }
            });
            return array;
            
        }

        //Crea un mapa según la lat y lng que le pasamos
        function CrearMapa(lat, lng) {
            //La variable platform necesita estos parámetros para hacer conexión con la API.
            var platform = new H.service.Platform({
                app_id: 'EK6iyMjMfQijKE2UA3vH',
                app_code: '5Y8v3buLpU4vcXsW5TM-mw'
            });

            //Opciones por defecto.
            var pixelRatio = window.devicePixelRatio || 1;
            var defaultLayers = platform.createDefaultLayers();

            //Inicializo el mapa pasándole un bloque div a través de su ID
            var map = new H.Map(document.getElementById('mapa'),
                defaultLayers.normal.map, { pixelRatio: pixelRatio });

            //Hacemos el mapa interactivo
            // Behavior implementa por defecto los botones de zoom in/ zoom out
            var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

            // Crea los componentes principales de la interfaz
            var ui = H.ui.UI.createDefault(map, defaultLayers);
            ui.getControl('zoom').setVisibility(false);
            ui.getControl('mapsettings').setVisibility(false);

            // Creamos el mapa pasando como objeto la latitud y longitud
            map.setCenter({ lat: lat, lng: lng });
            map.setZoom(16);

            //Creamos un marcador en el mapa
            var icon = new H.map.Icon('img/location-pin.png');
            var marcador = new H.map.Marker({ lat: lat, lng: lng },{icon:icon});
            
            map.addObject(marcador);
        }

        //Como la latlng nos viene como string separada por ; tendremos que partirla en un array para obtener sus valores. Devuelve un array
        function ConstruyeCoordenadas(latlng) {            

            var arrayLatLng = latlng.split(";");


            return arrayLatLng; // Devuelve el array con los dos valores

        }


        //Crea una ruta entre dos direccines que le pasemos, siendo primera la de origen y segundamente la de destino.
        //Tiene que recibir los arrays como parámetros
        function CrearRutas(arrayOrigen, arrayDestino) {
            // Creamos un servicio con las credenciales de nuestro proyecto
            var platform = new H.service.Platform({
                app_id: 'EK6iyMjMfQijKE2UA3vH',
                app_code: '5Y8v3buLpU4vcXsW5TM-mw'
            });
            // Cogemos el bloque div donde se cargará el mapa
            var targetElement = document.getElementById('mapa');

            // Cogemos la plantilla por defecto de Here maps
            var defaultLayers = platform.createDefaultLayers();
            

            // Creamos el mapa
            var map = new H.Map(
                document.getElementById('mapa'),
                defaultLayers.normal.map,
                {
                    zoom: 16,
                    center: { lat: arrayOrigen[0], lng: arrayOrigen[1] }
                });

            var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

            var ui = H.ui.UI.createDefault(map, defaultLayers);
            ui.getControl('zoom').setVisibility(false);
            ui.getControl('mapsettings').setVisibility(false);

            // Parámetros para crear la ruta según las coordenadas
            var routingParameters = {
                // Transporte que se usa en la ruta
                'mode': 'fastest;car',
                // Punto de origen de la ruta
                'waypoint0': 'geo!' + arrayOrigen[0] + ',' + arrayOrigen[1] + '',
                // Punto de destino de la ruta
                'waypoint1': 'geo!' + arrayDestino[0] + ',' + arrayDestino[1] + '',
                // Para que nos muestre una ruta entre los dos puntos                
                'representation': 'display'
            };
                        
            // Función con callback para procesar la ruta creada
            var onResult = function (result) {
                var route,
                    routeShape,
                    startPoint,
                    endPoint,
                    linestring;
                if (result.response.route) {
                    //Cojo la primera ruta del response
                    route = result.response.route[0];
                    // Forma de la ruta(visualmente)
                    routeShape = route.shape;

                    
                    // Creamos una línea para usarla como dirección en la ruta
                    linestring = new H.geo.LineString();
                    
                    //Mete todas las coordenadas en la línea que hemos creado arriba para que quede como una ruta
                    routeShape.forEach(function (point) {
                        var parts = point.split(',');
                        linestring.pushLatLngAlt(parts[0], parts[1]);
                    });

                    // Retrieve the mapped positions of the requested waypoints:
                    startPoint = route.waypoint[0].mappedPosition;
                    endPoint = route.waypoint[1].mappedPosition;

                    
                    // Hacemos un Polyline para mostrar la ruta
                    var routeLine = new H.map.Polyline(linestring, {
                        style: { lineWidth: 6 },
                        arrows: { fillColor: 'white', frequency: 2, width: 0.7, length: 0.7 }
                    });

                    // Crea un marcador en el punto de inicio:
                    var icon = new H.map.Icon('img/location-pin.png');
                    var startMarker = new H.map.Marker({
                        lat: startPoint.latitude,
                        lng: startPoint.longitude
                    },{icon:icon});

                    // Crea un marcador en el punto de destino:
                    var endMarker = new H.map.Marker({
                        lat: endPoint.latitude,
                        lng: endPoint.longitude
                    },{icon:icon});

                    // Añadimos la ruta y los dos marcadores al mapa que hemos creado
                    map.addObjects([routeLine, startMarker, endMarker]);

                    // Ponemos que la ruta sea responsive 
                    map.setViewBounds(routeLine.getBounds());
                }
            };

            //Obtenemos una instancia del servicio de ruteo

            var router = platform.getRoutingService();

            // Call calculateRoute() with the routing parameters,
            // Llamamos a la función calculateRoute() con los parámetros creados
            // si hay algún error lanzará un alert con este
            router.calculateRoute(routingParameters, onResult,
                function (error) {
                    alert(error.message);
                });
        }

        //Aquí recojo los valores de las variables que vienen por parámetros GET
        var address = "<?php echo $datos->getAddress() ?>";
        var latlng = "<?php echo $datos->getlatlng() ?>";
        var latlngDestino = "<?php echo $datos->getLatlngDestino() ?>";
        var addressDestino = "<?php echo $datos->getAddressDestino() ?>";

        //Cuando nos viene una lat y lng y una dirección llamamos a la función directamente con las coordenadas para ser más exactos
        if (latlng != "" && address != "Calle del transporte, Guadalcacin, 11591" && latlngDestino == "" && addressDestino == "Calle del transporte, Guadalcacin, 11591") {
            latlng = ConstruyeCoordenadas(latlng);
            CrearMapa(latlng[0], latlng[1]);
            }



        //Cuando nos viene una dirección solamente
        if (latlng == "" && address != "Calle del transporte, Guadalcacin, 11591" && latlngDestino == "" && addressDestino == "Calle del transporte, Guadalcacin, 11591") {
            address = ObtenerCoordenadas(address); 
            CrearMapa(address[0],address[1]);
        } 


        //Cuando nos vienen solamente las coordenadas
        if(latlng != "" && address == "Calle del transporte, Guadalcacin, 11591" && latlngDestino == "" && addressDestino == "Calle del transporte, Guadalcacin, 11591"){
            latlng = ConstruyeCoordenadas(latlng);
            CrearMapa(latlng[0],latlng[1]);
        }

        //Cuando nos viene una dirección y una dirección destino
        if(latlng == "" && address != "Calle del transporte, Guadalcacin, 11591" && latlngDestino == "" && addressDestino != "Calle del transporte, Guadalcacin, 11591"){
            address = ObtenerCoordenadas(address);
            addressDestino = ObtenerCoordenadas(addressDestino);
            CrearRutas(address,addressDestino);
        }
        
        //Cuando nos viene una latlng y una latlngDestino
        if(latlng != "" && latlngDestino != "" && addressDestino == "Calle del transporte, Guadalcacin, 11591"){
            latlng = ConstruyeCoordenadas(latlng);
            latlngDestino = ConstruyeCoordenadas(latlngDestino);
            CrearRutas(latlng,latlngDestino);
        }

        //Cuando nos viene una latlng y una dirección
        if(latlng != "" && latlngDestino == "" && addressDestino != "Calle del transporte, Guadalcacin, 11591"){
            latlng = ConstruyeCoordenadas(latlng);
            addressDestino = ObtenerCoordenadas(addressDestino);
            CrearRutas(latlng,addressDestino);
        }

        //Cuando nos viene una dirección de origen y una latlngdestino
        if(address != "Calle del transporte, Guadalcacin, 11591" && latlng == "" && latlngDestino != "" && addressDestino == "Calle del transporte, Guadalcacin, 11591"){
            address = ObtenerCoordenadas(address);
            latlngDestino = ConstruyeCoordenadas(latlngDestino);
            CrearRutas(address,latlngDestino);
        }


        //Cuando nos viene una una latlng y latlngDestino junto a una dirección de destino
        if(latlng != "" && latlngDestino != "" && addressDestino != "Calle del transporte, Guadalcacin, 11591"){
            latlng = ConstruyeCoordenadas(latlng);
            latlngDestino = ConstruyeCoordenadas(latlngDestino);
        }     

        if(latlng == "" && address == "Calle del transporte, Guadalcacin, 11591" && latlngDestino == "" && addressDestino == "Calle del transporte, Guadalcacin, 11591"){
            MapaDefecto();
        }   

    </script>

</body>

</html>