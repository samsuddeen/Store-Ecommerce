    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    
    <body>
        <div class="row mt-5">
            <div class="col-md-2">

            </div>
            <div class="col-md-8 div.col-sm-12">

                <div class="content-body">
                    <div class="card-view">
                        Please Contact us to activate your account You Are not active user.
                    </div>
                    <table class="table">
                        @foreach ($footer_contacts as $item)
                            <th>
                            <td>
                                {{ $item->contact_no }}
                            </td>
                            </th>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </body>

    </html>
