<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>
<body>
    <style>
        .nav-link:hover {
            color: white !important;
        }
    </style>

    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <a class="navbar-brand" style="margin-left: 20px;" href="#"><strong>SARABUN</strong> EASARABUN DEMO</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('ReceivedBook')}}">รับหนังสือ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('bookfile')}}">แฟ้มบนโต๊ะ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('FollowBook')}}">ติดตามหนังสือ</a>
                </li>
            </ul>
        </div>
    </nav>

    <br>

    @yield('layout')

    <br>

</body>
</html>
