@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Order')
<style>
    .barcode-modal-col {
    display: flex;
    border: 2px solid #ebebeb;
    border-radius: 4px;
}

.barcode-modal-left {
    flex: 2;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.barcode-modal-right {
    flex: 3;
    border-left: 2px solid #ebebeb;
}

.barcode-modal-right ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.barcode-modal-left span {
    display: block;
    font-weight: 600;
    margin-top: 10px;
    font-size: 17px;
}

.barcode-modal-right ul li {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    padding: 8px 15px;
    text-align: center;
}

.barcode-modal-right ul li +li {
    border-top: 2px solid #ebebeb;
}
#barcode_modal .modal-body {
    padding: 20px;
}
#barcode_modal .modal-header{
    padding:10px 20px;
}
.barcode-modal-col +.barcode-modal-col{
    margin-top:20px;
}
.second-barcode-modal .barcode-modal-right ul li{
    text-align: left;
}
.barcode-modal-footer ul {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0;
    margin: 0;
}

.barcode-modal-footer ul li {
    list-style: none;
    font-weight: 600;
}

.barcode-modal-footer {
    margin-top: 15px;
}
.group-btns ul {
        display: flex;
        margin: 0;
        padding: 0;
    }

    .group-btns {
        padding: 0 20px;
    }

    .group-btns ul li {
        list-style: none;
    }

    .group-btns ul li+li {
        margin-left: 7px;
    }

    .group-btns .btns {
        font-weight: 500;
        display: flex;
        align-items: center;
        font-size: 12px;
        border-radius: 0.3em;
        padding: 5px 10px;
        box-shadow: inset 0 0.08em 0 rgb(255 255 255 / 70%), inset 0 0 0.08em rgb(255 255 255 / 50%);
        text-shadow: 0 1px 0 rgb(255 255 255 / 80%);
        border: 1px solid #aaa;
        color: #050505;
        background: #ffffff;
        /* border: 1px solid #ebe9f1b3; */
        /* border-color: rgba(0, 0, 0, 0.3);
        border-bottom-color: rgba(0, 0, 0, 0.5); */
        /* background-image: linear-gradient(rgba(255, 255, 255, .1), rgba(255, 255, 255, .05) 49%, rgba(0, 0, 0, .05) 51%, rgba(0, 0, 0, .1)); */
    }

    .group-btns .badge {
        background: var(--secondary-color);
        color: #ffffff;
        font-size: 10px;
        font-weight: normal;
        padding: 5px 7px;
        margin-left: 7px;
    }

    .barcode-modal-col {
        display: flex;
        border: 2px solid #ebebeb;
        border-radius: 4px;
    }

    .barcode-modal-left {
        flex: 2;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .barcode-modal-right {
        flex: 3;
        border-left: 2px solid #ebebeb;
    }

    .barcode-modal-right ul {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .barcode-modal-left span {
        display: block;
        font-weight: 600;
        margin-top: 10px;
        font-size: 17px;
    }

    .barcode-modal-right ul li {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        padding: 8px 15px;
        text-align: center;
    }

    .barcode-modal-right ul li+li {
        border-top: 2px solid #ebebeb;
    }

    #barcode_modal .modal-body {
        padding: 20px;
    }

    #barcode_modal .modal-header {
        padding: 10px 20px;
    }

    .barcode-modal-col+.barcode-modal-col {
        margin-top: 20px;
    }

    .second-barcode-modal .barcode-modal-right ul li {
        text-align: left;
    }

    .barcode-modal-footer ul {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0;
        margin: 0;
    }

    .barcode-modal-footer ul li {
        list-style: none;
        font-weight: 600;
    }

    .barcode-modal-footer {
        margin-top: 15px;
    }
    .height-sell-01 {
    height: 40px;
    border: 1px solid #ebe9f1;
    border-radius: 4px;
    }
