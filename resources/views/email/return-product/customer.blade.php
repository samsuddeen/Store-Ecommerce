<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table, th, td {
            border: 1px solid;
        }        
    </style>
</head>
<body>
    <img src="{{$setting}}" alt="Company Logo" style="width: 100px;">
    <h4>Returning Product</h4>
    <h5>product Info</h5>    
    <table class="table">
        <thead>
            <th>Product Name</th>
            <th>Image</th>            
            <th>Action</th>
        </thead>
        <tr>
            <td>{{$product->name}}</td>
            <td>
                <img src="{{$product->images[0]->image}}" alt="Img" style="width:100px">
            </td>
            <td>
                <a href="{{url(env('APP_URL').'/product/'.$product->slug)}}">
                View More Info
                </a>
            </td>
        </tr>
    </table>
    <h5>We Will Soon Contact You After Verifying Our Returnable Policy.</h5>
</body>
</html>