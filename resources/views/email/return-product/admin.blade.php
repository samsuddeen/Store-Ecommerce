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
    <h4>Returning Product (E-mail From Customer)</h4>    
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

    <h5>Customer Info </h5>
    <table class="table">
        <tr>            
            <th>Name</th>
            <td>{{$customer->name}}</td>
        </tr>    
        <tr>
            <th>Email</th>
            <td>{{$customer->email}}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{$customer->phone}}</td>
        </tr>
        <tr>
            <th>Address</th>   
            <td>{{$customer->address}} </td>            
        </tr>
        <tr>
            <th>District</th>
            <td>{{$customer->district}}</td>                    
        </tr>
        <tr>
            <th>Area</th>
            <td>{{$customer->area}}</td> 
        </tr>
        <tr>        
            <th>Zip Code</th>        
            <td>{{$customer->zip}}</td>
        </tr>
    </table>   
</body>
</html>