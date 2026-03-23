<head>
    <meta charset="UTF-8">
    <title>{{ config('motor-admin-project.name') }} Backend - @yield('htmlheader_title', 'Your title here') </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['packages/partymeister-accounting/resources/assets/sass/partymeister-accounting-pos.scss'])
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
    </script>
</head>