</style>
@section('content')
    <section class="app-user-list">
        <div class="card">

          @isset($customerName)
          <h4>All Order Of {{@$customerName}}</h4>
          @endisset
            <div class="group-btns mb-2 mt-2">
                <ul>
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='0' ? 'btn-primary':''}}" data-type="0" role="button">
                            All <span class="badge">{{ @$statusArrayData['all'] }}</span>
                        </a>
                    </li>
                    @if($filterCustomer)
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='9' ? 'btn-primary':''}}" data-type="9" role="button">
                            Customer Order <span class="badge">{{ @$statusArrayData['all'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='10' ? 'btn-primary':''}}" data-type="10" role="button">
                            WholeSeller Order <span class="badge">{{ @$statusArrayData['all'] }}</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='1' ? 'btn-primary':''}}" data-type="1" role="button">
                            PENDING <span class="badge">{{ @$statusArrayData['pending'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='2' ? 'btn-primary':''}}" data-type="2" role="button">
                            READY_TO_SHIP <span class="badge">{{ @$statusArrayData['rdy'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='3' ? 'btn-primary':''}}" data-type="3" role="button">
                            DISPATCHED <span class="badge">{{ @$statusArrayData['dis'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='4' ? 'btn-primary':''}}" data-type="4" role="button">
                            SHPIED <span class="badge">{{ @$statusArrayData['ship'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='5' ? 'btn-primary':''}}" data-type="5" role="button">
                            DELIVERED <span class="badge">{{ @$statusArrayData['del'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='6' ? 'btn-primary':''}}" data-type="6" role="button">
                            CANCELED <span class="badge">{{ @$statusArrayData['can'] }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="btns btn-tabs {{@request()->get('type')=='7' ? 'btn-primary':''}}" data-type="7" role="button">
                            REJECTED <span class="badge">{{ @$statusArrayData['rej'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <form action="{{ $url }}" id="filter-form" method="get">
                <div class="table-filter">
                    <div class="table-filter-form">
                        <input type="text" value="0" id="type-inputss" name="type" value="" hidden>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="refid" name="refid" value="{{@request()->get('refid') ?? null}}" class="refid height-sell-01"
                                        placeholder="Ref Id..." />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="customername" name="customername" value="{{@request()->get('customername') ?? null}}" class="customername height-sell-01"
                                        placeholder="Customer Name..." />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="input-group input-group-sm">
                                    <input type="date" id="startDate" name="startDate" value="{{@request()->get('startDate') ?? null}}" class="startDate height-sell-01"
                                        placeholder="Start Date..." />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="input-group input-group-sm">
                                    <input type="date" id="endDate" name="endDate"value="{{@request()->get('endDate') ?? null}}"  class="endDate height-sell-01"
                                        placeholder="End Date..." />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn -sm btn-primary ">
                                    Search
                                </button>
                            </div>

                        </div>


                    </div>
                </div>
            </form>

            {{-- <x-cardHeader :href="route('order.index')" name="order">
            </x-cardHeader> --}}
            {{-- @dd($orders) --}}
            <div class="card-body border-bottom">
                <div class=" table-responsive pt-0">
                    <table class="order-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Order By</th>
                                <th>ref id</th>
                                <th>Customer Type</th>
                                <th>quantity</th>
                                <th>Discount</th>
                                <th>total</th>
                                <th>payment status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($orders as $key=>$row)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{!! getAdminOrder($row) !!}</a></td>
                                        <td>{{$row->updated_at}}</a></td>
                                        @if($filterCustomer)
                                        {{-- <td><a href='{{route('admin.customerallorder',$row->user->id)}}' target="_blank">{{$row->user->name ?? ''}} [ {{$row->user->phone}} ]</a></td> --}}
                                            <td>
                                                @if($row->user->id)
                                                    <a href="{{ route('admin.customerallorder', $row->user->id) }}" target="_blank">
                                                        {{$row->user->name ?? ''}} [ {{$row->user->phone}} ]
                                                    </a>
                                                @else
                                                    Unknown User
                                                @endif
                                            </td>
                                        @else
                                        <td><a href='javascript:;' >{{$row->user->name ?? ''}} [ {{$row->user->phone}} ]</a></td>
                                        @endif
                                        
                                        <td><a href="{{route('admin.viewOrder', $row->ref_id ?? 0)}}">{{$row->ref_id}}</a></td>
                                        <td>
                                            {{@$row->user->wholeseller ? 'Whole Seller':'Customer'}}
                                        </td>
                                        <td>
                                            
                                            {{@$row->total_quantity}}
                                        </td>
                                        <td>{{formattedNepaliNumber($row->total_discount)}}</td>
                                        <td>{{formattedNepaliNumber($row->total_price)}}</td>

                                        <td>{{$row->payment_status == 0 ? ('Not Paid ('.$row->payment_with.')') : ('Paid('.$row->payment_with.')')}} 
                                            @if(!empty($row->payment_proof))
                                            <a href="{{ Storage::url($row->payment_proof) }}">View</a>
                                        @else
                                            Cash On Delivery
                                        @endif
                                        </td>
                                        
                                        <td>
                                            {{-- <li></li> --}}
                                           <div>
                                                {!!adminOrderAction($row)!!}
                                           </div>
                                        
                                            <div>
                                                <a href="{{ route('user-order-detail-pdfs', @$row->ref_id) }}">Download Invoice</a>
                                            </div>
                                          
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $orders->links() }}
                    @endif
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure </h1>
                    <p class="text-center">Please Confirm</p>
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{ route('order.status') }}">
                        @csrf
                        @method('PATCH')
                        <div class="col-12" style="display: none" id="reason-box">
                            <label class="form-label" for="modalAddCardNumber">Reason/Remarks <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <input type="text" name="type" id="type-input" hidden>
                        <input type="text" name="order_id" id="order_id-input" hidden>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="barcode_modal">
       
        </div>
    </div>

    <div class="modal fade" id="reasonCancel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reasonData">
              
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="assignToDeliveryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Assign to Delivery <span class="text text-danger">*</span></h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deliveryData">
              <form action="{{ route('task.store') }}" id="taskAssignForm" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="order_id" name="order_id">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Assigned">Assigned</option>
                            <option value="Pending">Pending</option>
                            <option value="In-Progress">In-Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Overdue">Overdue</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="action">Action</label>
                        <select name="action_id" id="action_id" class="form-control">
                            @foreach ($actions as $action)
                                <option value="{{ $action->id }}" {{ $action->is_default == 1 ? 'selected' : '' }}>{{ $action->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="priority">Priority</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="None">None</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" name="start_date">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="start_date">Due Date</label>
                        <input type="date" class="form-control" name="due_date">
                    </div>
                    <div class="col-12">
                        <label for="asignto">Assign To</label>
                        <select name="members[]" id="assigned_to" class="form-control">
                            <option value="">Select User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->roles->first()->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary my-1">Assign Task</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
@endsection
@push('style')
    {{-- @include('admin.includes.datatables') --}}
@endpush
@push('script')
    <script>
        reloadActionBtn();
        function reloadActionBtn() {
            $('.order-action').on('click', function(e) {
                e.preventDefault();
                let type = $(this).data('type');
                if (type == "cancel" || type == "reject") {
                    $('#reason-box').css({
                        "display": "block"
                    });
                } else {
                    $('#reason-box').css({
                        "display": "none"
                    });
                }
                let order_id = $(this).data('order_id');
                $('#type-input').val(type);
                $('#order_id-input').val(order_id);
            });
        }
    </script>
    {{-- <script src="{{ asset('admin/order-admin.js') }}" defer></script> --}}

    <script>
        $(document).on('click','.barcode_btn',function()
        {
            var order_id=$(this).attr('data-orderId');
            $.ajax({
                url:"{{ route('admin.generateBarcode')}}",
                type:"get",
                data:{
                    orderId:parseInt(order_id)
                },
                success:function(response)
                {
                    $('#barcode_modal').replaceWith(response);
                    $('#exampleModal').modal('show');
                }
            });
        });
    </script>

<script>
    $(document).on('click','.cancelReason',function()
    {
        var reasonId=$(this).attr('data-orderId');
        $('#reasonData').html('');
            $.ajax({
                url:"{{route('getReason')}}",
                type:"get",
                data:{
                    reasonId:reasonId
                },
                success:function(response)
                {
                    if(response.error)
                    {
                        $('#reasonData').html('');
                        return false;
                    }
                    $('#reasonData').html(response.data);
                }
            });
    });
</script>

<script>
    $(document).on('click','.assignTask',function(){
        let order_id = $(this).data('orderid');
        $('#assignToDeliveryModal').modal('show');
        $('#order_id').val(order_id);
    });
    $(document).on('click','.btn-tabs',function(e){
            e.preventDefault();
            const dataType=$(this).attr('data-type');
            $(this).addClass('btn-primary');
           $('#type-inputss').val(dataType);
           $('#filter-form').submit();
        });
</script>
@endpush

