$(function() {
    ("use strict");
    var dtOrderTable = $(".order-list-table");
    dtOrderTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/orders",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "ref_id" },
            { data: "total_price" },
            { data: "total_quantity" },
            { data: "coupon_name" },
            { data: "payment_status" },
            { data: "approve"},
            // { data: "image" },
            {
                data: "status",
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








  