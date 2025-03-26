$(function() {
    ("use strict");
    var dtBrandTable = $(".location-list-table");
    dtBrandTable.DataTable({
        processing: true,
        stateSave: true,
        serverSide: true,
        ajax: "/datatables/locations",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "title" },
            { data: "belongs_to" },
            { data: "near_to" },
            { data: "charge" },
            { data: "status" },
            {
                data: "action",
                render: (data, type) => `<div class="btn-group">${data}</div>`,
            },
        ],
        columnDefs: [{
            // For Responsive
            className: "control",
            orderable: false,
            responsivePriority: 2,
            targets: 0,
            render: function(data, type, full, meta) {
                return "";
            },
        }, ],
        fnDrawCallback: function(oSettings) {
            feather.replace({
                width: 14,
                height: 14,
            });
        },
    });
});
