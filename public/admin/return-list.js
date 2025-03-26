$(function() {
    ("use strict");
    var dtReturnTable = $(".return-list-table");
    dtReturnTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/returns",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "customer" },
            { data: "product" },
            {data: "seller"},
            { data: "rating" },            
            { data: "comment" },
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
