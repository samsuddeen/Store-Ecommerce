$(function () {
    ("use strict");
    var dtBrandTable = $(".color-list-table");
    dtBrandTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/colors",
        columns: [
            {
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "title" },
            {
                data: "colorCode",
                render: (data, type) => `<div class="h2" style='background-color:${data};padding:5px;'></div>`,
            },
            {
                data: "status",
                render: (data, type) => `${data}`,
            },
            {
                data: "action",
                render: (data, type) => `<div class="btn-group">${data}</div>`,
            },
            
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
        },
    });
});
