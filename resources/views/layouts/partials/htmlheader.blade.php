<head>
    <meta charset="UTF-8">
    <title>{{ config('motor-backend-project.name') }} Backend - @yield('htmlheader_title', 'Your title here') </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ mix('/css/partymeister-accounting-pos.css') }}" rel="stylesheet" type="text/css"/>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
    </script>
</head>
