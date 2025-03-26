
@extends('layouts.app')
@section('meta')
    {{-- @include('front.website.layouts.meta') --}}
@endsection
@section('content')
<section id="sigle_blog_header_wrapper">


    <div class="container">
        <div class="row">
        {{-- @foreach($datas as $item) --}}

            <div class="col-lg-6">
                <div class="signle_blog_description">

                    <h2>{{$footers->title}}</h2>

                </div>
            </div>
        </div>
    </div>

</section>
<section id="single_blog_content_fx">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <p>{!! $footers->description !!}</p>

                <h2>Common characteristics of promotional emails</h2>
                <p>If you want your business to flourish and your promotional email to work its magic on your target
                    customers, here are a few characteristics to look out for which will help you create an engaging and
                    successful promotional email campaign.</p>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="disqus_thread"></div>
                        <script>
                        (function() { 
                            var d = document,
                                s = d.createElement('script');
                            s.src = 'https://onetest-1.disqus.com/embed.js';
                            s.setAttribute('data-timestamp', +new Date());
                            (d.head || d.body).appendChild(s);
                        })();
                        </script>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="for_side_thumbnail">
                   <img src="{{asset('cover_image')}}/{{$footers->cover_image}}" alt=""style="width:100%;height:500px">
                </div>

                <div class="popular_category">

                </div>

            </div>
        </div>
    </div>
</div>
</div>
</section>
@endsection
