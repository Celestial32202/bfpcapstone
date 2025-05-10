<script>
mapboxgl.accessToken = 'pk.eyJ1IjoiY2VsZXN0aWFsMjIiLCJhIjoiY20zazhyanp1MGJ6MDJqcTBiamZhemlmNSJ9.Ij-RBtk-xuosnS5JWrw7fg';

// Initialize the map
var map = new mapboxgl.Map({
    container: 'map', // ID of the map div
    style: 'mapbox://styles/celestial22/cm3t0sn75003201pzft34detg', // Custom style
    center: [121.061596, 14.544263], // [longitude, latitude]14.544263, 121.061596
    zoom: 15.2,
    pitch: 45,
    bearing: -50
});

// Add zoom and rotation controls
map.addControl(new mapboxgl.NavigationControl());
map.on('load', function() {
    map.setLayerZoomRange('building', 5, 25);
    fetch('assets/json/Taguig-Line.json') // Ensure this file is correctly served
        .then(response => response.json())
        .then(data => {
            const datasets = [{
                    id: 'comembo',
                    data: data.comemboData,
                    color: '#ff0000'
                }, // Red
                {
                    id: 'pembo',
                    data: data.pemboData,
                    color: '#0000ff'
                }, // Blue
                {
                    id: 'west-rembo',
                    data: data.westRemboData,
                    color: '#0000ff'
                }, // 
                {
                    id: 'east-rembo',
                    data: data.eastRemboData,
                    color: '#0000ff'
                }, // 
                {
                    id: 'rizal',
                    data: data.rizalData,
                    color: '#0000ff'
                }
                // ,
                // {
                //     id: 'taguig',
                //     data: data.TaguigCityDataLine,
                //     color: '#0000ff'
                // }
            ];
            datasets.forEach(dataset => {
                // Add GeoJSON source
                map.addSource(`${dataset.id}-lines`, {
                    type: 'geojson',
                    data: dataset.data
                });

                // Add line layer
                map.addLayer({
                    id: `${dataset.id}-line-layer`,
                    type: 'line',
                    source: `${dataset.id}-lines`,
                    layout: {
                        "line-join": "round",
                        "line-cap": "round"
                    },
                    paint: {
                        "line-color": dataset.color,
                        "line-width": 2
                    }
                });
                // OPTIONAL: Add a filter to hide water areas
                map.setFilter('taguig-line-layer', ['!=', ['get', 'natural'], 'water']);
            });

        })
        .catch(error => console.error('Error loading GeoJSON:', error));
    // Fetch all locations from the database
    fetch('get_markers.php')
        .then(response => response.json())
        .then(data => {
            const locations = data.locations;
            // Add markers for all locations
            locations.forEach(location => {
                const marker = new mapboxgl.Marker({
                        element: createCustomMarker(location.type)
                    })
                    .setLngLat([location.longitude, location.latitude])
                    .setPopup(new mapboxgl.Popup().setHTML(
                        `<strong>${location.type}:</strong> ${location.location}`
                    ))
                    .addTo(map);
            });

            // Function to create custom markers based on location type
            function createCustomMarker(type) {
                const el = document.createElement('div');
                el.className = 'custom-marker';

                // Assign different images based on type
                let imageUrl = '';
                switch (type) {
                    case 'hydrant':
                        imageUrl = 'assets/images/hydrant.png';
                        break;
                    case 'fire_station':
                        imageUrl = 'assets/images/fire-station.png';
                        break;
                    case 'evacuation_center':
                        imageUrl = 'assets/images/evacuation-shelter.png';
                        break;
                    default:
                        imageUrl = 'assets/images/warning-icon.png'; // Default icon
                }

                el.style.backgroundImage = `url('${imageUrl}')`;
                el.style.width = '25px';
                el.style.height = '25px';
                el.style.backgroundSize = 'cover';
                return el;
            }
        })
        .catch(error => console.error('Error loading locations:', error));
});
</script>
</body>

</html>