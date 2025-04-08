<?php
include('includes/header.php');
include('includes/navbar.php');

$users = getUserDetails();
?>
<div class="form_overlay" id="form-overlay"></div>
<div id="fullDetailsModal" class="form_container">
    <i class="fas fa-times form_close"></i>
    <div id="full_details" class="bg-secondary rounded p-4">
        <h6 class="mb-4">Full Details</h6>
        <form>
            <div class="row g-4">
                <div class="col-sm-12 col-xl-6">
                    <div class="mb-3">
                        <label for="fullDetailsFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="fullDetailsFirstName" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="fullDetailsMiddleName" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="fullDetailsLastName" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsBirthDate" class="form-label">Birth Date</label>
                        <input type="text" class="form-control" id="fullDetailsBirthDate" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsEmail" class="form-label">Email</label>
                        <input type="text" class="form-control" id="fullDetailsEmail" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsPhoneNumber" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="fullDetailsPhoneNumber" disabled>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-6">
                    <div class="mb-3">
                        <label for="fullDetailsFullAddress" class="form-label">Full Address</label>
                        <input type="text" class="form-control" id="fullDetailsFullAddress" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsCivilStatus" class="form-label">Civil Status</label>
                        <input type="text" class="form-control" id="fullDetailsCivilStatus" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsAgeGroup" class="form-label">Age Group</label>
                        <input type="text" class="form-control" id="fullDetailsAgeGroup" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsYouthClass" class="form-label">Youth Class</label>
                        <input type="text" class="form-control" id="fullDetailsYouthClass" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsYouthClassNeeds" class="form-label">Youth Class Needs</label>
                        <input type="text" class="form-control" id="fullDetailsYouthClassNeeds" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsWorkStatus" class="form-label">Work Status</label>
                        <input type="text" class="form-control" id="fullDetailsWorkStatus" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsEducBackground" class="form-label">Educ Background</label>
                        <input type="text" class="form-control" id="fullDetailsEducBackground" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsSKVoter" class="form-label">SK Voter</label>
                        <input type="text" class="form-control" id="fullDetailsSKVoter" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="fullDetailsRegisteredVoter" class="form-label">Registered Voter</label>
                        <input type="text" class="form-control" id="fullDetailsRegisteredVoter" disabled>
                    </div>
                </div>
            </div>
            <button type="button" class="button">Print</button>
        </form>
    </div>
</div>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="bg-secondary text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Registered Users</h6>
                <button id="printSelected" class="btn btn-primary">Print Selected</button>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-white">
                            <th scope="col"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                            <th scope="col">First Name</th>
                            <th scope="col">Middle Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Birth Date</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><input type="checkbox" class="userCheckbox form-check-input" value="<?php echo $user['id_counter']; ?>"></td>

                                <td><?php echo $user['first_name']; ?></td>
                                <td><?php echo $user['middle_name']; ?></td>
                                <td><?php echo $user['last_name']; ?></td>
                                <td><?php echo $user['birth_date']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['phone_number']; ?></td>
                                <td><a class="btn btn-sm btn-primary full-details-btn" href="#" data-user-email="<?php echo $user['email']; ?>">Full Details</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var fullDetailsButtons = document.querySelectorAll('.full-details-btn');
        var overlay = document.getElementById('form-overlay');
        var modal = document.getElementById('fullDetailsModal');
        var closeButton = document.querySelector('.form_close');
        var selectAllCheckbox = document.getElementById('selectAll');
        var userCheckboxes = document.querySelectorAll('.userCheckbox');
        var printSelectedButton = document.getElementById('printSelected');

        fullDetailsButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var userEmail = this.getAttribute('data-user-email');
                displayUserDetails(userEmail);
                showModal();
            });
        });

        function displayUserDetails(email) {
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure the request
            xhr.open('POST', 'functions/get_user_details.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Set up the callback function to handle the response
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    // Parse the JSON response
                    var response = JSON.parse(xhr.responseText);
                    var userDetails = response.user_details;
                    var surveyDetails = response.survey_details;

                    // Populate the modal with the user details
                    document.getElementById('fullDetailsFirstName').value = userDetails.first_name;
                    document.getElementById('fullDetailsMiddleName').value = userDetails.middle_name;
                    document.getElementById('fullDetailsLastName').value = userDetails.last_name;
                    document.getElementById('fullDetailsBirthDate').value = userDetails.birth_date;
                    document.getElementById('fullDetailsEmail').value = userDetails.email;
                    document.getElementById('fullDetailsPhoneNumber').value = userDetails.phone_number;

                    document.getElementById('fullDetailsFullAddress').value = surveyDetails.full_address;
                    document.getElementById('fullDetailsCivilStatus').value = surveyDetails.civil_status;
                    document.getElementById('fullDetailsAgeGroup').value = surveyDetails.age_group;
                    document.getElementById('fullDetailsYouthClass').value = surveyDetails.youth_class;
                    document.getElementById('fullDetailsYouthClassNeeds').value = surveyDetails.youth_class_needs;
                    document.getElementById('fullDetailsWorkStatus').value = surveyDetails.work_status;
                    document.getElementById('fullDetailsEducBackground').value = surveyDetails.educ_background;
                    document.getElementById('fullDetailsSKVoter').value = surveyDetails.sk_voter;
                    document.getElementById('fullDetailsRegisteredVoter').value = surveyDetails.registered_voter;
                } else {
                    console.error('Error retrieving user details');
                }
            };

            // Handle network errors
            xhr.onerror = function() {
                console.error('Network error occurred');
            };

            // Send the request with the email as the data
            xhr.send('email=' + encodeURIComponent(email));
        }
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
        // Function to show the modal
        closeButton.addEventListener('click', function() {
            hideModal();
        });
        
        overlay.addEventListener('click', function() {
            hideModal();
        });

        function showModal() {
            modal.classList.add('show');
            overlay.classList.add('show');
        }

        function hideModal() {
            modal.classList.remove('show');
            overlay.classList.remove('show');
        }
        printSelectedButton.addEventListener('click', function() {
            var selectedUsers = Array.from(userCheckboxes).filter(function(checkbox) {
                return checkbox.checked;
            }).map(function(checkbox) {
                return checkbox.value;
            });

            if (selectedUsers.length > 0) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'functions/generate_pdf.php';
                selectedUsers.forEach(function(userId) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'user_ids[]';
                    input.value = userId;
                    form.appendChild(input);
                });
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('Please select at least one user.');
            }
        });
    });
</script>
</body>

</html>