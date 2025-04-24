$(document).ready(function() {
    let actionType = ''; // "delete" or "lock"
    let userId = '';

    // When trash icon clicked
    $('#usersTable').on('click', '.delete-user', function() {
        userId = $(this).closest('tr').data('user-id').trim();
        actionType = 'delete';
        $('#confirmActionMessage').text('Are you sure you want to delete this account?');
        $('#confirmActionModal').modal('show');
    });

    $('#usersTable').on('click', '.unlock-user', function() {
        userId = $(this).closest('tr').data('user-id').trim();
        actionType = 'unlock';
        $('#confirmActionMessage').text('Are you sure you want to unlock this account?');
        $('#confirmActionModal').modal('show');
    });
    // When lock icon clicked
    $('#usersTable').on('click', '.lock-user', function() {
        userId = $(this).closest('tr').data('user-id').trim();
        actionType = 'lock';
        console.log(actionType);
        console.log(userId);
        $('#confirmActionMessage').text('Are you sure you want to lock this account?');
        $('#confirmActionModal').modal('show');
    });
    $('#usersTable').on('click', '.undelete-user', function() {
        userId = $(this).closest('tr').data('user-id').trim();
        actionType = 'undelete';
        $('#confirmActionMessage').text('Are you sure you want to restore this account?');
        $('#confirmActionModal').modal('show');
    });
    $('#usersTable').on('click', '.edit-user', function() {
        userId = $(this).closest('tr').data('user-id').trim();
        actionType = 'edit';
        $('#confirmActionMessage').text('Are you sure you want to edit this account?');
        $('#confirmActionModal').modal('show');
    });
    // Confirm Button inside modal
    $('#confirmActionBtn').click(function() {
        if (actionType && userId) {
            if (actionType === 'edit') {
                // Create a form dynamically and submit it using POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'edit-account.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'admin_id';
                input.value = userId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            } else {
                fetch('configs/actions_mngmnt.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=${actionType}&user_id=${userId}`
                })
                .then(response => response.text())
                .then(result => {
                    if (result.trim() === "success") {
                        $('#confirmActionModal').modal('hide');
                
                        if (actionType === 'delete') {
                            alert('Account successfully deleted!');
                            location.reload();
                        } else if (actionType === 'undelete') {
                            alert('Account successfully restored!');
                            location.reload();
                        } else if (actionType === 'lock') {
                            alert('Account successfully locked!');
                            location.reload();
                        }
                        
                        else if (actionType === 'unlock') {
                            alert('Account successfully unlocked!');
                            location.reload();
                        }
                    } else {
                        alert('Action failed.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Server error.');
                });
            }   
        }
    });
});
