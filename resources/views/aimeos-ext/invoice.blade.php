<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <style type="text/css">
        @font-face {
            /*font-family: 'Raleway-Light';*/
            /*src: url('/storage/fonts/Raleway/Raleway-Light.ttf') format("truetype");*/
        }

        body {
            padding: 40px 80px;
            font-family: DejaVu Sans, sans-serif;
        }

        .col-md-9 {
            width: 60%;
            float: left;
            padding: 20px 0;
        }

        .col-md-3 {
            width: 40%;
            float: left;
            padding: 10px 0;
        }

        .grey td {
            width: 15%;
        }

        hr {
            clear: both;
            border-color: #d1d5d6;
        }

        .row {
            clear: both;
        }

        .center {
            text-align: center;
        }

        td {
            padding: 6px;
        }

        .full-width {
            width: 100%;
        }

        tr.border-bottom td {
            border-bottom: 1pt solid #d1d5d6;
        }

        .pad-100 {
            padding: 40px;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .comp-logo img {
            width: 200px;
        }

        body .row .col-md-9.comp-logo {
            padding-top: 0px;
        }

        .return-policy p {
            font-size: 12px;
        }

        .policy-rtl {
            direction: rtl;
        }
    </style>
</head>

<body>
<div class="row">
    <?php $currency = 'Qar' ?>
    <div class="col-md-9 comp-logo">
        <img src="{{ asset('icons/header-logoen.png') }}" alt="logo">
    </div>
    <div class="col-md-3">

    </div>
</div>
<hr>
<?php
app()->setLocale($lang);
$fname = 'order.base.address.firstname';
$lname = 'order.base.address.lastname';
$address1 = 'order.base.address.address1';
$address2 = 'order.base.address.address2';
$address3 = 'order.base.address.address3';
$city = 'order.base.address.city';
$state = 'order.base.address.state';
$countryid = 'order.base.address.countryid';
$postal = 'order.base.address.postal';
$orderid = 'order.base.id';
$datepayment = 'order.base.mtime';
$prodname = 'order.base.product.name';
$qty = 'order.base.product.quantity';
$price = 'order.base.product.price';
$costs = 'order.base.product.costs';
$baseprice = 'order.base.price';
$basecosts = 'order.base.costs';
$productsiteid = 'order.base.product.siteid';
$prodsku = 'order.base.product.prodcode';
$addresstitle = 'order.base.address.title';
$status = 'order.base.product.status';

$addr1 = $address->$address1;
$addArr = explode(',', $addr1);
$count = count($addArr);
if ($count == 1) {
    $area = $address->$addresstitle;
    $street = $addArr[0];
} elseif ($count == 2) {
    $area = $addArr[0];
    $street = $addArr[1];
}
?>
<div class="row">
    <div class="col-md-9">
        <b>BILL TO :</b><br><br>
        @if(($address->$fname) OR ($address->$lname))
            <b>Name:</b>{{ ($address->$fname) }} {{ $address->$lname }}<br>
        @endif
        <b>Building No:</b> {{ $address->$address2 }}<br>
        <b>Area:</b> {{ $area }}<br>
        {{-- {{ $address->$address1 }} --}}
        <b>Street:</b> {{ $street }}<br>
        {{-- {{ $address->$address3 }}<br> --}}
        {{-- <b>City:</b>{{ $address->$city }}<br> --}}
        <b>State:</b>{{ $address->$state }}<br>
        {{-- <b>Country</b>{{ $address->$countryid }}<br> --}}
        {{ $address->$postal }}
    </div>
    <div class="col-md-3">
        <b>INVOICE</b><br><br>
        <table cellspacing="0" class="full-width">
            <tr>
                <td><b>ORDER #</b></td>
                <td>{{ $base->$orderid }}</td>
            </tr>
            <tr>
                <td><b>INVOICE DATE</b></td>
                <td>{{ $base->$datepayment}}</td>
            </tr>
            <tr>
                <td><b>Payment Method</b></td>
                <td>{{ $paymentType }}</td>
            </tr>
            <tr>
                <td><b>Mobile</b></td>
                <td>{{ $mobile }}</td>
            </tr>
            <tr class="grey">
                <td style="color: blue"><b>AMOUNT</b></td>
                @if($checkParentSiteOrnot)
                    <td style="color: blue"><b>{{ $currency }} {{ $totalCost }}</b></td>
                @else
                    <td style="color: blue"><b>{{ $currency }} {{ $total }}</b></td>
                @endif
            </tr>
        </table>
    </div>
</div>

<hr>


<div class="row">
    <table cellspacing="0" class="full-width">
        <tr class="grey">
            <td><b>Item</b></td>
            <td><b>SKU</b></td>
            @if(count($models) > 0)
                <td><b>Model</b></td>
            @endif
            <td><b>Qty</b></td>
            <td><b>Price</b></td>
            <td><b>Sum</b></td>
        </tr>
        @if($checkParentSiteOrnot)
            @foreach($prods as $key => $prod)
                @if($prod->$status != 0)
                    <tr class="border-bottom">
                        <?php
                        $code = '';
                        $value = '';
                        if (count($attr) > 0) {
                            if (isset($attr[$key])) {
                                if (count($attr[$key]) > 0) {
                                    $code = $attr[$key]['code'];
                                    $value = $attr[$key]['value'];
                                }
                            }
                        }
                        ?>
                        <td>{{ $prod->$prodname }} <br> {{ $code }} {{ $value }}</td>
                        <td>{{ $prod->$prodsku }}</td>
                        @if(count($models) > 0)
                            <td>{{ isset($models[$key]) ? $models[$key] : ''}}</td>
                        @endif
                        <td>{{ $prod->$qty }}</td>
                        <td>{{ $currency }}
                            {{ $prod->$price }} + {{ $prod->$costs }}</td>
                        <td>{{ $currency }}
                            {{ $prod->$price * $prod->$qty }} + {{ $prod->$costs * $prod->$qty }} </td>
                    </tr>
                @endif
            @endforeach
        @else
            @foreach($prods as $key => $prod)
                @if($prod->$productsiteid == $siteId)
                    @if($prod->$status != 0)
                        <tr class="border-bottom">
                            <td>{{ $prod->$prodname }}</td>
                            <td>{{ $prod->$prodsku }}</td>
                            @if(count($models) > 0)
                                <td>{{ isset($models[$key]) ? $models[$key] : ''}}</td>
                            @endif
                            <td>{{ $prod->$qty }}</td>
                            <td>{{ $currency }}
                                {{ $prod->$price }} + {{ $prod->$costs }}</td>
                            <td>{{ $currency }}
                                {{ $prod->$price * $prod->$qty }} + {{ $prod->$costs * $prod->$qty }} </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        @endif
    </table>
</div>

<div class="row">
    <div class="col-md-9">
        {{-- <b>NOTES/MEMO</b><br>
        Free Shipping with 30 days money back gurantee. --}}
    </div>
    <div class="col-md-3">
        <table cellspacing="0" class="full-width">
            <tr>
                <td><b>SUB TOTAL</b></td>
                @if($checkParentSiteOrnot)
                    <td>{{ $currency }} {{  $base->$baseprice }}</td>
                @else
                    <td>{{ $currency }} {{  $subTotal }}</td>
                @endif
            </tr>
            <tr>
                <td><b>COSTS</b></td>
                @if($checkParentSiteOrnot)
                    <td>{{ $currency }} {{ $base->$basecosts }}</td>
                @else
                    <td>{{ $currency }} {{ $totalCost }}</td>
                @endif
            </tr>
            <tr class="grey">
                <td style="color: blue"><b>TOTAL</b></td>
                @if($checkParentSiteOrnot)
                    <td style="color: blue"><b>{{ $currency }}  {{ $totalCost }}</b></td>
                @else
                    <td style="color: blue"><b>{{ $currency }}  {{ $total }}</b></td>
                @endif
            </tr>


        </table>
    </div>
</div>
<hr>

<div class="return-policy {{$lang == 'ar' ? ' policy-rtl' : ''}}">
    <h4>@lang('messages.return_policy_title')</h4>
    <p>@lang('messages.seven_days_return')</p>
    <p>@lang('messages.product_must_include')</p>
    <p>@lang('messages.product_must_returned')</p>
    <p>@lang('messages.if_product_returned')</p>
</div>


{{--<div class="row center pad-100">--}}
{{--    --}}{{-- <img src="{{ asset('preview/icons/512х512.png') }}" alt="logo"> --}}
{{--</div>--}}
<hr>
<div class="row">

    <div class="col-md-3 left">
        SYAANH INC<br>
    </div>

    {{-- <div class="col-md-3 center">
        Text text text<br>
        text text text
    </div>

    <div class="col-md-3 right">
        Text text<br>
        text text text text<br>
        text text text
    </div> --}}

</div>
</body>
</html>
