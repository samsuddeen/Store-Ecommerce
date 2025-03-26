<div class="store-page-head">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="store-head-left">
                <div class="store-page-logo">
                    <img src="{{ $meta_setting['logo'] }}" alt="Logo">
                    <div class="store-page-logo-info">
                        <h3>{{$meta_setting['name'] ?? 'Glass Pipe'}}</h3>
                        <span>95% Positive Seller Ratings</span>
                    </div>
                </div>
                {{-- <div class="store-utilities">
                    <div class="store-chat">
                        <a href="#">
                            <i class="las la-comments"></i>
                            <span>Chat Now</span>
                        </a>
                    </div>
                    <div class="store-chat">
                        <a href="#">
                            <i class="las la-store-alt"></i>
                            <span>Follow Us</span>
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="store-head-right">
                <img src="{{ $seller_setting->logo ?? $meta_setting['logo'] }}" alt="images"
                    style="width: auto; height: 120px;">
            </div>
        </div>
    </div>
</div>
