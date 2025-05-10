const ws = new WebSocket('wss://baranggay-magtanggol.online:8443');
const config = {
    iceServers: [
        {
            urls: "stun:stun.l.google.com:19302"  // Google STUN server
        }
    ]
};
let userId;

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

// 📩 Handle Incoming Messages
ws.onmessage = async (event) => {
    let data = JSON.parse(event.data);
    console.log("📩 Received message:", data);

    if (data.userId !== userId) return; // Ignore messages not for this call

    switch (data.type) {
        case "acceptedCall": // ✅ Close call.php when user tab is closed
            acceptCall();
            meetingId = data.meetingId;
            userId = data.userId
            break;
    }
};
// 📹 Admin Handles WebRTC Offer
async function acceptedCall(userId, meetingId) {
}
