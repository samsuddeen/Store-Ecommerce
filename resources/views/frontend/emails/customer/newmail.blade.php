<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Mail</title>
    <link rel="stylesheet" href="{{ asset('frontend/fonts/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/fonts/line-awesome-1.3.0/css/line-awesome.min.css') }}">
    <style>
        body {
            background: #f1f1f1;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            color: #525252;
        }

        .similar-table {
            background: #fff;
            padding: 30px 25px;
            border: 2px solid #e3e3e3;
        }

        .table-first {
            text-align: center;
        }

        .table-first table {
            width: 100%;
        }

        .table-first img {
            height: 100px;
            width: auto;
            margin-bottom: 30px;
        }

        .table-first a {
            font-size: 25px;
            font-weight: 600;
            text-decoration: none;
            color: #39afb2;
            display: block;
        }

        .table-first b {
            font-size: 30px;
            display: block;
            margin-top: 40px;
            color: #4c4c4c;
        }

        .table-first p {
            text-align: left;
            font-size: 18px;
            line-height: 1.5;
            color: #525252;
            margin-bottom: 30px;
        }

        .btns-track a {
            background: #39afb2;
            color: #fff;
            text-transform: uppercase;
            border-radius: 5px;
            padding: 15px 30px;
            display: inline-block;
            font-weight: bold;
            font-size: 20px;
        }

        .similar-table+.similar-table {
            border-top: 4px solid #e3e3e3;
        }

        .similar-table h3 {
            font-size: 25px;
            margin-top: 0;
            margin-bottom: 20px;
            color: #4c4c4c;
        }

        .table-second td {
            padding-top: 10px;
        }

        .table-third img {
            height: 200px;
            width: auto;
        }

        .table-fifth ul {
            padding: 0;
            margin: 0;
        }

        .table-fifth p {
            margin-bottom: 0;
            line-height: 1.5;
            margin-top: 10px;
        }

        .table-fifth ul li {
            list-style: none;
        }

        .table-fifth ul li b {
            font-size: 20px;
        }

        .table-fifth ul li+li {
            margin-top: 30px;
        }

        a {
            text-decoration: none;
            color: #39afb2;
            transition: ease .3s;
        }

        a:hover {
            text-decoration: underline;
        }

        .footer-table {
            margin-top: 30px;
            text-align: center;
        }

        .download img {
            height: 35px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .footer-logo img {
            height: 100px;
            width: auto;
            margin-top: 20px;
            margin-bottom: 25px;
        }

        .help-text a {
            display: inline-block;
            color: #4c4c4c;
            transition: ease-in-out .3s;
        }

        .help-text a+a {
            border-left: 2px solid #d7d7d7;
            padding-left: 10px;
            margin-left: 10px;
        }

        .help-text a:hover {
            color:#39afb2;
        }

        .download a {
            color: #4c4c4c;
        }

        .social-media a {
            display: inline-block;
        }

        .social-media img {
            height: 27px;
            width: auto;
        }

        .social-media a+a {
            margin-left: 2px;
        }
    </style>
</head>

<body>
   
    <div class="similar-table table-first">
        <table>
            <tbody>
                <tr>
                    <td colspan="3"><img src="{{$setting[0]->value}}" alt="images">
                    </td>
                </tr>
                {{-- <tr>
                    <td><a href="#">Software</a></td>
                    <td><a href="#">Accessories</a></td>
                    <td><a href="#">Hardware</a></td>
                </tr> --}}

                
                <tr>
                    <td colspan="3"><b>Your Package is {{ucfirst(@$msg)}}!</b></td>
                </tr>
                
                <tr>
                    <td colspan="3">
                        <p>
                            
                            @include('admin.message-setup.test')
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="btns-track"><a href="{{route('order.productDetails',@$order->id)}}">Track My Order</a></td>
                </tr>
                @if(@$order->status == '5')
                <tr>
                    <td colspan="3" class="btns-track"><a href="{{route('order.productDetails',@$order->id)}}">Take your time to share your experience with us</a></td>

                </tr>
                 @endif
            </tbody>
        </table>
    </div>
    <div class="similar-table table-second">
        <table>
            <tbody>
                <tr>
                    <td colspan="2">
                        <h3>Delivery Details</h3>
                    </td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td>{{@$order->name}}</td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>{{@$order->province}},{{@$order->district}},{{@$order->area}},{{@$order->additional_address}}
                    </td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td>{{@$order->phone}}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{@$order->email}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="similar-table table-third">
        <table>
            <tbody>
                <tr>
                    <td colspan="2">
                        <h3>Order Details</h3>
                    </td>
                </tr>
                @foreach($order->orderAssets as $key=>$product)
                
                <tr>
                    <td colspan="2" style="line-height:1.7;">
                        {{-- ITEM {{$key+1}}<br> --}}
                        {{-- Sold by {{echo getSeller($product)}}<br> --}}
                        {{-- Estimated Delivery between 25 January - 26 January --}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="{{@$product->image}}"
                            alt="images">
                    </td>
                    <td style="line-height:1.7;padding-left:40px;">
                        {{@$product->product_name}}<br><br>
                        Rs. {{@$product->sub_total_price}}<br>
                        X{{@$product->qty}}
                    </td>
                </tr>
                @endforeach
                
            </tbody>
        </table>
    </div>
    <div class="similar-table table-fourth">
        <table>
            <tbody>
                <tr>
                    <td>Order Total:</td>
                    <td style="text-align:right;">Rs. {{@$order->total_price}}</td>
                </tr>
                <tr>
                    <td>Delivery Fee:</td>
                    <td style="text-align:right;">Rs. {{@$order->shipping_charge}}</td>
                </tr>
                <tr>
                    <td>Total Discount:</td>
                    <td style="text-align:right;">Rs. {{@$order->total_discount}}</td>
                </tr>
                {{-- <tr>
                    <td style="padding-bottom:30px;font-weight:bold;font-size:20px;">Total Payment (VAT Incl):</td>
                    <td style="padding-bottom:30px;font-weight:bold;font-size:20px;color:#e62e04;text-align:right;">Rs. 1250</td>
                </tr> --}}
                <tr>
                    <td>Delivery Method:</td>
                    <td style="text-align:right;">Standard</td>
                </tr>
                <tr>
                    <td>Paid By:</td>
                    <td style="text-align:right;">{{@$order->payment_with}}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:30px;line-height:1.5;">
                        <b>Note:</b>
                        {!! @$email_message->note !!}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="similar-table table-fifth">
        
        <table>
            <tbody>
                <tr>
                    {!! @$email_message->message !!}
                </tr>
                {{-- <tr>
                    <td>
                        <h3>Need Help?</h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li>
                                <b>When will receive my order?</b>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget
                                    dolor.
                                    Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes
                                </p>
                            </li>
                            <li>
                                <b>How can I my order?</b>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget
                                    dolor.
                                    Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes
                                </p>
                            </li>
                            <li>
                                <b>Will I be contacted before the delivery of my package?</b>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget
                                    dolor.
                                    Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes
                                </p>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="btns-track"><a href="{{route('general','customer-care')}}"
                            style="display: block;text-align:center;margin-top:30px;">Still have questions? Contact
                            us</a></td>
                </tr> --}}
                <tr>
                    {!! @$email_message->footer_message !!}
                    {{-- <td style="padding-top:30px;color:#e62e04;">
                        <b>Notes:</b>
                        <p style="color: #525252;margin-top:20px;">For more information, visit our <a
                                href="{{route('general','customer-care')}}">Help Center</a> or
                            check our <a href="{{route('general','return-policy')}}">Return Policy</a>.</p>
                        <p style="color: #525252;margin-top:20px;">
                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                            Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur
                            ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla
                            consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget,
                            arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.
                        </p>
                    </td> --}}
                </tr>
            </tbody>
        </table>
    </div>
    <div class="footer-table">
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td class="social-media" style="width: 33.3333%;">
                        @foreach ($social as $social_link)
                                {{-- @dd($social_link) --}}
                                @if ($social_link->title == 'facebook' || $social_link->title == 'Facebook' || $social_link->title == 'FACEBOOK')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="facebook" target="_blank"
                                            title="{{ $social_link->title }}"><i class="lab la-facebook-f"></i></a>
                                    </li>
                                @elseif ($social_link->title == 'instagram' || $social_link->title == 'Instagram' || $social_link->title == 'INSTAGRAM')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="facebook" target="_blank"
                                            title="{{ $social_link->title }}"><i class="lab la-instagram"></i></a>
                                    </li>
                                @elseif ($social_link->title == 'twitter' || $social_link->title == 'Twitter' || $social_link->title == 'TWITTER')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="twitter" target="_blank"
                                            title="{{ $social_link->title }}"><i class="lab la-twitter"></i></a>
                                    </li>
                                @elseif ($social_link->title == 'youtube' || $social_link->title == 'Youtube' || $social_link->title == 'YOUTUBE')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="youtube" target="_blank"
                                            title="{{ $social_link->title }}"><i class="lab la-youtube"></i></a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $social_link->url }}" class="youtube" target="_blank"
                                            title="{{ $social_link->title }}">
                                            <img src="{{ $meta_setting['logo'] }}" alt="Logo"
                                                style="width:20px; height:20px;">
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                    </td>
                    <td class="help-text" style="width: 33.3333%;">
                        <a href="{{route('general','customer-care')}}">Help Center</a>
                        <a href="{{route('general','customer-care')}}">Contact Us</a>
                    </td>
                    <td class="download" style="width: 33.3333%;">
                        <a href="#">
                            <img src="{{$setting[0]->value}}" alt="images">
                            Download Glass Pipe App
                        </a>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="footer-logo">
                        <img src="{{$setting[0]->value}}" alt="images">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="help-text">
                        @foreach ($menu as $menu_data)
                            <a href="{{route('general',$menu_data->slug)}}">{{$menu_data->name}}</a>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p>
                            This is an automatically generated e-mail from our subscription list. Please don not reply
                            to this e-mail.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
