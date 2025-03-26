@foreach ($data as $value)
<a href="{{ $status=='cat' ? route('category.show',$value->slug) : route('brand-front.index',$value->slug)}}">
    <div class="bulk-section-col carousel-img">
        <img src="{{$value->logo}}" alt="">
        <p class="p-2 pt-0 pb-0"> {{ $value->name }}</p>
    </div>
</a>
@endforeach
