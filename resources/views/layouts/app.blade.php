<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Global Supply Chain Risk Intelligence</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body>

<div class="wrapper">

    @include('dashboard.sidebar')

    <div class="main">

        @include('dashboard.navbar')

        <div class="content">

            @yield('content')

        </div>

    </div>

</div>

</body>

</html>