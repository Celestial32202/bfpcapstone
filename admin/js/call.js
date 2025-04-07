const ws = new WebSocket('wss://baranggay-magtanggol.online:8443');
const config = {
    iceServers: [
        {
            urls: "stun:stun.l.google.com:19302"  // Google STUN server
        }
    ]
};

let peerConnection;
let userId;
let pendingCandidates = [];
let monitorInterval = null;

// 📌 Extract userId from URL
const urlParams = new URLSearchParams(window.location.search);
userId = urlParams.get("token");

if (!userId) {
    alert("Invalid Call Request!");
    window.close();
}

// 🌐 WebSocket Connection
ws.onopen = () => {
    console.log("✅ WebSocket Connected (Call Page)", userId);
    ws.send(JSON.stringify({ type: "callWindowConnected", userId: userId }));
    // startMonitoring();
}
ws.onerror = (error) => console.error("❌ WebSocket Error:", error);
ws.onclose = (event) => console.warn("⚠️ WebSocket Disconnected. Code:", event.code);

// function startMonitoring() {
//     if (monitorInterval) return; // Prevent duplicate intervals
//     monitorInterval = setInterval(() => {
//         console.log("📤 Sending Monitoring Ping...");
//         ws.send(JSON.stringify({ type: "callMonitoring", userId: userId }));
//     }, 500);

// }

// 📩 Handle Incoming Messages
ws.onmessage = async (event) => {
    let data = JSON.parse(event.data);
    console.log("📩 Received message:", data);

    if (data.userId !== userId) return; // Ignore messages not for this call

    switch (data.type) {
        case "offer":
            console.log("📤 Received WebRTC Offer. Handling...");
            await handleOffer(data.offer);
            break;

        case "candidate":
            console.log("📤 Received ICE Candidate. Adding...");
            if (peerConnection && peerConnection.remoteDescription) {
                await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
                console.log("✅ ICE Candidate Added.");
            } else {
                console.warn("⏳ Remote description not set yet. Storing candidate.");
                pendingCandidates.push(data.candidate);
            }
            break;
        case "userCallDisconnected": // ✅ Close call.php when user tab is closed
            document.getElementById("video-reconnecting").style.display = "none";
            userCallDisconnected();
            break;
        case "callEndedByUser": // ✅ Close call.php when user tab is closed
            console.log("🚨 User page closed. call ended by user...");
            callEndedByUser();
            break;
        case "userCallReconnecting": 
            document.getElementById("video-reconnecting").style.display = "block";
            document.getElementById("adminVideoContainer").style.display = "none";
            break;
        case "userCallReconnected": 
            document.getElementById("video-reconnecting").style.display = "none";
            document.getElementById("adminVideoContainer").style.display = "block";
            break;
            
    }
};
// 📹 Admin Handles WebRTC Offer
async function handleOffer(offer) {
    console.log("📤 Handling WebRTC Offer...", offer);

    peerConnection = new RTCPeerConnection(config);

    peerConnection.ontrack = (event) => {
        console.log("🎥 Received Remote Track:", event.streams[0]);
        let remoteVideo = document.getElementById("adminVideo");
        if (remoteVideo) {
            remoteVideo.srcObject = event.streams[0];
            document.getElementById("videoContainer").style.display = "block";
            document.getElementById("endCallBtn").style.display = "block"; // ✅ Show the "End Call" button
            document.getElementById("adminVideoContainer").style.display = "block";
            document.getElementById("video-loading").style.display = "none";
            document.getElementById("video-reconnecting").style.display = "none";
            console.log("✅ Admin Video Displayed.");
            pendingCandidates.forEach(candidate => {
                peerConnection.addIceCandidate(new RTCIceCandidate(candidate))
                    .then(() => console.log("✅ ICE Candidate Added (Pending)"))
                    .catch(err => console.error("❌ ICE Candidate Error:", err));
            });
            pendingCandidates = []; // Clear the queue

        }
    };

    await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
    console.log("✅ Remote Description Set.");
    
    peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
            console.log("📤 Sending ICE Candidate to Server...");
            ws.send(JSON.stringify({ 
                type: "candidate", 
                candidate: event.candidate, 
                userId: userId }));
        }
    };

    let answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);
    console.log("📤 Sending WebRTC Answer to Server...", answer);

    ws.send(JSON.stringify({ type: "answer", answer: answer, userId: userId }));
}
// ✅ Function to End the Call
function callEndedByUser() {
    let countdown = 5;
    const countdownElement = document.querySelector('.user-endcall #countdown'); // More specific selection
    if (peerConnection) {
        peerConnection.getSenders().forEach(sender => {
            if (sender.track) sender.track.stop(); // ✅ Stop each track (both audio & video)
        });
        peerConnection.close();
        peerConnection = null;
    }
    // Remove video from the UI
    let remoteVideo = document.getElementById("adminVideo");
    if (remoteVideo) {
        remoteVideo.srcObject = null;
    }
    document.getElementById("videoContainer").style.display = "none";
    document.getElementById("endCallBtn").style.display = "none";
    document.getElementById("user-endcall").style.display = "block";
    const timer = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        console.log("⏳ Countdown:", countdown);

        if (countdown === 0) {
            clearInterval(timer);
            window.close();
        }
    }, 1000);
}
function userCallDisconnected() {
    let countdown = 5;
    const countdownElement = document.querySelector('.user-disconnected #countdown'); // More specific selection
    if (peerConnection) {
        peerConnection.getSenders().forEach(sender => {
            if (sender.track) sender.track.stop(); // ✅ Stop each track (both audio & video)
        });
        peerConnection.close();
        peerConnection = null;
    }
    // Remove video from the UI
    let remoteVideo = document.getElementById("adminVideo");
    if (remoteVideo) {
        remoteVideo.srcObject = null;
    }
    document.getElementById("videoContainer").style.display = "none";
    document.getElementById("endCallBtn").style.display = "none";
    document.getElementById("user-disconnected").style.display = "block";
    if (!countdownElement) {
        console.error("❌ Countdown element not found!");
        return;
    }
    const timer = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        console.log("⏳ Countdown:", countdown);

        if (countdown === 0) {
            clearInterval(timer);
            document.getElementById("user-disconnected").style.display = "none";
            window.close();
        }
    }, 1000);
}
function endCall() {
    console.log("❌ Ending Call...");
    ws.send(JSON.stringify({ type: "callEndedByAdmin", userId: userId })); // Notify server

    if (peerConnection) {
        peerConnection.getSenders().forEach(sender => {
            if (sender.track) sender.track.stop(); // ✅ Stop each track (both audio & video)
        });
        peerConnection.close();
        peerConnection = null;
    }
    // Remove video from the UI
    let remoteVideo = document.getElementById("adminVideo");
    if (remoteVideo) {
        remoteVideo.srcObject = null;
    }
    document.getElementById("videoContainer").style.display = "none";
    document.getElementById("endCallBtn").style.display = "none";
    
    window.close();
}

