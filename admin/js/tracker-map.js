const ws = new WebSocket('wss://baranggay-magtanggol.online:8443');
const config = { iceServers: [{ urls: "stun:stun.l.google.com:19302" }] };

let currentUserId = null;
let adminUser = null;
let adminPosition = null;
let adminBranch = null;
let admin_session_id = null;
mapboxgl.accessToken = 'pk.eyJ1IjoiY2VsZXN0aWFsMjIiLCJhIjoiY20zazhyanp1MGJ6MDJqcTBiamZhemlmNSJ9.Ij-RBtk-xuosnS5JWrw7fg';
let userMarker = null; // To store the user's location marker
let destination = null;
let initialLocation = null;
let isReturning = false;
let monitorUserConnection = null;
let distanceKm_Admin = null;
let etaMinutes_Admin = null;
let latitudeAdmin = null;
let longitudeAdmin = null;
let token = null;
let userStatus = null;
let incident_id = null;

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

let directions = new MapboxDirections({
    accessToken: mapboxgl.accessToken,
    unit: 'metric',
    profile: 'mapbox/driving',
    interactive: false,
    controls: {
        inputs: false,  // Keep input search fields
        profileSwitcher: false,  // Remove profile switcher (walking, cycling)
        instructions: true  // Keep instructions panel
    }
});

function checkToken() {
    const urlParams = new URLSearchParams(window.location.search);
    token = urlParams.get('token');
    if (!token) {
        console.error("❌ No token found in the URL");
        return; // This will stop further execution in the function scope
    }
}

checkToken(); // Call the function

initializeMap();

// Function to send user status update via fetch
function sendStatusUpdate(status) {
    fetch(`includes/update_rescue_status.php?incidentToken=${token}`, {
        method: "POST",
        headers: {
        "Content-Type": "application/json",
        },
        body: JSON.stringify({ userStatus })
    })
        .then(response => response.json())
        .then(data => {
        if (data.status === "success") {
            console.log(data.message); // Success message
        } else {
            console.error(data.message); // Error message
        }
        })
        .catch(error => {
        console.error("❌ Error:", error);
        });
}

