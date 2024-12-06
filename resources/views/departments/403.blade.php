<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFI-FR</title>

    <style>
        @import url("https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i");

        *,
        *:before,
        *:after {
            box-sizing: border-box;
        }

        html {
            height: 100%;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #fefefe;
            height: 100%;
            padding: 10px;
        }

        a {
            color: #ADD8E6;
            text-decoration: none;
        }

        a:hover {
            color: #FFFFFF !important;
            text-decoration: none;
        }

        .text-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .title {
            font-size: 4em;
            font-weight: 700;
            color: #ADD8E6;
        }

        .subtitle {
            font-size: 30px;
            font-weight: 700;
            color: #666666;
        }

        .isi {
            font-size: 18px;
            text-align: center;
            margin: 30px;
            padding: 20px;
            color: #000;
        }

        .buttons {
            margin: 30px;
            font-weight: 700;
            border: 2px solid #ADD8E6;
            text-decoration: none;
            padding: 15px;
            text-transform: uppercase;
            color: #ADD8E6;
            border-radius: 26px;
            transition: all 0.2s ease-in-out;
            display: inline-block;
        }

        .buttons:hover {
            background-color: #ADD8E6;
            color: #fff;
            transition: all 0.2s ease-in-out;
        }

        .buttons:hover .button {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="text-wrapper">
        <div class="title" data-content="404">
            403 - ACCESS DENIED
        </div>

        <div class="subtitle">
            Oops, You don't have permission to access this page.
        </div>
        <div class="isi">
            If you think this is a mistake, please contact Admin for support.
        </div>

        <div class="buttons">
            <a class="button" href="{{ $backUrl ?? route('department.home') }}">Go to dashboard</a>
        </div>
    </div>
</body>

</html>
