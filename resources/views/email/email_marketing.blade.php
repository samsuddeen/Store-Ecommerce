<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>This would be a great fit for your sense</title>
    <style>
        table,
        th,
        td {
            border: 1px solid;
        }
    </style>
</head>

<body>
    {{-- <h4>Notification from Glass Pipe </h4> --}}
    
    <div>
        Dear Customer <br>
        <h5>{{$details['title']}}</h5>
        <p>{{$details['summary']}}</p> 
        <p>{!!$details['description']!!}</p>
        <p><a href="{{$details['url']}}">{{$details['url']}}</a></p>
        <p><img src="{{$details['image']}}" alt="" srcset=""></p>
       
    </div>
</body>

</html>
