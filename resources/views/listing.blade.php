<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div>
        <table class="table table-striped table-responsive">
            <tr>
                <th>name</th>
                <th>email</th>
                <th>date</th>
            </tr>
            @foreach($users as $user)
            <tr>
                
                <td>{{ $user->name}}</td>
                <td>{{ $user->email}}</td>
                <td>{{ $user->created_at}}</td>
               
            </tr>
            @endforeach
        </table>
    </div>
    <div>
    {!! $html !!}
    </div>
</body>
</html>