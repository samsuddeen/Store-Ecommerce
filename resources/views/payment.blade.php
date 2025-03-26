<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- saved from url=(0035)http://127.0.0.1:8000/return/index.html -->
<html class="supernova">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


    <meta property="og:title" content="Glass Pipe">
    <meta property="og:description" content="Please click the link to complete this form.">
    <link rel="shortcut icon" href="http://himalayanbank.com/themes/himalayan/assets/ico/hbl-icon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="HandheldFriendly" content="true">
    <title>Glass Pipe</title>
    <link href="{{ asset('hbl/hbl.css') }}" rel="stylesheet" type="text/css">
    <link type="text/css" rel="stylesheet" href="{{ asset('hbl/hbl_nova.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('hbl/theme.css') }}">
    <script type="text/Javascript"></script>
    <style type="text/css">
        .form-label-left {
            width: 150px;
        }

        .form-line {
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .form-label-right {
            width: 150px;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .form-all {
            margin: 0px auto;
            padding-top: 0px;
            width: 80%;
            /*removed important */
            color: rgb(71, 71, 71) !important;
            font-family: 'PT Sans Narrow';
            font-size: 14px;
        }

        .form-radio-item label,
        .form-checkbox-item label,
        .form-grading-label,
        .form-header {
            color: false;
        }

        .vacancy_content {
            padding: 20px;
            /*remove 50px*/
        }

        .vacancy_bodycontent {
            font-size: 16px;
            padding: 5px;
        }

        .novacancy_footer {
            /* width: 25%; */
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .yesvacancy_footer {
            /* width: 25%; */
            margin-top: 10px;
        }

        /*   #header-banner {
        height: 45px!important;
        width: 300px!important;
    }
    .header-banner {
        background-image: url("../image/hbl_logo.png");
        background-repeat: no-repeat;
        background-size: 300px 45px!important;
        float: right;
        position: relative;
        margin-top: 35px;
        margin-right:60px;
    }
    .form-header-group {
        background-image: url("http://cc.hbl.com.np/hblacs2//assets/image/hbl_office2.jpg")!important;
        height: 250px!important;
        width: 100%!important;
        background-size: 100% 535px!important;
    }*/


    .vacancy_content  {
        background-color: #fff;
        padding: 35px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.08);
        border-radius:4px;
        height: 250px;
    }

    .d-flex {
        display: flex;
        gap: 10px;
    }

    .justify-content-between {
        justify-content: space-between;
    } 
    
    li {
        list-style: none;
    }
    .return_back {
        color: #000;
        font-weight: 600;
    }
    .round-btns .btns {
        border: none;
        background: #0a472e;
        padding: 4px 15px 6px;
        color: #fff;
        border-radius: 50px;
        transition: ease-in-out 0.3s;
        text-decoration: none;
    }
    .form-control  {
        width: 95%;
        padding: .375rem .75rem;
        font-size: 1rem !important;
        font-weight: 400;
        line-height: 1.5;
        color: #212529 !important;
        background-color: #fff !important;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .justify-content-center {
        justify-content: center
    }
    .vacancy_content h2 {
        font-weight: bold !important
    }
    </style>

    <style type="text/css" id="form-designer-style">
        /* Injected CSS Code */
        @import "https://fonts.googleapis.com/css?family=PT Sans Narrow:light,lightitalic,normal,italic,bold,bolditalic";
    </style>

    <link type="text/css" rel="stylesheet" href="{{asset('hbl/hbl(1).css')}}">
</head>

<body>

    @extends('frontend.layouts.app')
@section('title', @$content->name)
@section('content')

  

    <span class="text-danger">{{session()->has('success') ? session()->get('success') : ''}}</span>
    <form class="jotform-form" action="{{ route('payment.store') }}" method="post" enctype="multipart/form-data"
        name="form_payment" id="" accept-charset="utf-8">
        @csrf
        <input type="hidden" name="formID" value="92921030145569">
        @isset($directCheckout)
        <input type="hidden" name="directCheck" value="1">
        @else
        <input type="hidden" name="directCheck" value="0">
        @endisset
        <div class="form-all">
            <ul class="form-section page-section">
                <!-- <li id="cid_1" class="form-input-wide" data-type="control_head">
                    <div class="form-header-group" style="background-image:url('{{@$setting[0]->value}}');background-size: 650px;    padding-bottom: 0;height: 136px;
                        background-repeat: no-repeat;">
                        <a href="http://localhost/hblgateway">
                            <div class="header-banner">
                                <div id="header-banner" class="form-subHeader">
                                </div>
                            </div>
                        </a>
                        <div class="header-text httal htvam">
                            <h2 id="header_1" class="form-header" data-component="header">
                                Glass Pipe
                            </h2>
                            <div id="subHeader_1" class="form-subHeader">
                            </div>
                        </div>
                    </div>
                </li> -->


                

                <div class="d-flex justify-content-center" style="margin-top: 2rem;  margin-bottom: 2rem;">
                    <li id="yes_vacancy">
                        <div class="form-sub-label-container">
                            <div class="vacancy_content">
                                <h2>Payment Details <?php echo isset($_GET['payment']) ? ($_GET['payment'] == 'success' ? ' - <font color="green">Payment Success</font>' : ' - <font color="red">Payment failed or canceled</font>') : ''; ?></h2>
                                <div id="vacancy_body">
                                    <div class="vacancy_bodycontent">
                                        @if(session()->has('success'))
                                        <div>
                                            <span>{{session()->get('success')}}</span>
                                        </div>
                                        @endif
                                        
                                        @if(session()->has('error'))
                                        <div>
                                            <span style="color: red">{{session()->get('error')}}</span>
                                        </div>
                                        @endif
                                        
                                        <div class="vacancy_desc">
                                            <span>
                                                <!-- <label class="form-sub-label" for="currency" id="sublabel_curr"
                                                    style="min-height:13px; color: #000; font-size: 16px; font-weight: 600;"> Currency </label> -->
                                                <select name="input_currency" id="input_currency" data-validation="required"
                                                    class="form-dropdown validate[required] form-control" style="width: initial; float: right;">
                                                    {{-- <option value="NPR">NPR</option> --}}
                                                    <option value="USD" selected>USD</option>
                                                </select>
                                            </span>
                                            <div>
                                                <span style="color: red">{{ $errors->first('input_currency') }}</span>
                                            </div>
                                        </div>

                                        <div class="vacancy_desc" style="margin-bottom: 1rem;">
                                                
                                                <span>
                                                    <label class="form-sub-label" for="input_amount" id="sublabel_amount"
                                                        style="min-height:13px; font-size: 16px; font-weight: 600;"> Amount </label>
                                                        <div style="margin-top: 1rem;">
                                                            <input type="number" id="input_amount" hidden name="input_amount" class="form-control"  value="{{@$fixed_price}}">
                                                            <input type="number" id="amount" name="amount" disabled class="form-textbox validate[required] form-control"  value="{{@$fixed_price}}">
                                                        </div>
                                                </span>
                                            <div>
                                                <span style="color: red">{{ $errors->first('input_amount') }}</span>
                                            </div>
                                        </div>
                                        <div class="vacancy_desc d-flex" style="align-items: center;">
                                            <div class="round-btns">
                                                <button type="submit" class="form-submit-button btns" data-component="button">
                                                    Checkout
                                                </button>
                                            </div>
                                            <a href="{{route('index')}}"  class="form-submit-button return_back">Return Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li id="yes_vacancy">
                        <div class="vacancy_content">
                            <div class="vacancy_bodycontent yesvacancy_footer">
                                <div class="contact-side-am">
                                    <h3>Contact Address</h3>

                                    <p><span style="font-size:16px"><span
                                                style="font-family:arial,helvetica,sans-serif"><span
                                                    style="color:#0099ff"><strong>{{@$setting[1]->value}}</strong></span></span></span></p>

                                    <p><span style="font-size:14px"><span
                                                style="font-family:arial,helvetica,sans-serif">{{@$setting[7]->value}}</span></span></p>

                                    <p><span style="font-size:14px"><span
                                                style="font-family:arial,helvetica,sans-serif">Tel: {{@$setting[2]->value}}</span></span></p>

                                    <p><span style="font-size:14px"><span
                                                style="font-family:arial,helvetica,sans-serif">Email :{{@$setting[3]->value}}&nbsp;</span></span></p>
                                </div>
                            </div>
                        </div>
                    </li>
                </div>

            </ul>
        </div>
        <input type="hidden" id="simple_spc" name="simple_spc" value="92921030145569">

        <div class="formFooter-heightMask">
        </div>

    </form>
@endsection

</body>

</html>
