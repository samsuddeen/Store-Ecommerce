<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Home</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item text-capitalize">
                            <a href="{{ route('dashboard') }}">Home</a>
                        </li>
                        @foreach ($path as $item)
                        
                            @if (is_numeric($item))
                                @continue;
                            @endif
                            @php
                                $url = $url . $item . '/';
                            @endphp
                            {{-- @dd($url) --}}
                            @if($url=='admin/slider/')
                            @php
                                 $url=$url.'index';
                            @endphp
                            @elseif($url=='admin/view-order/')
                            @php
                                $url=route('order.index');
                            @endphp
                            @endif
                            <li class="breadcrumb-item text-capitalize">
                                <a href="{{ $loop->last ? '#' : url($url) }}">{{ $item }}</a>
                            </li>
                        @endforeach

                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
