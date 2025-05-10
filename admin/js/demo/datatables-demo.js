
$(document).ready(function () {
  // Central config for all tables
  const tableConfigs = {
    '#dataTable': {
      ordering: false,
      lengthMenu: [5, 10, 20, 50],
      columnDefs: [
        { targets: 0, 
          className: "datatable-id-collumn" 
        },
        { targets: 1, 
          className: "datatable-name-collumn" 
        },
        { targets: 2, 
          className: "datatable-contact-collumn" 
        },
        { targets: 4, 
          className: "datatable-message-collumn" 
        },
        { targets: 5, 
          className: "datatable-status-collumn" 
        },
        { targets: 6, 
          className: "datatable-status-collumn" 
        },
        { targets: 7, 
          className: "datatable-connstats-collumn" 
        },
        { targets: 8, 
          className: "datatable-view-collumn" 
        },
      ]
    },
    '#usersTable': {
      ordering: false,
      lengthMenu: [5, 10, 20, 50],
      columnDefs: [
        { targets: 0, 
          className: "datatable-name-collumn" 
        },
        { targets: 1, 
          className: "datatable-username-collumn" 
        },
        { targets: 2, 
          className: "datatable-position-collumn" 
        },
        { targets: 3, 
          className: "datatable-branch-collumn" 
        },
        { targets: 4, 
          className: "datatable-contact-collumn" 
        },
        { targets: 5, 
          className: "datatable-email-collumn" 
        },
        { targets: 6, 
          className: "datatable-verified-collumn" 
        },
        { targets: 7, 
          className: "datatable-attempts-collumn" 
        },
        { targets: 9, 
          className: "datatable-locked-collumn" 
        },
        { targets: 10, 
          className: "datatable-options-collumn" 
        },
     
      ]
    }
  };

  // Loop over configs and apply DataTables only if the table exists
  for (const tableId in tableConfigs) {
    if ($(tableId).length) {
      $(tableId).DataTable(tableConfigs[tableId]);
    }
  }
});
