<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
    <style>
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            margin: 10px;
            display: flex;
            align-items: center;
        }

        .card img {
            width: 180px;
            height: 180px;
            border-radius: 30%;
            margin-right: 10px;
        }

        .card-info {
            float: right;
        }
    </style>   

    <title>USUARIOS</title>
    </head>

    <body>
    
        <div>
            @foreach($users as $user)
                <div class="card">
                    @if($user->photo == null || $user->photo == '')
                        <img src="http://localhost/examtest_back/storage/app/none3.jpg">
                    @else
                        <img src="http://localhost/examtest_back/storage/app/usuarios/{{$user->id}}/imagen/{{$user->photo}}"> 
                    @endif
                    <div style="margin-left: 220px; margin-top: -200px">
                        <h5>{{ $user->name }} {{ $user->last_name }}</h5>
                        <p >
                            {{ $user->phone }} <br>
                            <u style="color: blue">{{ $user->email }}</u> <br>
                            {{ $user->role }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

    </body>
</html>

        
