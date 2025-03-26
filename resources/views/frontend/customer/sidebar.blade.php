<div class="dashboard_sidebar">
    <div class="dash-toggle">
        <i class="las la-times"></i>
    </div>
    <ul class="sideBar_menu">
        <li><a href="{{ route('Cdashboard') }}" ><i class="lar la-address-card"></i>Dashboard</a></li>
        <li class="{{(request()->route()->getName()=='Cprofile' || request()->route()->getName()=='addressBook' || request()->route()->getName()=='shipping.address.book' || request()->route()->getName()=='pamentOption' || request()->route()->getName()=='voucher' ) ? 'visible_me':''}}"><a href="#" ><i class="lar la-user "></i>Manage My Account</a>
            <ul class="sidebar_sub_Menu">
                <li><a href="{{ route('Cprofile') }}" class="{{(request()->route()->getName()=='Cprofile')? 'active':''}}">Profile</a></li>
                <li><a href="{{ route('addressBook') }}" class="{{(request()->route()->getName()=='addressBook')? 'active':''}}">Billing Address</a></li>
                <li><a href="{{ route('shipping.address.book') }}" class="{{(request()->route()->getName()=='shipping.address.book')? 'active':''}}">Shipping Address</a></li>
                <li><a href="{{ route('pamentOption') }}" class="{{(request()->route()->getName()=='pamentOption')? 'active':''}}">Payment Option</a></li>
                <li><a href="{{ route('voucher') }}" class="{{(request()->route()->getName()=='voucher')? 'active':''}}">Vouchers</a></li>
            </ul>
        </li>
        <li class="{{(request()->route()->getName()=='Corder' || request()->route()->getName()=='completed' || request()->route()->getName()=='return' || request()->route()->getName()=='cancel' ) ? 'visible_me':''}}"><a href="#"><i class="lab la-first-order-alt"></i>Order</a>
            <ul class="sidebar_sub_Menu">
                <li><a href="{{ route('Corder') }}" class="{{(request()->route()->getName()=='Corder')? 'active':''}}">All Order</a></li>
                <li><a href="{{ route('completed') }}" class="{{(request()->route()->getName()=='completed')? 'active':''}}">Completed</a></li>
                <li><a href="{{ route('return') }}" class="{{(request()->route()->getName()=='return')? 'active':''}}">Returning Product</a></li>
                <li><a href="{{ route('cancel') }}" class="{{(request()->route()->getName()=='cancel')? 'active':''}}">Cancelled Product</a></li>
            </ul>
        </li>
        <li><a href="{{ route('Creview') }}"><i class="lar la-eye"></i>Reviews</a></li>
        <li><a href="{{ route('Cwishlist') }}"><i class="lar la-heart"></i>Wishlist</a></li>

        <li><a href="{{ route('Clogout') }}"><i class="las la-sign-out-alt"></i>logout</a></li> 

    </ul>
</div>
