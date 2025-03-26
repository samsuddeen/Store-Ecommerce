
<!DOCTYPE html>
<html>
<head>
    <title>New Contact Message</title>
</head>
<body>
    <p>You Have New Product Inquiry Mail .</p><br>
    <p>
        <strong>Name: </strong> {{@$data->full_name}}
        <strong>Email: </strong> {{@$data->email}}
        <strong>Phone: </strong> {{@$data->phone}}
        <strong>Message: </strong> {{@$data->message}}
        <strong>Product: </strong> <a href="{{route('product.details',@$data->product->slug)}}">{{@$data->product->name}}</a>
    </p>
</body>
</html>
