<body>
    <form action="https://esewa.com.np/epay/main" method="POST" id="esewa-form">
        <input value="{{$tAmt}}" name="tAmt" type="hidden">
        <input value="{{$amt}}" name="amt" type="hidden">
        <input value="{{$txAmt}}" name="txAmt" type="hidden">
        <input value="{{$psc}}" name="psc" type="hidden">
        <input value="{{$pdc}}" name="pdc" type="hidden">
        <input value="NP-ES-ULTRASOFT" name="scd" type="hidden">
        <input value="{{$pid}}" name="pid" type="hidden">
        <input value="{{$su.'?q=su'}}" type="hidden" name="su">
        <input value="{{$fu.'?q=fu'}}" type="hidden" name="fu">
    </form>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#esewa-form').submit();
        });
    </script>
</body>