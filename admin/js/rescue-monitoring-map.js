const ws = new WebSocket('wss://baranggay-magtanggol.online:8443');
const config = { iceServers: [{ urls: "stun:stun.l.google.com:19302" }] };
let currentUserId = null;
mapboxgl.accessToken =
        'pk.eyJ1IjoiY2VsZXN0aWFsMjIiLCJhIjoiY20zazhyanp1MGJ6MDJqcTBiamZhemlmNSJ9.Ij-RBtk-xuosnS5JWrw7fg';
let userMarkers = {}; // Store markers for each user // To store the user's location marker
let userStates = {};  
let userDirections = {};
let destination = null;
let destinationMarker = null; 
let selectedBranches = null;
const userColors = {};
let token = null;
let mapDestination =  null;
function checkToken() {
    const urlParams = new URLSearchParams(window.location.search);
    token = urlParams.get('token');
    if (!token) {
        console.error("❌ No token found in the URL");
        return; // This will stop further execution in the function scope
    }
}
checkToken(); // Call the function

fetch("configs/session-info.php")
    .then(response => response.json())
    .then(data => {
        adminUser = data.admin_user;
        adminPosition = data.admin_position;
        adminBranch = data.admin_branch;
        console.log("👤 Admin User:", adminUser);
        if (adminUser) {
            currentUserId = adminUser;
            // ✅ Authenticate after WebSocket connection opens
            ws.onopen = () => {
                console.log("✅ Admin WebSocket Connected");
                
                ws.send(JSON.stringify({
                    type: "adminConnection",
                    userId: adminUser,
                    session_id: data.session_id,
                    admin_position: "MapMonitoring",
                    admin_branch: adminBranch
                }));
                ws.send(JSON.stringify({ 
                    type: "UpdateConnectedGps", 
                    userId: adminUser,
                    session_id: data.session_id,
                    admin_position: adminPosition,
                    token: token
                }));
            };
        } else {
            console.warn("⚠️ No admin session found.");
        }
    })
    .catch(error => console.error("❌ Error fetching session:", error));