function fetchAndSetInitialDestination() {
    fetch(`includes/get_first_destination.php?token=${token}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                const { latitude, longitude } = data;

                // Store the destination globally
                destination = [longitude, latitude];
                incident_id = data.incident_id;

                // Set the destination on the map
                if (directions) {
                    directions.setDestination(destination);
                    if(userStatus === 'arrived'){
                        document.getElementById("returningBtn").disabled = false;
                        directions.setOrigin([longitudeAdmin, latitudeAdmin]);
                    }
                    // console.log(`✅ Initial Destination: Lat: ${latitude}, Lon: ${longitude}`);

                } else {
                    console.error("❌ Directions instance is not available.");
                }
            }
            else if (data.status === 'error') {
                // If there's an error, display the error message
                console.error("Error:", data.message || 'Unknown error');
                // Display it on the webpage, e.g., in a div or alert box
                document.getElementById('error-message').textContent = data.message || 'Unknown error occurred';
              } else {
                // Handle unexpected responses
                console.error("Unexpected response format", data);
                document.getElementById('error-message').textContent = 'Unexpected response format.';
              }
        })
        .catch(error => console.error("❌ Fetch Error:", error));
}

function sendLocation(position) {
    let lat = position.coords.latitude;
    let lon = position.coords.longitude;
    longitudeAdmin = lon;
    latitudeAdmin = lat;

    if (!initialLocation) {
        initialLocation = [lon, lat];
    }

    if (userStatus === 'arrived') {
        directions.setOrigin([lon, lat]);
        directions.setDestination([lon, lat]);
        document.getElementById("returningBtn").disabled = false;
    } else if(userStatus === 'returning') {
        directions.removeRoutes();
        isReturning = true; // Prevent this from running again
        destination = null; // Disable this condition
         // Set new route back to initial location
        directions.setOrigin([longitudeAdmin, latitudeAdmin]);
        directions.setDestination(initialLocation);
    } else {
        directions.setOrigin([lon, lat]);
    }
    
    // Center the map on the user's current location
    map.setCenter([lon, lat]);

    // Optional: Add a smooth transition effect
    map.flyTo({
        center: [lon, lat],
        zoom: 15, // Adjust zoom level as needed
        speed: 1.2, // Transition speed
        curve: 1, // Makes the movement smooth
        essential: true
    });

    // Listen for route updates and extract ETA & distance
    let hasSentRoute = false; // Flag to track execution

    if (userStatus !== "arrived" && destination && hasArrived(lat, lon, destination)) {
        document.getElementById("returningBtn").disabled = false;
        userStatus = "arrived";
        sendStatusUpdate(userStatus);

    }
    directions.on('route', (event) => {
        if (!hasSentRoute && event.route && event.route.length > 0) {
            let route = event.route[0]; // Get the first route
            etaMinutes_Admin = route.duration ? route.duration / 60 : 0; // Convert seconds to minutes
            distanceKm_Admin = route.distance ? route.distance / 1000 : 0; // Convert meters to km
            
            etaMinutes_Admin = etaMinutes_Admin.toFixed(2);
            distanceKm_Admin = distanceKm_Admin.toFixed(2);
            // console.log(`ETA: ${etaMinutes_Admin} minutes`);
            // console.log(`Distance: ${distanceKm_Admin} km`);
            
            ws.send(JSON.stringify({
                type: 'MapMonitoring',
                userId: currentUserId,
                lat: lat,
                lon: lon,
                admin_position: adminPosition,
                admin_branch: adminBranch,
                eta: etaMinutes_Admin,
                distance: distanceKm_Admin,
                token: token,
                status: userStatus
            }));
            
            hasSentRoute = true; // Set flag to true so it only runs once
        }
    });

    document.getElementById("returningBtn").addEventListener("click", function () {
        if (!ws || ws.readyState !== WebSocket.OPEN) {
            console.error("WebSocket is not connected.");
            return;
        }
        if (!isReturning) {
            userStatus = "returning";
            sendStatusUpdate(userStatus);
            ws.send(JSON.stringify({
                type: 'MapMonitoring',
                userId: currentUserId,
                lat: latitudeAdmin,
                lon: longitudeAdmin,
                admin_position: adminPosition,
                admin_branch: adminBranch,
                eta: etaMinutes_Admin,
                distance: distanceKm_Admin,
                token: token,
                status: userStatus
            }));
            // Remove route & switch to returning mode
            directions.removeRoutes();
            isReturning = true; // Prevent this from running again
            destination = null; // Disable this condition
    
            // Set new route back to initial location
            directions.setOrigin([longitudeAdmin, latitudeAdmin]);
            directions.setDestination(initialLocation);
            // Enable the button
            console.log("Returning to original location:", initialLocation);
        }
        console.log("WebSocket message sent: User is returning.");
        document.getElementById("returningBtn").disabled = true;
    });
    
    // 2️⃣ Second Condition: Arrive Back at Initial Location
    if (isReturning && initialLocation && hasArrived(lat, lon, initialLocation)) {
        alert("You have returned to your original location!");
        userStatus = 'returned';
        sendStatusUpdate(userStatus);
        ws.send(JSON.stringify({
                type: 'MapMonitoring',
                userId: currentUserId,
                lat: lat,
                lon: lon,
                admin_position: adminPosition,
                admin_branch: adminBranch,
                eta: etaMinutes_Admin,
                distance: distanceKm_Admin,
                token: token
            }));
        // Stop further execution (Final stop)
        isReturning = false;
        destination = null;
        console.log("User has completed the route.");
    }
}

function hasArrived(userLat, userLon, dest) {
    const R = 6371e3; // Earth radius in meters
    const φ1 = userLat * Math.PI / 180;
    const φ2 = dest[1] * Math.PI / 180;
    const Δφ = (dest[1] - userLat) * Math.PI / 180;
    const Δλ = (dest[0] - userLon) * Math.PI / 180;

    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) *
        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const distance = R * c; // Distance in meters
    return distance < 10; // Returns true if the user is within 10 meters
}

function initializeMap() {
    fetch("configs/session-info.php")
    .then(response => response.json())
    .then(data => {
        adminUser = data.admin_user;
        adminPosition = data.admin_position;
        adminBranch = data.admin_branch;
        admin_session_id = data.session_id;
        
        // console.log("👤 Admin User:", adminUser, adminBranch, adminPosition, data.session_id, data.admin_email);
        if (adminUser) {
            currentUserId = adminUser;
            // ✅ Authenticate after WebSocket connection opens
            ws.onopen = () => {
                ws.send(JSON.stringify({ 
                    type: "adminConnection", 
                    userId: currentUserId , 
                    session_id: admin_session_id,
                    admin_position: adminPosition,
                    admin_branch: adminBranch
                }));
                navigator.geolocation.getCurrentPosition((position) => {
                    let lat = position.coords.latitude;
                    let lon = position.coords.longitude;

                    longitudeAdmin = lon;
                    latitudeAdmin = lat;

                    // 🔥 Send to accept_rescue.php
                    fetch("includes/accept_rescue.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            incidentToken: token,
                            latitude: lat,
                            longitude: lon
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // console.log("Response from accept_rescue.php:", data); // Log the full response 
                        if (data.status) {
                            
                            userStatus = data.status;

                            // console.log(userStatus);

                            if (userStatus === 'returning'){
                                directions.removeRoutes();
                                isReturning = true; // Prevent this from running again
                                destination = null; // Disable this condition
                            } else if(userStatus === 'arrived'){
                                directions.setOrigin([longitudeAdmin, latitudeAdmin]);
                                initialLocation = [data.longitude, data.latitude];
                                console.log(`✅ destination set ${userStatus}`);
                            } else if(userStatus === 'ongoing'){
                                initialLocation = [lon, lat];
                            } else if(userStatus === 'returned'){
                                isReturning = false;
                                destination = null;
                            }
                        } else {
                            console.error("❌ Failed to accept rescue:", data.error);
                        }
                    })
                    .catch(error => {
                        console.error("❌ Error sending accept_rescue request:", error);
                    });
                    // console.log("User's original location:", initialLocation);
                }, console.error, {
                    enableHighAccuracy: true
                });
                navigator.geolocation.watchPosition(sendLocation, console.error, {
                    enableHighAccuracy: true,
                    maximumAge: 0
                });
            };

            // const layerList = document.getElementById('map');
            // // const inputs = layerList.getElementsByTagName('input');

            // for (const input of layerList) {
            //     input.onclick = (layer) => {
            //         const layerId = layer.target.id;
            //         map.setStyle('mapbox://styles/mapbox/' + layerId);
            //     };
            // }
            
            // const mapDiv = document.getElementById("map");
            // const map = L.map(mapDiv).setView([51.505, -0.09], 13);
            // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            // const resizeObserver = new ResizeObserver(() => {
            //     map.invalidateSize();
            // });

            // resizeObserver.observe(mapDiv);
        } else {
            console.warn("⚠️ No admin session found.");
        }
    })
    .catch(error => console.error("❌ Error fetching session:", error));

    ws.onmessage = (event) => {
        let data = JSON.parse(event.data);
        console.log(`✅  Recieved Message: ${data.lat}`)
        if (data.token && token !== data.token) {
            console.warn("❌ Token mismatch. Ignoring message.");
            return; // Ignore the message if the tokens do not match
        }
        switch (data.type) {
        
            case "updateAdminLoc":
                ws.send(JSON.stringify({
                    type: 'MapMonitoring',  // Custom message type
                    userId: currentUserId,
                    lat: latitudeAdmin,
                    lon: longitudeAdmin,
                    admin_position: adminPosition,
                    admin_branch: adminBranch,
                    token: token,
                    eta: etaMinutes_Admin,
                    distance: distanceKm_Admin,
                    reason: 'updateAdminLoc',
                    toUser: data.userId
                }));
                break;
            default:
                console.log("⚠️ Unknown WebSocket Message Type:", data);
        }
    };

    // Add zoom and rotation controls
    map.on('load', function() {
        fetchAndSetInitialDestination(); 
        
        fetch('assets/json/Taguig-Line.json') // Ensure this file is correctly served
        .then(response => response.json())
        .then(data => {
            const lineData = data.lineData;
            // Add GeoJSON source   

            map.addControl(directions, 'top-left');
            
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
            const hydrantLocations = data.hydrantLocations;
            hydrantLocations.forEach(location => {
                const marker = new mapboxgl.Marker({
                        element: createHydrantMarker()
                    })
                    .setLngLat([location.longitude, location.latitude])
                    .setPopup(new mapboxgl.Popup().setHTML(
                        `<strong>Hydrant:</strong> ${location.location}`))
                    .addTo(map);
            });
            // Function to create custom hydrant marker element
            function createHydrantMarker() {
                const el = document.createElement('div');
                el.className = 'hydrant-marker';
                el.style.backgroundImage =
                    "url('assets/images/hydrant.png')"; // Make sure the image path is correct
                el.style.width = '30px';
                el.style.height = '30px';
                el.style.backgroundSize = 'cover';
                return el;
            }

        })
        .catch(error => console.error('Error loading GeoJSON:', error));
    });
}