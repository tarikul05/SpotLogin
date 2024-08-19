<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de maintenance</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 d-flex align-items-center">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="https://sportlogin.app/img/logo-blue.png" width="90px" />
                        <h1 class="card-title">Maintenance in progress</h1>
                        <p class="card-text">{{$message}}</p>
                        <p class="card-text">Maintenance start date : {{$date}}</p>

                        @if($isSuperAdmin)
                        <a href="{{ route('admin.maintenance.update') }}" class="btn btn-primary">Modifier les paramètres de maintenance</a>
                        @else
                        <a href="{{ route('agenda') }}" class="btn btn-primary"><i class="fa fa-refresh"></i> Retry</a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>