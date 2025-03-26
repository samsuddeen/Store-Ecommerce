$(function() {
    ("use strict");
    var dtProductTable = $(".seller-list-table");
    dtProductTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/sellers",
        columns: [{
                data: "DT_RowIndex",
                render: (data, type) => `${data}`,
                searchable: false,
                orderable: false,
            },
            { data: "name" },
            {
                data:'status'
            },
            {
                data:'phone'
            },
            {
                data:'address'
            },
            {
                data:'document'
            },
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
            reloadAddDocument();
            reloadActionStatus();
        },
    });
});

function reloadAddDocument(){
    $('.add-document').on('click', function(e){
        let seller_id = $(this).data('seller_id');
        $('#modal-seller_id').val(seller_id);
    });
}

function reloadActionStatus() {    
    $('.order-action').on('click', function() {        
        $('#customer_id-input').val($(this).data('seller_id_new'));
        $('#status-input').val($(this).data('type_new'));
    });
}