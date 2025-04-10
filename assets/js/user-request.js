const ws = new WebSocket('wss://baranggay-magtanggol.online:8443');
const config = {
    iceServers: [
        {
            urls: "stun:stun.l.google.com:19302"  // Google STUN server
        }
    ]
};


let peerConnection;
let currentUserId = localStorage.getItem("currentUserId");
let isStreaming = false;  // ✅ Track if user is streaming

var myModal = new bootstrap.Modal(document.getElementById('redirectModal'), {
    backdrop: 'static',  // Prevent closing when clicking outside
    keyboard: false      // Disable closing with ESC key
});

if (!currentUserId) {
    currentUserId = Math.random().toString(36).substring(2) + Math.random().toString(36).substring(2);
    currentUserId = currentUserId.substring(0, 16);
    localStorage.setItem("currentUserId", currentUserId);  // Save to localStorage
    ws.onopen = () => {
        console.log("✅ User WebSocket Connected")
        // userConnectionMonitoring();
        ws.send(JSON.stringify({ type: "userConnectionMonitoring", userId: currentUserId }));
    };
    
}else if(currentUserId){
    ws.onopen = () => {
        console.log("✅ User WebSocket Reconnected")
        // userConnectionMonitoring();
        ws.send(JSON.stringify({ type: "userConnectionMonitoring", userId: currentUserId }));
    };
}
console.log("🆔 Current User ID:", currentUserId);
ws.onerror = (error) => {
    console.error("❌ WebSocket Error:", error)   
};
ws.onclose = (event) => {
    console.warn("⚠️ WebSocket Disconnected. Code:", event.code)
};

ws.onmessage = async (event) => {
    console.log("📩 Message received:", event.data); // Log raw message

    let data;
    try {
        data = JSON.parse(event.data);
    } catch (e) {
        console.error("❌ JSON Parse Error:", e);
        return;
    }
    console.log("🔍 Parsed Data:", data); // Log parsed message
    if (data.userId !== currentUserId) {
        console.log("🚫 Ignored message (not for this user).");
        return;
    }
    switch (data.type) {
        case "startCall":
            console.log("📞 Admin Requested a Call. Showing accept modal.");
            myModal.show();
            currentUserId = data.userId;
            break;
        
        case "reportUpdate":
                document.getElementById("info-spinner").style.display = "none";
                document.getElementById("report-form").style.display = "none"; 
                switch (data.update) {
                    case "Approved":
                        document.getElementById("message-done").style.display = "block";
                        break;
                    case "Declined":
                        document.getElementById("message-declined").style.display = "block";
                        break;
                    case "pending":
                        document.getElementById("vid-stream").classList.toggle("d-none"); 
                        document.getElementById("info-spinner").style.display = "block"; 
                        break;
                    default:
                        console.error("❌ Error: Unexpected report update received", data);
                }
            break;
        default:
    }
};
document.getElementById("incidentForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent default form submission

    document.getElementById("vid-stream").classList.toggle("d-none");
    document.getElementById("info-spinner").style.display = "block";
    document.getElementById("report-form").style.display = "none";

    let name = document.getElementById("name").value;
    let contact_number = document.getElementById("contact-number").value;
    let location = document.getElementById("location").value;
    let message = document.getElementById("message").value;

    getLocation((gpsResult) => {
        let gpsData = gpsResult.success
            ? `${gpsResult.lat}, ${gpsResult.lon}` // ✅ Success: Store Lat, Lon
            : gpsResult.error; // ❌ Error: Store Error Message

        let formData = new FormData();
        formData.append("name", name);
        formData.append("contact_number", contact_number);
        formData.append("location", location); // ✅ User inputted location
        formData.append("message", message);
        formData.append("connection_id", currentUserId);
        formData.append("gps_location", gpsData); // ✅ Always store GPS or Error

        fetch("forms/incident-report.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text()) // ✅ Read as text first
        .then(text => { 
            try {
                let data = JSON.parse(text);
                if (data.success) {
                    console.log("✅ Incident Report Submitted! ID:", data.incident_id, data.connection_id);
                    
                    ws.send(JSON.stringify({
                        type: "newIncidentReport",
                        userId: currentUserId,
                        incident_id: data.incident_id,
                        name: name,
                        contact_number: contact_number,
                        location: location,
                        message: message,
                        gps_location: gpsData, // ✅ Send GPS or error to WebSocket
                        report_status: data.report_status,
                        submitted_at: data.submitted_at
                    }));

                    console.log("✅ Form Submitted! Waiting for Admin.");
                } else {
                    console.error("❌ Error:", data.error);
                    alert("❌ Error Submitting Report: " + data.error);
                }
            } catch (error) {
                console.error("❌ JSON Parse Error:", error, "Response:", text);
                alert("❌ Server returned an invalid response.");
            }
        })
        .catch(error => console.error("❌ Fetch Error:", error));
    });
});


function getLocation(callback) {
    if (!("geolocation" in navigator)) {
        callback({ error: "Geolocation not supported by browser" });
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            callback({ success: true, lat: position.coords.latitude, lon: position.coords.longitude });
        },
        (error) => {
            let errorMessage = "";
            switch (error.code) {
                case 1:
                    errorMessage = "User denied location access";
                    break;
                case 2:
                    errorMessage = "Location information unavailable";
                    break;
                case 3:
                    errorMessage = "Location request timed out";
                    break;
                default:
                    errorMessage = "Unknown error occurred";
            }
            callback({ error: errorMessage });
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

async function acceptCall() {
    console.log("✅ User Accepted Call. Accessing Camera...");
    myModal.hide();
    document.getElementById("info-spinner").style.display = "none"; 
    document.getElementById("vid-spinner").style.display = "block"; 

    try {
        document.getElementById("info-spinner").style.display = "none"; 
        document.getElementById("vid-spinner").style.display = "none";
        document.getElementById("videoContainer").style.display = "block";

    } catch (error) {
        console.error("❌ Camera/Microphone Access Failed:", error);
    }
    ws.send(JSON.stringify({ 
        type: "acceptCall", 
        meetingId: offer, 
        userId: userId  
    }));
}