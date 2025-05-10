

function limitInput(element, maxLength) {
    if (element.value.length >= maxLength) {
        element.value = element.value.slice(0, maxLength);
    }
}

function restrictInput(event) {
    const key = event.key;
    const target = event.target;

    if (!/^\d$/.test(key) ||
        (target.value.length === 0 && key !== '0') ||
        (target.value.length === 1 && key !== '9')) {
        event.preventDefault();
    }
}
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
function generatePassword() {
    var passwordField = document.getElementById("password");
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+";
    var password = "";
    for (var i = 0; i < 20; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    passwordField.value = password;
    passwordField.removeAttribute("disabled");
    checkFormValidity(); // Ensure form is still valid after generating a password

}
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("register_form");
    const positionSelect = document.getElementById("position");
    const branchSelect = document.getElementById("branch");
    const branchContainer = branchSelect.parentElement;

    fetchBranches();
    

    function toggleBranchVisibility() {
        if (positionSelect.value === "Fire Officer" || positionSelect.value === "Fire Officer Supervisor") {
            branchContainer.style.display = "block"; // Show branch selection
            branchSelect.setAttribute("required", "true");
        } else {
            branchContainer.style.display = "none"; // Hide branch selection
            branchSelect.value = "branch-none"; // Reset selection
            branchSelect.removeAttribute("required");
        }
        checkFormValidity(); // Revalidate the form
    }

    // Hide branch selection initially
    branchContainer.style.display = "none";

    // Event listeners for form validation
    positionSelect.addEventListener("change", toggleBranchVisibility);
    branchSelect.addEventListener("change", checkFormValidity);
    form.querySelectorAll("input[required], select[required]:not(#branch)").forEach(input => {
        input.addEventListener("input", checkFormValidity);
        input.addEventListener("change", checkFormValidity);
    });
});

function fetchBranches() {
    fetch('../get_markers.php') // Update with the correct path
        .then(response => response.json())
        .then(data => {
            const branchSelect = document.getElementById("branch");
            branchSelect.innerHTML = '<option value="branch-none">What Branch</option>'; // Default option

            if (data.locations) {
                data.locations
                    .filter(location => location.type === "fire_station") // Keep only fire stations
                    .forEach(station => {
                        let option = document.createElement("option");
                        option.value = station.location;
                        option.textContent = station.location;
                        branchSelect.appendChild(option);
                    });
            } else {
                console.error("Error fetching locations:", data);
            }
        })
        .catch(error => console.error("Error:", error));
}