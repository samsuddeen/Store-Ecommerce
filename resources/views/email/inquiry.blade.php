
<!DOCTYPE html>
<html>
<head>
    <title>New Contact Message</title>
</head>
<body>
    <p>You have received a new message from email : {{ $data['email'] }} .</p><br>
    <p>Regards,</p>
    <h3>{{ env("APP_NAME") }}.</h3>
</body>
</html>
