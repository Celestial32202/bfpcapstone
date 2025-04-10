var meetingId = "";
const ws = new WebSocket('wss://baranggay-magtanggol.online:8443');
const config = {
    iceServers: [
        {
            urls: "stun:stun.l.google.com:19302"  // Google STUN server
        }
    ]
};
const fetchMeetingRooms = async (_) => {
    console.log("fetchMeetingRooms...");
    var meetingRooms = await db.collection(meetingRef + meetingsDomain + "/" + roomRef).get();
    console.log("total meetingRooms ->", meetingRooms.docs.length);
    meetingRooms.docs.forEach(v => {
        const d = v.data();
        meetingId = d.meeting_id;
    });
    // console.log("meetingId ->", meetingId);
}

fetchMeetingRooms();

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
        case "answer":
            console.log("📩 Received WebRTC Answer. Setting Remote Description...");
            if (peerConnection) {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(data.answer));
                console.log("✅ Remote Description Set Successfully.");
                } else {
                    console.error("❌ peerConnection is NULL when setting remote description!");
                }
            break;
        case "callEndedByAdmin":
                console.log("🛑 Call ended by the admin.");
                document.getElementById("info-spinner").style.display = "block";
                stopUserVideoCall();
                alert("Call has ended.");
            break;
        case "callEndedByServer":
                console.log("🛑 Call ended by the server.");
                document.getElementById("message-done").style.display = "block";
                stopUserVideoCall();
                alert("Call has ended.");
            break;
        case "callWindowReconnected":
                console.log("🔄 Call was active, reconnecting...");
                document.getElementById("message-reconnecting").style.display = "none";
                await acceptCall();  
            break;
        case "adminCallDisconnected":
                console.log("🔄 Call disconnected...");
                stopUserVideoCall();
                document.getElementById("info-spinner").style.display = "block";
                document.getElementById("message-reconnecting").style.display = "none";
            break;
        case "adminCallReconnecting":
                console.log("🔄 Call was active, reconnecting...");
                stopUserVideoCall();
                document.getElementById("message-reconnecting").style.display = "block";
            break;
        case "userCallReconnected":
                console.log("🔄 Call was tite, reconnecting...");
                document.getElementById("vid-stream").classList.toggle("d-none"); 
                document.getElementById("report-form").style.display = "none"; 
                document.getElementById("message-reconnecting").style.display = "none";
                document.getElementById("user-reconnecting").style.display = "block";
                reconnecting_countdown();
            break;
        case "userCallReconnecting":
                console.log("🔄 Call was active, reconnecting...");
                document.getElementById("vid-stream").classList.toggle("d-none"); 
                document.getElementById("message-reconnecting").style.display = "block"; 
                document.getElementById("report-form").style.display = "none"; 
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

        console.log("incident-report meetingId ->", meetingId);

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
                        submitted_at: data.submitted_at,
                        video_stream_meeting_id: meetingId
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

function reconnecting_countdown(){
    let countdown = 3;
        const countdownElement = document.getElementById('countdown');
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown === 0) {
                clearInterval(timer);
                document.getElementById("user-reconnecting").style.display = "none";
                acceptCall();
            }
        }, 1000);
}
async function acceptCall() {
    console.log("✅ User Accepted Call. Accessing Camera...");
    myModal.hide();
    document.getElementById("info-spinner").style.display = "none"; 
    document.getElementById("vid-spinner").style.display = "block"; 

    try {
        let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        console.log("🎥 Camera Access Granted. Starting Call...");
        
        document.getElementById("userVideo").srcObject = stream;
        document.getElementById("info-spinner").style.display = "none"; 
        document.getElementById("vid-spinner").style.display = "none";
        document.getElementById("videoContainer").style.display = "block";

        isStreaming = true;  // ✅ Mark streaming as active
        // startMonitoring();  // ✅ Start monitoring

        startUserVideoCall(stream, currentUserId);
    } catch (error) {
        console.error("❌ Camera/Microphone Access Failed:", error);
    }
}
async function startUserVideoCall(stream, userId) {
    console.log("📡 Starting WebRTC Peer Connection...");
    peerConnection = new RTCPeerConnection(config);

    stream.getTracks().forEach(track => {
        peerConnection.addTrack(track, stream);
        console.log("🎥 Added Track:", track.kind);
    });

    peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
            console.log("📤 Sending ICE Candidate to Server...");
            ws.send(JSON.stringify({ 
                type: "candidate", 
                candidate: event.candidate, 
                userId: userId  
            }));
        }
    };

    let offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);
    console.log("📤 Sending WebRTC Offer to Server...", offer);

    ws.send(JSON.stringify({ 
        type: "offer", 
        offer: offer, 
        userId: userId  
    }));
    ws.send(JSON.stringify({ 
        type: "ongoingCalls", 
        status: "active", 
        userId: userId  
    }));
}
function stopUserVideoCall() {
    console.log("🛑 Stopping User Video Stream...");
    // 🔴 Stop all media tracks
    let videoElement = document.getElementById("userVideo");
    if (videoElement.srcObject) {
        videoElement.srcObject.getTracks().forEach(track => track.stop());
    }
    // 🔴 Hide video container
    document.getElementById("videoContainer").style.display = "none";

    // 🔴 Close WebRTC connection
    if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
    }
}
function endCall() {
    console.log("❌ Ending Call...");
    let videoElement = document.getElementById("userVideo");
    if (videoElement.srcObject) {
        videoElement.srcObject.getTracks().forEach(track => track.stop());
    }
    // 🔴 Hide video container
    document.getElementById("videoContainer").style.display = "none";
    ws.send(JSON.stringify({ type: "callEndedByUser", userId: currentUserId })); 

    if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
    }
}