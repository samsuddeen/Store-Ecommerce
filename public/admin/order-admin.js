$(function () {
    ("use strict");
    var dtBrandTable = $(".order-list-table");
    var tableData=dtBrandTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/datatables/orders",
            data: function (d) {
                d.type = $('#type-input').val();
            }
        },
        columns: [
            {
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            // { data: "all_action" },
            { data: "status" },
            { data: "order_by" },
            { data: "ref_id" },
            // { data:"seller_value"},
            { data: "total_quantity" },
            { data: "total_discount" },
            { data: "total_price" },
            { data: "payment_status" },
            { data: "action" },
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
    $('.btn-tabs').on('click', function (e) {
        e.preventDefault();
        $('#type-input').val($(this).data('type'));
        table.draw();
    });
});
