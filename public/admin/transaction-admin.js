$(function () {
    ("use strict");
    var dtBrandTable = $(".order-list-table");
    dtBrandTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/transactions",
        columns: [
            {
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "delivery_date" },
            { data: "transaction_no" },
            { data: "ref_id" },
            { data: "order_by" },
            { data: "total_quantity" },
            { data: "total_discount" },
            { data: "total_price" },
        ],
        columnDefs: [
            {
                // For Responsive
                className: "control",
                orderable: false,
                responsivePriority: 2,
                targets: 0,
                render: function (data, type, full, meta) {
                    return "";
                },
            },
        ],
        fnDrawCallback: function (oSettings) {
            feather.replace({
                width: 14,
                height: 14,
            });
            reloadActionBtn();
        },
    });
});
