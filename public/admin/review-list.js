$(function() {
    ("use strict");
    var dtReviewTable = $(".review-list-table");
    dtReviewTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/reviews",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "product" },
            { data: "customer" },
            {data: "amount"},
            { data: "reason" },            
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
