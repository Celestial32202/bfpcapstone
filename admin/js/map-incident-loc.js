const ws = new WebSocket('wss://baranggay-magtanggol.online:8443');
const config = { iceServers: [{ urls: "stun:stun.l.google.com:19302" }] };
let currentUserId = localStorage.getItem("currentUserId");
let monitorUserConnection = null;

if (!currentUserId) {
    currentUserId = Math.random().toString(36).substring(2) + Math.random().toString(36).substring(2);
    currentUserId = currentUserId.substring(0, 16);
    localStorage.setItem("currentUserId", currentUserId);  // Save to localStorage
    ws.onopen = () => {
        ws.send(JSON.stringify({ type: "AdminMapMonitoring", userId: currentUserId , position: "HigherAdmin"}));
    };
    
}else if(currentUserId){
    ws.onopen = () => {
        ws.send(JSON.stringify({ type: "AdminMapMonitoring", userId: currentUserId , position: "HigherAdmin"}));
    };
}

mapboxgl.accessToken = 'pk.eyJ1IjoiY2VsZXN0aWFsMjIiLCJhIjoiY20zazhyanp1MGJ6MDJqcTBiamZhemlmNSJ9.Ij-RBtk-xuosnS5JWrw7fg';
let destinationMarker;
let selectedDestination = null;
// Initialize the map
var map = new mapboxgl.Map({
    container: 'map', // ID of the map div
    style: 'mapbox://styles/celestial22/cm3t0sn75003201pzft34detg', // Custom style
    center: [121.061396, 14.540525], // [longitude, latitude]
    zoom: 15,
    pitch: 50,
    bearing: -10
});
let geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    mapboxgl: mapboxgl,
    marker: false,
    placeholder: "Search for a location..."
});
// Add zoom and rotation controls
map.on('load', function() {
    fetch('../assets/json/Taguig-Line.json') // Ensure this file is correctly served
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
                
            });
            // const lineData = data.lineData;
            // // Add GeoJSON source   
            // map.addControl(geocoder);
            // geocoder.on('result', (e) => {
            //     let lat = e.result.center[1];
            //     let lon = e.result.center[0];

            //     if (destinationMarker) destinationMarker.remove();
            //     destinationMarker = new mapboxgl.Marker({
            //             color: 'red'
            //         })
            //         .setLngLat([lon, lat])
            //         .addTo(map);

            //     // Send destination to the user
            //     ws.send(JSON.stringify({
            //         type: 'destination',
            //         lat,
            //         lon
            //     }));
            // });
            // map.addSource('geojson-lines', {
            //     type: 'geojson',
            //     data: lineData
            // });
            // Define line layer style
            // map.addLayer({
            //     id: 'line-layer',
            //     type: 'line',
            //     source: 'geojson-lines',
            //     layout: {
            //         "line-join": "round",
            //         "line-cap": "round"
            //     },
            //     paint: {
            //         "line-color": "#ff0000", // Red color
            //         "line-width": 2 // Adjust thickness
            //     }
            // });
            // const hydrantLocations = data.hydrantLocations;
            // hydrantLocations.forEach(location => {
            //     const marker = new mapboxgl.Marker({
            //             element: createHydrantMarker()
            //         })
            //         .setLngLat([location.longitude, location.latitude])
            //         .setPopup(new mapboxgl.Popup().setHTML(
            //             `<strong>Hydrant:</strong> ${location.location}`))
            //         .addTo(map);
            // });
            // // Function to create custom hydrant marker element
            // function createHydrantMarker() {
            //     const el = document.createElement('div');
            //     el.className = 'hydrant-marker';
            //     el.style.backgroundImage =
            //         "url('assets/images/hydrant.png')"; // Make sure the image path is correct
            //     el.style.width = '30px';
            //     el.style.height = '30px';
            //     el.style.backgroundSize = 'cover';
            //     return el;
            // }

        })
        .catch(error => console.error('Error loading GeoJSON:', error));
});
geocoder.on('result', (e) => {
    let lat = e.result.center[1];
    let lon = e.result.center[0];

    if (destinationMarker) destinationMarker.remove();
    destinationMarker = new mapboxgl.Marker({
            color: 'red'
        })
        .setLngLat([lon, lat])
        .addTo(map);

});
let fireStationMarkers = []; // Store fire station markers
let lastSelectedDestination = null; // Store last clicked location
let selectedStations = [];
map.on('click', async (e) => {
    let lat = e.lngLat.lat;
    let lon = e.lngLat.lng;

    // Remove previous destination marker
    if (destinationMarker) destinationMarker.remove();
    destinationMarker = new mapboxgl.Marker({ color: 'red' })
        .setLngLat([lon, lat])
        .addTo(map);

    lastSelectedDestination = { lat, lon }; // Save last clicked location
    updateDestinationDetails(lon, lat);

    console.log("Destination selected:", lastSelectedDestination);

    // Fetch and update fire stations
    await updateFireStationMarkers();
});

// Listen for changes in dropdown and update fire stations dynamically
document.getElementById('quantity').addEventListener('change', async () => {
    if (lastSelectedDestination) {
        await updateFireStationMarkers(); // Re-run marker update if location is selected
    }
});

