<section class="banner">
    <img src="{{  $seller_setting->banner_image ??  asset('frontend/images/login.jpg') }}" alt="images">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                {{-- <li class="breadcrumb-item active" aria-current="page">{{ @$seller->name ?? 'Glass Pipe'}}</li> --}}
            </ol>
        </nav>
    </div>
</section>