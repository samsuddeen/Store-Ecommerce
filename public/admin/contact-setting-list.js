$(function() {
    ("use strict");
    var dtBrandTable = $(".tag-list-table");
    dtBrandTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/contact-setting",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "type" },
            { data: "contact_no" },
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
            reloadActionBtn();
        },
    });
});