$(function() {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/payment-histories",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "date" },
            { data: "paid_by" },
            { data: "received_by" },
            { data: "amount" },
            { data: "status" },
            { data: "method" },
            {
                data: "action",
                render: (data, type) => `<div class="btn-group">${data}</div>`,
            },
        ],
        fnDrawCallback: function(oSettings) {
            feather.replace({
                width: 14,
                height: 14,
            });
        },
    });

});