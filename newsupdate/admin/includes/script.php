    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
let indexColor = 2;
let indexSize = 2;

function checkForDuplication(type) {
    let inputIds = document.querySelectorAll(`#${type}-input-group input`);
    let messages = document.querySelectorAll(`#${type}-input-group .text-danger`);
    let addButton = document.getElementById(`add-${type}-btn`);

    let values = Array.from(inputIds).map(input => input.value.trim().toLowerCase());
    let duplicateValues = findDuplicates(values);

    messages.forEach(message => {
        message.style.display = "none";
    });

    duplicateValues.forEach(duplicateValue => {
        let duplicateId = values.indexOf(duplicateValue);
        messages[duplicateId].style.display = "block";
    });
    if (duplicateValues.length > 0) {
        addButton.classList.add("disabled");
    } else {
        addButton.classList.remove("disabled");
    }
}
$(document).on("keydown", ".no-spinners", function(e) {
    if (e.which === 38 || e.which === 40) {
        e.preventDefault();
    }
});
// Function to find duplicate values in an array
function findDuplicates(arr) {
    let sortedArr = arr.slice().sort();
    let duplicates = [];
    for (let i = 0; i < sortedArr.length - 1; i++) {
        if (sortedArr[i + 1] === sortedArr[i]) {
            duplicates.push(sortedArr[i]);
        }
    }
    return duplicates;
}

function addInput(type) {
    let inputGroup = document.createElement("div");
    inputGroup.classList.add("input-group", "mb-2");

    let input = document.createElement("input");
    input.setAttribute("type", "text");
    input.classList.add("form-control");
    input.setAttribute("id", `${type}-variation-${type === 'color' ? indexColor : indexSize}`);
    input.setAttribute("oninput", `enableButton('add-${type}-btn', '${type}-input-group')`);

    let message = document.createElement("div");
    message.classList.add("text-danger", "form-text", "mb-2");
    message.style.display = "none";
    message.textContent = `Options of ${type} variations should be different.`;
    message.setAttribute("id", `${type}-variation-${type === 'color' ? indexColor : indexSize}`);

    let deleteButton = document.createElement("span");
    deleteButton.classList.add("btn", "btn-primary");
    deleteButton.textContent = "delete";
    deleteButton.addEventListener("click", function() {
        inputGroup.remove();
        checkEmptyVariation(type);
        updateVariationList();

    });

    inputGroup.appendChild(input);
    inputGroup.appendChild(deleteButton);

    let containerId = `${type}-input-group`;
    let container = document.getElementById(containerId);

    if (container) {
        container.appendChild(inputGroup);
        container.appendChild(message);
        document.getElementById(`add-${type}-btn`).classList.add("disabled");
    } else {
        console.error(`Container with id '${containerId}' not found.`);
    }

}
// Function to check if all input boxes in a table have a value
function checkTableInputs(tableId, type) {
    var table = document.getElementById(tableId);
    if (!table) {
        console.error(`Table with ID '${tableId}' not found.`);
        return false;
    }

    // Get all input elements within the specified table
    var inputs = table.querySelectorAll("input");

    // Check if any input box within the table is empty
    var isEmpty = Array.from(inputs).some(input => input.value.trim() === "");

    if (!isEmpty) {
        // Check for duplications in color or size
        let values = Array.from(inputs).map(input => input.value.trim().toLowerCase());
        let duplicateValues = findDuplicates(values);

        return duplicateValues.length === 0;
    }

    return false;
}

// Function to enable or disable the Submit button based on input validation



function updateVariationList() {
    let colorInputs = document.querySelectorAll("#color-input-group input");
    let sizeInputs = document.querySelectorAll("#size-input-group input");

    let tableBody = document.getElementById("variation-table-body");
    tableBody.innerHTML = ""; // Clear the existing rows

    colorInputs.forEach((colorInput, colorIndex) => {
        let color = colorInput.value.trim();

        sizeInputs.forEach((sizeInput, sizeIndex) => {
            let size = sizeInput.value.trim();

            if (color !== "") {
                // Create a new row
                let row = document.createElement("tr");
                // Add cells for color and size
                row.id = `variation-row-${colorIndex}-${sizeIndex}`;
                let colorCell = document.createElement("th");
                colorCell.setAttribute("scope", "row");
                colorCell.textContent = color;
                row.appendChild(colorCell);

                let sizeCell = document.createElement("td");
                sizeCell.textContent = size;
                row.appendChild(sizeCell);

                // Add empty cells for Prices, Stock, and SKU
                for (let i = 0; i < 3; i++) {
                    let emptyCell = document.createElement("td");
                    let input = document.createElement("input");
                    input.setAttribute("type", "number");
                    input.classList.add("form-control", "no-spinners");
                    emptyCell.appendChild(input);
                    row.appendChild(emptyCell);
                }
                let commentsCell = document.createElement("td");
                let commentsDiv = document.createElement("div");
                commentsDiv.classList.add("form-floating");
                let textarea = document.createElement("textarea");
                textarea.classList.add("form-control");
                textarea.setAttribute("style", "height: 150px;");
                let label = document.createElement("label");
                commentsDiv.appendChild(textarea);
                commentsDiv.appendChild(label);
                commentsCell.appendChild(commentsDiv);
                row.appendChild(commentsCell);

                let fileCell = document.createElement("td");
                let fileInput = document.createElement("input");
                fileInput.setAttribute("type", "file");
                fileInput.classList.add("form-control", "bg-dark");
                fileInput.setAttribute("name", `photo[]`); // Add name attribute for file uploads
                fileCell.appendChild(fileInput);
                row.appendChild(fileCell);

                tableBody.appendChild(row);
            }
        });
    });
}