ws.onmessage = (event) => {
    let data = JSON.parse(event.data);
    // console.log(`✅ Received Tracking: ${data.type} for ${data.userId}`);
    if (data.token && token !== data.token) {
        console.warn("❌ Token mismatch. Ignoring message.");
        return; // Ignore the message if the tokens do not match
    }
    switch (data.type) {
        case "MapMonitoring":
            if (data.lat && data.lon) {
                console.log(`recieved data from ${data.userId}`);
                if (!userStates[data.userId]) {
                    userStates[data.userId] = {
                        initialLocation: [data.lon,data.lat],
                        destination: destination,
                        isReturning: false,
                        isArrived: false
                    };
                }
                if (data.status === 'returning'){
                    userStates[data.userId].isReturning = false;
                    returningChecking(data.userId, data.lat, data.lon);
                }else if(data.status === 'arrived'){
                    console.log(`tite`);
                    if(!userStates[data.userId].isArrived && hasArrived(data.lat, data.lon, userStates[data.userId].destination)){
                        userStates[data.userId].isArrived = true;
                        alert(`${data.userId} has arrived at the destination!`);
                    }
                    updateUserLocationOnMap(data.userId, data.lat, data.lon);
                }else{
                    updateUserLocationOnMap(data.userId, data.lat, data.lon);
                }
                if (data.admin_branch) {
                    // Delay the execution of displayOrUpdateBranches by 1 second
                    if (selectedBranches){
                        displayOrUpdateBranches(selectedBranches, data.admin_branch, data.eta, data.distance, data.status);
                    }else{
                        setTimeout(() => {
                            displayOrUpdateBranches(selectedBranches, data.admin_branch, data.eta, data.distance, data.status);
                        }, 3000);  // 1000 milliseconds = 1 second                    
                    }
                } else {
                console.warn("⚠️ Received invalid location data:", data);
                }
        break;
    }
    default:
}
};
function returningChecking(userId, lat, lon) {
    if (!userStates[userId]) return;
    let user = userStates[userId];

    if(user.isArrived){
        user.isArrived = false;
    }
    user.isReturning = true;
    updateUserLocationOnMap(userId, lat, lon);

    // ✅ Second Condition: Arrived Back at Original Location
    // Condition 2: Arrived Back at Initial Location
    if (user.isReturning && hasArrived(lat, lon, user.initialLocation)) {
        alert(`${userId} has returned to their original location!`);
        
        user.isReturning = false;
        user.destination = null;
        console.log(`${userId} has completed their journey.`);
    }
}
function updateUserLocationOnMap(userId, lat, lon) {
    if (!destination) {
        console.warn("⚠️ Destination not set yet. Retrying in 1 second...");
        setTimeout(() => updateUserLocationOnMap(userId, lat, lon), 1000);
        return;
    }
    
    if (!userStates[userId]) return;
    let user = userStates[userId];

    // 🎨 Assign a consistent color
    const routeColor = getRandomColor(userId);
    const borderColor = getDarkerColor(routeColor); // Get darker border color
    if (user.isReturning) {
        // If returning, use the initial location as the destination
        if (user.initialLocation) {
            mapDestination = user.initialLocation; // Set destination to the original location
        } else {
            console.warn("⚠️ User's initial location is not set.");
            return; // If no initial location is found, return without updating
        }
    }else{
        mapDestination = destination;
    }
    // 🔄 Fetch route using Mapbox Directions API
    fetch(`https://api.mapbox.com/directions/v5/mapbox/driving/${lon},${lat};${mapDestination[0]},${mapDestination[1]}?geometries=geojson&overview=full&steps=true&annotations=distance&access_token=${mapboxgl.accessToken}`)
        .then(response => response.json())
        .then(data => {
            if (data.routes.length > 0) {
                const routeGeoJSON = {
                    type: "Feature",
                    properties: {},
                    geometry: data.routes[0].geometry
                };
                const routeId = `route-${userId}`;


                // ✅ Remove existing route for this user if exists
                if (map.getSource(routeId)) {
                    map.removeLayer(`${routeId}-border`);
                    map.removeLayer(routeId);
                    map.removeSource(routeId);
                }
                if (!userMarkers[userId]) {
                    userMarkers[userId] = new mapboxgl.Marker({ color: routeColor })
                        .setLngLat([lon, lat])
                        .addTo(map);
                } else {
                    userMarkers[userId].setLngLat([lon, lat]);
                }

                if(user.isArrived){
                    
                    return;
                }
                
                // ✅ Add new route source
                // ✅ Add new route source only if it doesn't already exist
                if (!map.getSource(routeId)) {
                        // Update route data in the source for smooth animation
                    // 🏷️ Add or Update User Marker
                    
                    map.addSource(routeId, { type: "geojson", data: routeGeoJSON });

                    // ✅ Add border layer (thicker & darker)
                    map.addLayer({
                        id: `${routeId}-border`,
                        type: "line",
                        source: routeId,
                        layout: { "line-join": "round", "line-cap": "round" },
                        paint: {
                            "line-color": borderColor, // Darker border color
                            "line-width": 10, // Thicker border
                        }
                    });
                    map.addLayer({
                        id: routeId,
                        type: "line",
                        source: routeId,
                        layout: { "line-join": "round", "line-cap": "round" },
                        paint: {
                            "line-color": routeColor, // Original user color
                            "line-width": 8 // Slightly thinner than the border
                        }
                    });
                }
                
            } 
        })
        .catch(error => console.error(`❌ Error fetching route for ${userId}:`, error));
}

