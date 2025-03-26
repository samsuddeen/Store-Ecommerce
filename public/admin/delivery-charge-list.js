$(function() {
    ("use strict");
    var dtBrandTable = $(".delivery-charge-list-table");
    dtBrandTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/delivery-charges",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "from" },
            { data: "to" },
            { data: "Branch Delivery ($)" },
            { data: "Door Delivery ($)" },
            { data: "Branch Express Delivery ($)" },
            { data: "Branch Normal Delivery ($)" },
            { data: "Door Express Delivery ($)" },
            { data: "Door Normal Delivery ($)" },
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