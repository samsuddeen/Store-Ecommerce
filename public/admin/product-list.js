$(function() {
    ("use strict");
    var dtProductTable = $(".product-list-table");
    dtProductTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/products",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "name_element" },
            { data: "category" },
            {data:'stock'},
            {data:'status'},
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
                height: 14
            });
        },
    });
});
