const ws = new WebSocket('wss://baranggay-magtanggol.online:8443');
const config = { iceServers: [{ urls: "stun:stun.l.google.com:19302" }] };
let adminUser = null;

// 🌐 Fetch Session Data First
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
                    admin_position: adminPosition,
                    admin_branch: adminBranch
                }));
            };
        } else {
            console.warn("⚠️ No admin session found.");
        }
    })
    .catch(error => console.error("❌ Error fetching session:", error));

ws.onerror = (error) => console.error("❌ WebSocket Error:", error);
ws.onclose = (event) => console.warn("⚠️ WebSocket Disconnected. Code:", event.code);

// 📩 Handle Incoming Messages
ws.onmessage = async (event) => {
    let data = JSON.parse(event.data);

    switch (data.type) {
        case "newIncidentReport":
            console.log("📝 New Submission Received:", data);
            displayIncidentReport(
                data.userId, 
                data.incident_id,
                data.reporter_name,  
                data.contact_number,
                data.location,
                data.message,
                data.report_status,
                data.resident_image_url,
                data.submitted_at,
                data.gpsLocation,
                data.video_stream_meeting_id
            );
            updateUserStatus(data.userId, data.status);
            break;
        case "userconnections":
                updateUserStatus(data.userId, data.status);
                break;
        case "userConnected":
                updateUserStatus(data.userId, "Connected");
                break
        case "userDisconnected":
                console.log(`🚨 User Disconnected: ${data.userId}`);
                updateUserStatus(data.userId,  "Disconnected");
                break;    
        case "reportUpdate":
            let incidentId = data.incidentId
            let verified_by = data.verified_by
            let reportUpdate = data.update
            
            // Check if the reportUpdate is "processing" and verified_by is NOT the logged-in admin
            let viewButton = document.querySelector(`.view-row[data-id="${incidentId}"]`);
            let row = document.querySelector(`tr[data-row-id="${incidentId}"]`);
            if (viewButton) {
                viewButton.setAttribute("data-status", reportUpdate); // Update status
                viewButton.setAttribute("data-verified-by", verified_by); // Set verified_by
                let statusCell = row.querySelector("td:nth-child(7) span"); // Adjust if column order changes
                if (statusCell) {
                    // ✅ Change badge color dynamically
                    if (reportUpdate === "processing") {
                        statusCell.textContent = `${reportUpdate} by ${verified_by}`; // Change text inside <span>
                        statusCell.className = "badge badge-info";
                    } else if (reportUpdate === "pending") {
                        statusCell.className = "badge badge-warning";
                    }  else if (reportUpdate === "Approved" || reportUpdate === "Declined") {
                        statusCell.textContent = `${reportUpdate} by ${verified_by}`; // Change text inside <span>
                        // ✅ Remove the row from the table
                        row.remove();
                        console.log(`🗑️ Removed row for Incident ID: ${incidentId} (${reportUpdate})`);
                    }
                }

            } else {
                console.error(`❌ View button for Incident ID: ${incidentId} not found!`);
            }
            break; 
        default:
            console.log("⚠️ Unknown WebSocket Message Type:", data);
    }
};           
                                                                      
// 📌 Admin Sees User Requests
function displayIncidentReport(userId, incident_id, reporter_name, contact_number, location, message, report_status, residentImageURL, submitted_at, gpsLocation) { 
    let tableBody = document.querySelector("#dataTable tbody");
    if (!tableBody) {
        console.error("❌ This is user page");
        return;
    }
    console.log(`📌 Displaying New Incident Report: ${reporter_name} (${incident_id})`);
    let existingRow = tableBody.querySelector(`tr[data-user-id="${userId}"]`);
    // Determine GPS Attributes
    if (gpsLocation.includes(",")) { 
        // ✅ GPS Success (lat, lon)
        let [lat, lon] = gpsLocation.split(",");
        gpsAttributes = `data-lat="${lat}" data-lon="${lon}"`;
    } else {
        // ❌ GPS Error
        gpsAttributes = `data-errorGps="${gpsLocation}"`;
    }
    if (existingRow) {
        console.log(`♻️ Updating existing report for user: ${userId}`);

        // Update the row instead of adding a new one
        existingRow.innerHTML = `
        <tr data-user-id="${userId}" data-row-id="${incident_id}>
            <td>${incident_id}</td>
            <td>${reporter_name}</td>
            <td>${contact_number}</td>
            <td>${location}</td>
            <td>${message}</td>
            <td>${submitted_at}</td>
            <td><span class="badge badge-warning"><h6>${report_status}</h6></span></td>
            <td class="status-cell"><span class="badge badge-secondary"><h6>Checking...</h6></span></td>
            <td>
                <button class="view-row btn btn-primary" data-toggle="modal"
                    data-target="#incidentModal"
                    data-userId="${userId}"
                    data-id="${incident_id}"
                    data-name="${reporter_name}"
                    data-contact="${contact_number}"
                    data-location="${location}"
                    data-message="${message}"
                    data-time="${submitted_at}"
                    data-status="${report_status}"
                    data-residentImage="${residentImageURL}"
                    ${gpsAttributes} >View
                </button>
            </td>
            </tr>
        `;
    } else {
        console.log(`➕ Adding new report for user: ${userId}`);

        // Create a new row
    let newRow = document.createElement("tr");
    newRow.setAttribute("data-user-id", userId);
    newRow.setAttribute("data-row-id", incident_id);
    newRow.innerHTML = `
    <td>${incident_id}</td>
    <td>${reporter_name}</td>
    <td>${contact_number}</td>
    <td>${location}</td>
    <td>${message}</td>
    <td>${submitted_at}</td>
    <td><span class="badge badge-warning"><h6>${report_status}</h6></span></td>
    <td class="status-cell"><span class="badge badge-secondary"><h6>Checking...</h6></span></td> <!-- ✅ New Status Column -->
    <td>
        <button class="view-row btn btn-primary" data-toggle="modal"
            data-target="#incidentModal"
            data-userId="${userId}"
            data-id="${incident_id}"
            data-name="${reporter_name}"
            data-contact="${contact_number}"
            data-location="${location}"
            data-message="${message}"
            data-time="${submitted_at}"
            data-status="Pending"
            ${gpsAttributes} >View
        </button>
    </td>
        
    `;
    tableBody.prepend(newRow); // ✅ Insert new report at the top
    }
}
// 📞 Admin Requests Video Call
function requestVideoCall(userId) {
    console.log("📞 Sending Video Call Request to User:", userId);
    // let callUrl = `call.php?token=${userId}`;
    
    window.open('https://baranggay-magtanggol-online.web.app/vs_admin_join_meeting.html');
    
    // Open the call window in a new tab
    window.open(callUrl, "_blank");
    ws.send(JSON.stringify({ type: "requestCall", userId: userId }));
}
function updateUserStatus(userId, status) {
    console.log(`update user triggered`)
    let userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (userRow) {
        let statusCell = userRow.querySelector(".status-cell");
        if (statusCell) {
            let color = status === "Connected" ? "badge-success" : "badge-danger";
            // ✅ Clear existing content first
            statusCell.innerHTML = "";
            let newStatus = document.createElement("span");
            newStatus.className = `status-cell badge ${color}`;
            newStatus.innerHTML = `<h6>${status}</h6>`;
            statusCell.appendChild(newStatus);
            
        }
    }
}
