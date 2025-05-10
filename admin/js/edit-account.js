function checkFormValidity() {
    const form = document.getElementById("register_form");
    const submitBtn = document.getElementById("register_btn");
    const positionSelect = document.getElementById("position");
    const branchSelect = document.getElementById("branch");
    const requiredInputs = form.querySelectorAll("input[required], select[required]:not(#branch)");

    let allInputsFilled = Array.from(requiredInputs).every(input => input.value.trim() && input.value !== "position-none");

    if (positionSelect.value === "Fire Officer" || positionSelect.value === "Fire Officer Supervisor") {
        submitBtn.disabled = !(allInputsFilled && branchSelect.value !== "branch-none");
    } else {
        submitBtn.disabled = !allInputsFilled;
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("register_form");
    const positionSelect = document.getElementById("position");
    const branchSelect = document.getElementById("branch");
    const branchContainer = branchSelect.parentElement;

    // Fetch branches from the server
    fetchBranches();

    // Function to toggle visibility of branch field
    function toggleBranchVisibility() {
        if (positionSelect.value === "Fire Officer" || positionSelect.value === "Fire Officer Supervisor") {
            branchContainer.style.display = "block"; // Show the branch dropdown
            branchSelect.setAttribute("required", "true"); // Make the branch dropdown required
        } else {
            branchContainer.style.display = "none"; // Hide the branch dropdown
            branchSelect.value = "branch-none"; // Reset the branch dropdown value
            branchSelect.removeAttribute("required"); // Remove the required attribute
        }
        checkFormValidity(); // Check form validity
    }

    // Initially hide the branch field
    branchContainer.style.display = "none";

    // Set initial visibility of branch field based on the existing position
    toggleBranchVisibility(); // Ensure the branch field is shown or hidden based on the user position

    // Event listeners to toggle branch visibility when the position changes
    positionSelect.addEventListener("change", toggleBranchVisibility);
    branchSelect.addEventListener("change", checkFormValidity);
    form.querySelectorAll("input[required], select[required]:not(#branch)").forEach(input => {
        input.addEventListener("input", checkFormValidity);
        input.addEventListener("change", checkFormValidity);
    });
});

// Function to fetch branches and populate the dropdown
function fetchBranches() {
    fetch('../get_markers.php') // Replace with correct path
        .then(response => response.json())
        .then(data => {
            const branchSelect = document.getElementById("branch");
            branchSelect.innerHTML = '<option value="branch-none">What Branch</option>'; // Default option

            if (data.locations) {
                data.locations
                    .filter(location => location.type === "fire_station") // Only include fire stations
                    .forEach(station => {
                        let option = document.createElement("option");
                        option.value = station.location;
                        option.textContent = station.location;
                        branchSelect.appendChild(option);
                    });
                
                // If the user already has a branch selected, set it
                // Set selected value if applicable
                if (typeof currentBranch !== "undefined" && currentBranch !== 'branch-none') {
                    branchSelect.value = currentBranch;
                }
            } else {
                console.error("Error fetching locations:", data);
            }
        })
        .catch(error => console.error("Error:", error));
}
