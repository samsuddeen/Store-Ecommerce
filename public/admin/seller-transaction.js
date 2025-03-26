$(function() {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/datatables/seller-transactions",
        columns: [
        {
                data: 'order_id',
                name: 'order_id'
            },
            {
                data:'ref_id',
                name:'ref_id'
            },
            {
                data:'qty_id',
                name:'qty_id'
            },
            {
                data:'total_price',
                name:'total_price'
            },
            {
                data:'payment_status',
                name:'payment_status'
            },
            {
                data:'action',
                name:'action'
            },

        ],
        fnDrawCallback: function(oSettings) {
            feather.replace({
                width: 14,
                height: 14,
            });
            reloadActionBtn();
        },
    });

});