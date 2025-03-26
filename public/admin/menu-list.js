$(function() {
    ("use strict");
    var dtBrandTable = $(".category-list-table");
    dtBrandTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/menus",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "position" },
            { data: "name" },
            { data: "image"  },
            { data: "type" },
            { data: "show_on" },
            { data: "status" },
            {
                data: "total_children",
                searchable: false,
                orderable: false,
            },
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