document.getElementById('toggleOldPassword').addEventListener('click', function() {
    togglePasswordVisibility('oldpword-input', 'toggleOldPassword');
});

document.getElementById('toggleNewPassword').addEventListener('click', function() {
    togglePasswordVisibility('newpword-input', 'toggleNewPassword');
});

document.getElementById('toggleRepeatPassword').addEventListener('click', function() {
    togglePasswordVisibility('rpt-newpword-input', 'toggleRepeatPassword');
});

function togglePasswordVisibility(inputId, iconId) {
    var passwordInput = document.getElementById(inputId);
    var eyeIcon = document.getElementById(iconId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    }
}

function checkEmptyVariation(type) {
    let variationInputIds = document.querySelectorAll(`#${type}-input-group input`);
    let addButton = document.getElementById(`add-${type}-btn`);

    let isEmpty = Array.from(variationInputIds).some(input => input.value.trim() === "");

    if (isEmpty) {
        addButton.classList.add("disabled");
    } else {
        addButton.classList.remove("disabled");
    }
}


document.getElementById("add-color-btn").addEventListener("click", function() {
    addInput("color");
    indexColor++;
    updateVariationList();
});

document.getElementById("add-size-btn").addEventListener("click", function() {
    addInput("size");
    indexSize++;
    updateVariationList();
});


// Get all input elements within the specified container
function enableButton(btnId, containerId) {
    var container = document.getElementById(containerId);
    var button = document.getElementById(btnId);

    // Get all input elements within the specified container
    var inputs = container.querySelectorAll("input");

    // Check if any input box within the container is empty
    var isEmpty = Array.from(inputs).some(input => input.value.trim() === "");

    if (isEmpty) {
        button.classList.add("disabled");
    } else {
        button.classList.remove("disabled");
    }
}

document.getElementById("color-input-group").addEventListener("input", function(event) {
    if (event.target && event.target.id.startsWith("color-variation")) {
        checkForDuplication('color');
        updateVariationList();
    }
});

// Trigger checkForDuplication when any size input changes
document.getElementById("size-input-group").addEventListener("input", function(event) {
    if (event.target && event.target.id.startsWith("size-variation")) {
        checkForDuplication('size');
        updateVariationList();
    }
});

function submitData() {
    let productName = document.getElementById('product-name').value;
    let category = document.getElementById('category-options').value;
    let productType = document.getElementById('product_type').value;

    let variations = [];
    let rows = document.querySelectorAll("#variation-table-body tr");

    rows.forEach((row, index) => {
        let color = row.cells[0].textContent;
        let size = row.cells[1].textContent;
        let prices = row.cells[2].querySelector("input").value;
        let quantityPerOrder = row.cells[3].querySelector("input").value;
        let stock = row.cells[4].querySelector("input").value;
        let productDescription = row.cells[5].querySelector("textarea").value;

        variations.push({
            color: color,
            size: size,
            prices: prices,
            quantityPerOrder: quantityPerOrder,
            stock: stock,
            productDescription: productDescription,
        });
    });

    let formData = new FormData();
    formData.append('productName', productName);
    formData.append('category', category);
    formData.append('productType', productType);
    formData.append('variations', JSON.stringify(variations));

    let fileInputs = document.querySelectorAll("#variation-table-body input[type='file']");
    fileInputs.forEach((fileInput, index) => {
        let files = fileInput.files;
        if (files.length > 0) {
            formData.append('photo[]', files[0]);
        } else {
            formData.append('photo[]', ''); // If no file is selected, you can adjust this accordingly
        }
    });

    fetch('functions/functions.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            if (data.status === 'success') {
                const successMessageDiv = document.getElementById('success-message');
                successMessageDiv.style.display = 'block';
                successMessageDiv.textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error during form submission. Please try again.');
        });
}
    </script>
    </body>

    </html>