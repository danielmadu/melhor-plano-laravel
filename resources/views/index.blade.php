<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <title>Teste Melhor Plano</title>
</head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div id="app"></div>
                </div>
            </div>
        </div>
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>