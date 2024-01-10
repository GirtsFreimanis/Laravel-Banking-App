<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        html, body {
            padding: 0;
            margin: 0;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
<div class="container" style="padding-top:15%">
    <div class="row pb-3">
        <h1 class="text-center">Welcome to Banking App</h1>
    </div>
    <div class="row">
        <div class="col text-center">
            <a href="{{ route('login') }}" class="text-decoration-none">
                <button class="btn btn-primary" style="text-decoration: none">Log-in</button>
            </a>

            <a href="{{ route('register') }}" class="text-decoration-none">
                <button class="btn btn-secondary">Register</button>
            </a>

        </div>
    </div>

</div>

</body>
</html>
