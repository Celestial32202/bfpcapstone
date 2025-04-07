document.addEventListener("click", function (event) {
    let button = event.target.closest(".view-row"); 
    if (!button) return;

    console.log("✅ View button clicked!", button); // Debugging

        // Extract incident details from button data attributes
    let incidentId = button.getAttribute("data-id");
    let incident_token = button.getAttribute("data-incident_token");
    let incidentLocation = button.getAttribute("data-incident_location");
    let infoMessage = button.getAttribute("data-info_message");
    let status = button.getAttribute("data-status");
    let submittedTime = button.getAttribute("data-time");
    if (!incidentId) {
        console.error("❌ Incident ID is missing!");
        return;
    }
    // Update modal fields
    document.getElementById("modalIncidentID").textContent = incidentId;
    document.getElementById("modalLocation").textContent = incidentLocation;
    document.getElementById("modalMessage").textContent = infoMessage;
    document.getElementById("modalTime").textContent = submittedTime;
    document.getElementById("modalStatus").textContent = status;

    // ✅ Handle GPS Data
    let lat = button.getAttribute("data-lat");
    let lon = button.getAttribute("data-lon");
    let gpsContainer = document.getElementById("modalGpsLocation");
    if (lat && lon) {
        gpsContainer.innerHTML = `<strong>User Location:</strong> <span><br>Latitude: ${lat}<br>Longitude: ${lon}</span>`;
    } else if (errorGps) {
        gpsContainer.innerHTML = `<strong>GPS Error:</strong> <span>${errorGps}</span>`;
    } else {
        gpsContainer.innerHTML = `<strong>GPS Status:</strong> <span>Not Available</span>`;
    }
    // Remove any existing marker before adding a new one
    if (window.currentMarker) {
        window.currentMarker.remove();
    }
    // Check if lat and lon exist before adding a marker
    if (lat && lon) {
        // Create a new marker
        window.currentMarker = new mapboxgl.Marker({ color: "red" })
            .setLngLat([parseFloat(lon), parseFloat(lat)])
            .addTo(map);

        // Fly to the marker's location smoothly
        map.flyTo({
            center: [lon, lat],
            speed: 1.2, // Optional: controls animation speed
            zoom: 14.5,
            pitch: 50,
            bearing: -10
        });
    }
    // Set button attributes
    document.querySelector(".accept-btn")?.setAttribute("data-incident_token", incident_token);
    // Show the modal
    $("#incidentModal").modal("show");
    $('#incidentModal').on('shown.bs.modal', function () {
        map.resize();
    });
});
mapboxgl.accessToken = 'pk.eyJ1IjoiY2VsZXN0aWFsMjIiLCJhIjoiY20zazhyanp1MGJ6MDJqcTBiamZhemlmNSJ9.Ij-RBtk-xuosnS5JWrw7fg';
// Initialize the map
var map = new mapboxgl.Map({
    container: 'row-map', // ID of the map div
    style: 'mapbox://styles/celestial22/cm3t0sn75003201pzft34detg', // Custom style
    center: [121.061396, 14.540525],
     // [longitude, latitude]
    zoom: 15,
    pitch: 50,
    bearing: -10
});
document.addEventListener("click", async function (event) {
    let acceptButton = event.target.closest(".accept-btn");
    if (!acceptButton) return;

    let incidentToken = acceptButton.getAttribute("data-incident_token");
    if (!incidentToken) {
        console.error("❌ Incident token is missing!");
        return;
    }
    
    try {
        // Send request to accept the rescue
        let response = await fetch("includes/accept_rescue.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                incidentToken: incidentToken
            })
        });

        let result = await response.json();
        if (result.success) {
            console.log("✅ Rescue accepted:", result.success);

            // Open new tab with tracker-map
            let newTabUrl = `tracker-map.php?token=${encodeURIComponent(incidentToken)}`;
            window.open(newTabUrl, "_blank");
        } else {
            console.error("❌ Error:", result.error);
        }
    } catch (error) {
        console.error("❌ Fetch error:", error);
    }
});
// Add zoom and rotation controls
map.on('load', function() {
    fetch('../assets/json/Taguig-Line.json') // Ensure this file is correctly served
        .then(response => response.json())
        .then(data => {
            const lineData = data.lineData;
            // Add GeoJSON source   
            map.addSource('geojson-lines', {
                type: 'geojson',
                data: lineData
            });
            // Define line layer style
            map.addLayer({
                id: 'line-layer',
                type: 'line',
                source: 'geojson-lines',
                layout: {
                    "line-join": "round",
                    "line-cap": "round"
                },
                paint: {
                    "line-color": "#ff0000", // Red color
                    "line-width": 2 // Adjust thickness
                }
            });
        })
        .catch(error => console.error('Error loading GeoJSON:', error));
});