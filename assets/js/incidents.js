var getResidentIncidentID = localStorage.getItem("resident_submitted_incident_id");
var getResidentImageURL = localStorage.getItem("resident_submitted_resident_image_URL");

console.log('getResidentIncidentID ->', getResidentIncidentID);
console.log('getResidentImageURL ->', getResidentImageURL);

if (getResidentIncidentID === null) {
    console.log(">>> NO UPDATE QUERY... resident_submitted_incident_id IS NULL");
} else {
    console.log("UPDATE QUERY...");

    let formData = new FormData();
    formData.append("resident_image_url", getResidentImageURL);
    formData.append("incident_id", getResidentIncidentID);

    fetch("forms/update-incident-report.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text()) // ✅ Read as text first
    .then(text => { 
        try {
            let data = JSON.parse(text);
            console.log("Updating Incident Report data:", data);
            if (data.success) {
                console.log("✅ Updating Incident Report Submitted! ID:", data.incident_id, data.connection_id);
            
                ws.send(JSON.stringify({
                    type: "updateIncidentReport",
                    resident_image_url: data.resident_image_url,
                    incident_id: data.incident_id
                }));

                localStorage.removeItem("resident_submitted_incident_id");
                localStorage.removeItem("resident_submitted_resident_image_URL");

                console.log("✅ Updated Incident Form! Waiting for Admin.");
            } else {
                console.error("❌ Error:", data.error);
                // alert("❌ Error Updating Incident Report: " + data.error);
            }
        } catch (error) {
            console.error("❌ JSON Parse Error:", error, "Response:", text);
            // alert("❌ Server returned an invalid response.");
        }
    })
    .catch(error => console.error("❌ Fetch Error:", error));
}