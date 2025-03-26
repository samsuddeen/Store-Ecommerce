
<form id="paymentForm" action="https://uat.esewa.com.np/epay/main" method="POST">
    <input value="{{$totalAmount}}" name="tAmt" type="hidden">
    <input value="{{$amount}}" name="amt" type="hidden">
    <input value="{{$coupon_code}}" name="amt" type="hidden">
    <input value="{{$taxAmount}}" name="txAmt" type="hidden">
    <input value="{{$serviceCharge}}" name="psc" type="hidden">
    <input value="{{$shippingCharge}}" name="pdc" type="hidden">
    <input value="EPAYTEST" name="scd" type="hidden">
    <input value="{{$pid}}" name="pid" type="hidden">
    <input value="{{route('esewa.success')}}?q=su" type="hidden" name="su">
    <input value="{{route('esewa.failure')}}?q=fu" type="hidden" name="fu">
</form>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        document.getElementById('paymentForm').submit();
    });
</script>
