<!doctype html>
<html lang="jp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Noto+Sans+JP:wght@400;500;700&display=swap"
        rel="stylesheet" />
    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>

<body>
    <noscript>
        <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled.
                Please enable it to continue.</strong>
    </noscript>

    <div id="app"></div>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}" defer></script>
</body>

</html>
