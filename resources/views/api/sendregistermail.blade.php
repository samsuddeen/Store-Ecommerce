<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Mail</title>
</head>
<body>
    <h2>New Otp </h2>
    <strong>Dear, {{ ucfirst($data->name)}}</strong>
    <br>
    <p>
        Plz Use This Code To Verify Your Account ={{ $data->verify_otp}}
    </p>
</body>
</html>