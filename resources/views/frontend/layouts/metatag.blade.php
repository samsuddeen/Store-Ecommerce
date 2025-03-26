{{-- @dd($meta) --}}
<meta name="title" content="{{ @$meta['meta_title'] ?? @$seo->meta_title }}">
<meta name="description" content="{{ strip_tags(@$meta['meta_description'] ?? @$seo->meta_description) }}">
<meta name="keywords" content="{{ @$meta['meta_keywords'] ?? @$seo->meta_keywords }}">
<meta property="og:title" content="{{ @$meta['meta_title'] ?? @$seo->meta_title }}">
<meta property="og:image" content="{{ @$meta['og_image'] ?? (@$seo->og_image ?? @$meta_setting['logo']) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="675">
<meta property="og:image:alt" content="{{ @$meta['meta_title'] ?? @$seo->meta_title }}">
<meta property="og:description" content="{{ strip_tags(@$meta['meta_description'] ?? @$seo->meta_description) }}">
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:site_name" content="{{ @$meta_setting['name'] }}" />
{{-- <meta property="og:locale" content="ne_NP" /> --}}
<meta name="twitter:card" content="{{ @$meta['og_image'] ?? (@$seo->og_image ?? @$meta_setting['logo']) }}">
<meta name="twitter:site" content="{{ @$meta_setting['twitter'] }}" />
<meta name="allow-search" content="yes" />
<meta name="author" content="{{ @$meta_setting['name'] }}" />
<meta name="copyright" content="{{ date('Y') }} {{ @$meta_setting['name'] }}" />
<meta name="coverage" content="Worldwide" />
<meta name="identifier" content="{{ url()->current() }}" />
<meta name="language" content="{{ app()->getLocale() }}" />
<meta name="Robots" content="home,FOLLOW" />
<link rel="canonical" href="{{ url()->current() }}" />
<meta name="Googlebot" content="home, follow" />
<link rel="next" href="{{ route('index') }}" />
<meta property="fb:admins" content="" />
<meta property="fb:page_id" content="104637888619621" />
<meta property="fb:pages" content="104637888619621" />
<meta property="og:type" content="article" />
<meta property="ia:markup_url" content="{{ url()->current() }}">
<meta property="ia:rules_url" content="{{ url()->current() }}">
<meta property="fb:app_id" content="296672421803651" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="{{ route('index') }}" />
<meta name="twitter:title" content="{{ substr(@$meta['meta_title'] ?? @$seo->meta_title, 0, 70) }}" />
<meta name="twitter:description" content="{{ substr(strip_tags(@$meta['meta_description']), 0, 120) }}" />
<meta name="twitter:image" content=" {{ @$meta['og_image'] ?? @$meta_setting['logo'] }}" />
<meta name="google-site-verification" content="xLTE-QX5uTNDPc6lsm6-5Nx5P5VgF28sQeJg5vyCg2o" />
