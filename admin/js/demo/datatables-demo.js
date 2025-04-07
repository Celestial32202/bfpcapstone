// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    ordering: false,
    lengthMenu: [5, 10, 20, 50],
    columnDefs: [
      { 
        targets: 0, // Index of the column (starting from 0)
        className: "datatable-id-collumn" // Optional: Add CSS class for styling
      },
      { 
        targets: 1, // Index of the column (starting from 0)
        className: "datatable-name-collumn" // Optional: Add CSS class for styling
      },
      { 
        targets: 2, // Index of the column (starting from 0)
        className: "datatable-contact-collumn" // Optional: Add CSS class for styling
      },
      { 
        targets: 4, // Index of the column (starting from 0)
        className: "datatable-message-collumn" // Optional: Add CSS class for styling
      },
      { 
        targets: 5, // Index of the column (starting from 0)
        className: "datatable-status-collumn" // Optional: Add CSS class for styling
      },
      { 
        targets: 6, // Index of the column (starting from 0)
        className: "datatable-status-collumn" // Optional: Add CSS class for styling
      },
      { 
        targets: 7, // Index of the column (starting from 0)
        className: "datatable-connstats-collumn" // Optional: Add CSS class for styling
      },
      { 
        targets: 8, // Index of the column (starting from 0)
        className: "datatable-view-collumn" // Optional: Add CSS class for styling
      }
  ]
  });
});
