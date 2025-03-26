$(function() {
    alert('hub');
    ("use strict");
    var dtBrandTable = $(".hub-list-table");
    dtBrandTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/hubs",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "title" },
            { data: "address" },
            {data:"near_place"},
            {data:"status"},
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
