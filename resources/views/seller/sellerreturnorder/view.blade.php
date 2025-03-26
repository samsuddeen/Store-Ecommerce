@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | order')
@extends('layouts.app')
@push('style')
<style>
    td img{
        height: 70px !important;
        display: block;
        align-content: center;
        text-align: center;
    }
</style>
@endpush
@section('content')
<div class="page-heading">
<div class="page-content fade-in-up">
    <div class="ibox invoice" id="printpage">
        <div class="invoice-header">
            <div class="row">
                <div class="col-6">
                    <h1><center>Progress Bar Goes here</center></h1>
                </div>
            </div>
        </div>

        <table class="table table-striped no-margin table-invoice">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Product Name</th>
                    <th >Unit Price</th>
                    <th>Quantity</th>
                    <th colspan="2">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sellerOrder as $key => $row)
                {{-- @dd($row) --}}
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $row->product_name }}</td>
                    <td>{{ $row->price }}</td>
                    <td>{{ $row->qty }}</td>
                    <td>{{ $row->sub_total_price }}</td>
                    
                </tr>
                @endforeach

            </tbody>
        </table>
     
        <h4>Reason:</h4>
        <p>{{@$return->reason}}</p>
      


    </div>
    <style>
        .invoice {
            padding: 20px
        }

        .invoice-header {
            margin-bottom: 50px
        }

        .invoice-logo {
            margin-bottom: 50px;
        }

        .table-invoice tr td:last-child {
            text-align: right;
        }
    </style>

</div>
<!-- END PAGE CONTENT-->


@stop
@section('footer')

<script>
     
    function printDiv() {
        var value1 = document.getElementById('printpage').innerHTML;
        var value2 = document.body.innerHTML;
        document.body.innerHTML = value1;
        window.print();
        document.body.innerHTML = value2;
        location.reload();
    }
</script>
@endsection

@push('script')
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>   
    <script type="text/javascript">
    reloadActionBtn();
     function reloadActionBtn() {
            $('.order-action').on('click', function(e) {
                e.preventDefault();
                let type = $(this).data('type');
                if(type=="cancel" || type=="reject"){
                    $('#reason-box').css({"display":"block"});
                }else{
                    $('#reason-box').css({"display":"none"});
                }
                let order_id = $(this).data('order_id');
                $('#type-input').val(type);
                $('#order_id-input').val(order_id);
            });
        }
    </script>
@endpush