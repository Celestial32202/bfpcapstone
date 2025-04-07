// Call the dataTables jQuery plugin
$(document).ready(function() {
    $('#dataTable').DataTable({
      ordering: true,
      lengthMenu: [5, 10, 20, 50],
      columnDefs: [
        { 
          targets: 0, // Index of the column (starting from 0)
          className: "datatable-id-collumn" // Optional: Add CSS class for styling
        },
        { 
          targets: 2, // Index of the column (starting from 0)
          className: "datatable-contact-collumn" // Optional: Add CSS class for styling
        },
        { 
          targets: 4, // Index of the column (starting from 0)
          className: "datatable-message-collumn" // Optional: Add CSS class for styling
        }
    ]
    });
  });
  