function fetchAndSetInitialDestination() {
    fetch(`includes/get_first_destination.php?token=${token}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                const { latitude, longitude } = data;
                // ✅ Store the destination globally
                destination = [longitude, latitude];
                console.log(`✅ Initial Destination Set: Lat: ${latitude}, Lon: ${longitude}`);

                selectedBranches = data.selectedBranches;
                // // ✅ Add or Update Destination Marker
                addDestinationMarker(longitude, latitude);
                displayOrUpdateBranches(selectedBranches, null, null, null);
                // ✅ Update all users after setting the destination
                // Object.keys(userStates).forEach(userId => {
                //     userStates[userId].destination = destination;
                //     if (userDirections[userId]) {
                //         userDirections[userId].setDestination(destination);
                //     }
                // });
            } else {

                console.error("❌ Error:", data.message);
            }
        })
        .catch(error => console.error("❌ Fetch Error:", error));
}

// Store branch data persistently across function calls
const branchStatus = {};

function displayOrUpdateBranches(branches, adminBranch, eta, distance, status) {
    const branchesList = document.getElementById("dispatched-stations");
    branchesList.innerHTML = ''; // Clear previous list

    // Store the latest data only for the matching admin branch
    if (adminBranch) {
        if (!branchStatus[adminBranch]) {
            branchStatus[adminBranch] = { eta: "N/A", distance: "N/A", status: "" };
        }

        if (status && status.toLowerCase() === "arrived") {
            branchStatus[adminBranch].status = "arrived";
        } else if (eta && distance) {
            branchStatus[adminBranch].eta = eta;
            branchStatus[adminBranch].distance = distance;
        }
    }

    // Loop through each branch and display the stored values
    branches.forEach(branch => {
        const listItem = document.createElement("p");

        if (branchStatus[branch]) {
            if (branchStatus[branch].status === "arrived") {
                listItem.innerHTML = `${branch}: <br>Arrived at the destination`;
            } else {
                listItem.innerHTML = `${branch}: <br>ETA: ${branchStatus[branch].eta} minutes, Distance: ${branchStatus[branch].distance} km`;
            }
        } else {
            listItem.innerHTML = `${branch}: <br>ETA: N/A, Distance: N/A km`;
        }

        branchesList.appendChild(listItem);
    });
}



function addDestinationMarker(lon, lat) {
    if (destinationMarker) {
        destinationMarker.setLngLat([lon, lat]); // Update existing marker
    } else {
        // ✅ Create a custom marker element
        const el = document.createElement('div');
        el.className = 'destination-marker';
        el.style.backgroundImage = "url('assets/images/fire-png-698.png')"; // Ensure correct path
        el.style.width = '40px';
        el.style.height = '40px';
        el.style.backgroundSize = 'contain';

        // ✅ Create and add the marker
        destinationMarker = new mapboxgl.Marker({ element: el })
            .setLngLat([lon, lat])
            .setPopup(new mapboxgl.Popup().setHTML(`<strong>Destination</strong>`))
            .addTo(map);
    }
}
function getRandomColor(userId) {
    if (!userColors[userId]) {
        const colors = ["red", "blue", "green", "orange", "purple"];
        let index = parseInt(userId, 36) % colors.length; // Assign a fixed color
        userColors[userId] = colors[index]; // Store to maintain consistency
    }
    return userColors[userId]; // Always return the same color
}

function getDarkerColor(color) {
    const colorMap = {
        "red": "#8B0000",
        "blue": "#00008B",
        "green": "#006400",
        "orange": "#8B4500",
        "purple": "#4B0082"
    };
    return colorMap[color] || "#555"; // Default to gray if undefined
}

// ✅ Arrival Distance Check
function hasArrived(userLat, userLon, dest) {
    const R = 6371e3; // Earth radius in meters
    const φ1 = userLat * Math.PI / 180;
    const φ2 = dest[1] * Math.PI / 180;
    const Δφ = (dest[1] - userLat) * Math.PI / 180;
    const Δλ = (dest[0] - userLon) * Math.PI / 180;

    const a = Math.sin(Δφ / 2) ** 2 + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = R * c; // Distance in meters
    return distance < 10; // ✅ Returns true if within 10 meters  
}
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
    fetchAndSetInitialDestination();
    map.setLayerZoomRange('building', 5, 25);
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

        })
        .catch(error => console.error('Error loading GeoJSON:', error));
    // Fetch all locations from the database
    fetch('../get_markers.php')
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
                        imageUrl = '../assets/images/hydrant.png';
                        break;
                    case 'fire_station':
                        imageUrl = '../assets/images/fire-station.png';
                        break;
                    case 'evacuation_center':
                        imageUrl = '../assets/images/evacuation-shelter.png';
                        break;
                    default:
                        imageUrl = '../assets/images/warning-icon.png'; // Default icon
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