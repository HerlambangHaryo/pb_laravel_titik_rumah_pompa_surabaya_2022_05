<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Titik Rumah Pompa Surabaya</title>
      <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
      <link href="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css" rel="stylesheet">
      <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
      <style>
         body { margin: 0; padding: 0; }
         #map { position: absolute; top: 0; bottom: 0; width: 100%; }
      </style>
   </head>
   <body>
      <style>
         .mapboxgl-popup {
         max-width: 400px;
         font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
         }
      </style>
      <div id="map"></div>
      <script>
         // TO MAKE THE MAP APPEAR YOU MUST
         // ADD YOUR ACCESS TOKEN FROM
         // https://account.mapbox.com
            mapboxgl.accessToken = 'pk.eyJ1Ijoic3VwZXJhcnlhIiwiYSI6ImNrd3VuNHI0ZjB6OGsycm1oZmllcHAxaTEifQ.fF5_b3TtuYUKHWFg1mLSkw';
            
         const map = new mapboxgl.Map({
         container: 'map',
                style: "mapbox://styles/mapbox/streets-v9",
                center: [112.740972, -7.271391],
                zoom: 11.5 
         });
         
         map.on('load', () => {
         map.addSource('places', {
         'type': 'geojson',
         'data': {
         'type': 'FeatureCollection',
         'features':
            [         
              @foreach($data as $row)
              {
                  'type': 'Feature',
                  'properties': 
                  {
                      'description':
                          '<strong>{{$row->nama_lokasi}}</strong><br/><p>{{$row->lokasi}}</p><p>Dibangun {{$row->pembuat}} T.A {{substr($row->tahun_pembuatan,0,4)}}</p><p>Pompa Air : {{$row->pompa_air}}</p>'
                  },
                  'geometry': 
                  {
                      'type': 'Point',
                      'coordinates': [{{$row->longitude}}, {{$row->latitude}}]
                  },
              },
              @endforeach
            ]
         }
         });
         // Add a layer showing the places.
         map.addLayer({
         'id': 'places',
         'type': 'circle',
         'source': 'places',
         'paint': {
         'circle-color': '#4264fb',
         'circle-radius': 6,
         'circle-stroke-width': 2,
         'circle-stroke-color': '#ffffff'
         }
         });
         
         // Create a popup, but don't add it to the map yet.
         const popup = new mapboxgl.Popup({
         closeButton: false,
         closeOnClick: false
         });
         
         map.on('mouseenter', 'places', (e) => {
         // Change the cursor style as a UI indicator.
         map.getCanvas().style.cursor = 'pointer';
         
         // Copy coordinates array.
         const coordinates = e.features[0].geometry.coordinates.slice();
         const description = e.features[0].properties.description;
         
         // Ensure that if the map is zoomed out such that multiple
         // copies of the feature are visible, the popup appears
         // over the copy being pointed to.
         while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
         coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
         }
         
         // Populate the popup and set its coordinates
         // based on the feature found.
         popup.setLngLat(coordinates).setHTML(description).addTo(map);
         });
         
         map.on('mouseleave', 'places', () => {
         map.getCanvas().style.cursor = '';
         popup.remove();
         });
         });
      </script>
   </body>
</html>