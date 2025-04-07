document.addEventListener("click", function (event) {
    let button = event.target.closest(".view-row"); 
    if (!button) return;

    console.log("✅ View button clicked!", button); // Debugging

    let userId = button.getAttribute("data-userId");
    let incidentId = button.getAttribute("data-id");
    let reporterName = button.getAttribute("data-name");
    let reportStatus = button.getAttribute("data-status");
    let verifiedBy = button.getAttribute("data-verified-by"); // Get dynamically updated verified_by
    console.log("🔍 Extracted Data:");
    console.log("User ID:", userId);
    console.log("Incident ID:", incidentId);
    console.log("Reporter Name:", reporterName);

    if (!incidentId) {
        console.error("❌ Incident ID is missing!");
        return;
    }

    // Set modal values
    document.getElementById("modalIncidentID").textContent = incidentId;
    document.getElementById("modalName").textContent = reporterName;
    document.getElementById("modalContact").textContent = button.getAttribute("data-contact");
    document.getElementById("modalLocation").textContent = button.getAttribute("data-location");
    document.getElementById("modalMessage").textContent = button.getAttribute("data-message");
    document.getElementById("modalTime").textContent = button.getAttribute("data-time");
    document.getElementById("modalStatus").textContent = reportStatus;

    // ✅ Select buttons inside the modal
    let callBtn = document.querySelector(".call-btn");
    let approveBtn = document.querySelector(".approve-btn");
    let declineBtn = document.querySelector(".decline-btn");
    // Set button attributes
    document.querySelector(".call-btn")?.setAttribute("data-userid", userId);
    document.querySelector(".call-btn")?.setAttribute("data-id", incidentId);
    document.querySelector(".approve-btn")?.setAttribute("data-userid", userId);
    document.querySelector(".approve-btn")?.setAttribute("data-id", incidentId);
    document.querySelector(".decline-btn")?.setAttribute("data-userid", userId);
    document.querySelector(".decline-btn")?.setAttribute("data-id", incidentId);

    // ✅ Handle GPS Data
    let gpsContainer = document.getElementById("modalGpsLocation");
    let lat = button.getAttribute("data-lat");
    let lon = button.getAttribute("data-lon");
    let errorGps = button.getAttribute("data-errorGps");

    // ✅ Disable buttons if report is "processing" AND verified_by is NOT the logged-in admin
    if (reportStatus === "processing" && verifiedBy !== loggedInAdmin) {
        callBtn?.setAttribute("disabled", true);
        approveBtn?.setAttribute("disabled", true);
        declineBtn?.setAttribute("disabled", true);
        console.log(`🚫 Buttons disabled for Incident ID: ${incidentId}`);
    } else {
        callBtn?.removeAttribute("disabled");
        approveBtn?.removeAttribute("disabled");
        declineBtn?.removeAttribute("disabled");
    }
    
    if (lat && lon) {
        gpsContainer.innerHTML = `<strong>User Location:</strong> <span>Lat: ${lat}, Long: ${lon}</span>`;
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
    
    // Show the modal
    $("#incidentModal").modal("show");
    $('#incidentModal').on('shown.bs.modal', function () {
        map.resize();
    });
});
document.querySelector(".call-btn").addEventListener("click", function () {
    let userId = this.getAttribute("data-userid");
    let incidentId = this.getAttribute("data-id");
    
    if (userId) {
        updateReportStatus("processing", incidentId, userId);
        
        requestVideoCall(userId);
    } else {
        console.error("❌ No user ID found for call request.");
    }
});
document.querySelector(".approve-btn").addEventListener("click", function () {
    let userId = this.getAttribute("data-userid");
    let incidentId = this.getAttribute("data-id");
    if (userId && incidentId) {
        updateReportStatus("Approved", incidentId, userId);
        
    } else {
        console.error("❌ No user ID found for call request.");
    }
});
document.querySelector(".decline-btn").addEventListener("click", function () {
    let userId = this.getAttribute("data-userid");
    let incidentId = this.getAttribute("data-id");
    if (userId && incidentId) {
        updateReportStatus("Declined", incidentId, userId);
    } else {
        console.error("❌ No user ID found for call request.");
    }
});
function updateReportStatus(report_status, incidentId, userId) {
    let formData = new FormData();
    formData.append("report_status", report_status);
    formData.append("incident_id", incidentId);
    formData.append("connection_id", userId);
    
    fetch("includes/update-report-status.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text()) // ✅ Read as text first
    .then(text => {
        try {
            let data = JSON.parse(text);
            if (data.success) {
                console.log("✅ Report Updated:", data.incident_id, data.report_status, data.connection_id);
                if (data.report_status==="Approved"){
                    ws.send(JSON.stringify({
                        type: "reportUpdate",
                        incidentId: incidentId,
                        userId: userId,
                        update: "Approved",
                        verified_by: data.verified_by
                    }));
                    let reportLink = `map-incident-loc.php?token=${data.incident_id}`;
                    window.open(reportLink, "_blank");
                    location.reload();
                }if (data.report_status==="processing"){
                    ws.send(JSON.stringify({
                        type: "reportUpdate",
                        incidentId: incidentId,
                        userId: userId,
                        update: "processing",
                        verified_by: data.verified_by
                    }));
                }
                else{
                    ws.send(JSON.stringify({
                        type: "reportUpdate",
                        incidentId: incidentId,
                        userId: userId,
                        update: "Declined",
                        verified_by: data.verified_by

                    }));
                    console.log("Declined:", userId);
                    location.reload();
                }
            } else {
                console.error("❌ Error:", data.error);
                alert("❌ Error updating report: " + data.error);
            }
        } catch (error) {
            console.error("❌ JSON Parse Error:", error, "Response:", text);
            alert("❌ Server returned an invalid response.");
        }
    })
    .catch(error => console.error("❌ Fetch Error:", error));
}
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