<head>
    <meta charset="UTF-8">
    <title>{{ config('motor-backend-project.name') }} Backend - @yield('htmlheader_title', 'Your title here') </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ mix('/css/partymeister-accounting-pos.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        body {
            padding: 20px;
        }
        table {
            background-color: transparent !important;
        }
        .table tbody tr:hover td, .table tbody tr:hover th {
            background-color: transparent;
        }
        div.item {
            text-align: center;
            width: 250px;
            float: left;
            margin-right: 5px;
            min-height: 160px;
        }
        div.item button.btn-lg {
            font-weight: bold;
            width: 48%;
            height: 75px;
            font-size: 24px;
        }
        div.sales button.btn-success {
            width: 100%;
            height: 100px;
            font-size: 40px;
            font-weight: bold;
        }
        .beverage td {
            font-size: 24px;
            line-height: 30px;
            vertical-align: middle;
        }
        h2 {
            font-size: 22px;
            margin: 0;
        }
        tr.items td {
            border-top: 1px solid #888 !important;
        }
        #draggable div.item {
            float: none;
            width: 100%;
            min-height: 0;
        }
        #draggable .buttons {
            display: none;
        }
        button.delete-config-item {
            float: left;
            position: relative;
            top: -45px;
            left: 210px;
            font-size: 10px;
        }
        #draggable button.delete-config-item {
            display: none;
        }
        .separator {
            background: none;
        }
    </style>
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