// Function to fetch and update fire station markers
async function updateFireStationMarkers() {
    if (!lastSelectedDestination) return;

    const { lat, lon } = lastSelectedDestination;

    // Fetch all locations from PHP
    const response = await fetch('../get_markers.php');
    const data = await response.json();
    const locations = data.locations;

    // Filter only fire stations
    const fireStations = locations.filter(location => location.type === 'fire_station');

    // Compute distance for each fire station
    const sortedStations = fireStations.map(station => ({
        ...station,
        distance: haversineDistance(lat, lon, station.latitude, station.longitude)
    }));

    // Sort by nearest to farthest
    sortedStations.sort((a, b) => a.distance - b.distance);

    // Get selected number of stations
    const numberOfStations = parseInt(document.getElementById('quantity').value);

    // Get only the required number of nearest stations
    selectedStations = sortedStations.slice(0, numberOfStations);

    // Remove previous fire station markers
    fireStationMarkers.forEach(marker => marker.remove());
    fireStationMarkers = []; // Reset marker array

    // Add new markers for selected fire stations
    selectedStations.forEach(station => {
        const marker = new mapboxgl.Marker({
                element: createFireStationMarker()
            })
            .setLngLat([station.longitude, station.latitude])
            .setPopup(new mapboxgl.Popup().setHTML(
                `<strong>${station.location}</strong><br>Distance: ${station.distance.toFixed(2)} km`
            ))
            .addTo(map);

        fireStationMarkers.push(marker); // Store marker for future removal
    });
    // Update UI
    updateFireStationsList(selectedStations);
}

// Function to create fire-station marker
function createFireStationMarker() {
    const el = document.createElement('div');
    el.className = 'custom-marker';
    el.style.backgroundImage = "url('../assets/images/fire-station.png')"; // Fire-station icon
    el.style.width = '25px';
    el.style.height = '25px';
    el.style.backgroundSize = 'cover';
    return el;
}

// Haversine formula to calculate distance between two coordinates
function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of Earth in km
    const toRad = (angle) => (angle * Math.PI) / 180;

    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c; // Distance in km
}

// Function to update UI with the closest stations
function updateFireStationsList(stations) {
    const listContainer = document.getElementById('fireStationsList');
    listContainer.innerHTML = '<strong>Nearest Fire Stations:</strong><br>';

    stations.forEach((station, index) => {
        listContainer.innerHTML += `<p>${index + 1}. ${station.location} - ${station.distance.toFixed(2)} km</p>`;
    });
}
function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}
// Extract the token from the URL
const token = getQueryParam('token');

if (!token) {
    console.error("❌ No token found in URL.");
} else {
    console.log("✅ Token received:", token);
}
document.getElementById('sendDestination').addEventListener('click', () => {
    if (!lastSelectedDestination) {
        alert("Please select a destination first!");
        return;
    }
    fetch(`includes/update_rescue_dets.php?token=${encodeURIComponent(token)}`,{
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            userId: currentUserId,
            lat: lastSelectedDestination.lat,
            lon: lastSelectedDestination.lon,
            fireStations: selectedStations // Send station IDs
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error, 'Step:', data.step, 'SQL Error:', data.sql_error);
                if (data.error === "Rescue details already dispatched for this incident.") {
                    $('#redirectModal .modal-body').html(`
                        <h4 class="mt-4">Rescue details already dispatched.</h4>
                    `);
                    $('#redirectModal').modal({ backdrop: 'static', keyboard: false });
                    $('#redirectModal').modal('show');
                }
            } 
            else {
                console.log('Success:', data);
                // ws.send(JSON.stringify({
                //     type: 'MapGpsExchange',
                //     userId: currentUserId,
                //     position: 'LowerAdmin',
                //     lat: lastSelectedDestination.lat,
                //     lon: lastSelectedDestination.lon,
                //     incidentId: data.incident_id,
                //     incidentLocation: data.incident_location,
                //     infoMessage: data.info_message,
                //     fireStations: selectedStations
                // }));
        
                $('#redirectModal .modal-body').html(`
                    <div class="spinner"></div>
                    <h4 class="mt-4">Rescue details dispatched successfully.<br>Redirecting in <span id="countdown">3</span>...</h4>
                `);
                $('#redirectModal').modal({ backdrop: 'static', keyboard: false });
                $('#redirectModal').modal('show');
        
                // Start countdown
                let countdown = 3;
                const countdownElement = document.getElementById('countdown');
        
                const timer = setInterval(() => {
                    countdown--;
                    if (countdownElement) countdownElement.textContent = countdown;
                    if (countdown === 0) {
                        clearInterval(timer);
                        window.location.href = `rescue-monitoring-map.php?token=${data.token}`;
                    }
                }, 1000);
            }
        })
    .catch(error => console.error('Fetch error:', error));
});

function updateDestinationDetails(lon, lat) {
    document.querySelector('.card-body p:nth-child(4)').innerHTML = `<code>Longitude:</code> ${lon}`;
    document.querySelector('.card-body p:nth-child(5)').innerHTML = `<code>Latitude:</code> ${lat}`;
}
