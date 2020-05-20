<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Iniciar sesión</title>

    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">

    <meta name="theme-color" content="#563d7c">
    <style>
        body{
            background-color: #a2a2a2;
        }
    </style>
</head>
<body>
<div id="login">
 <div class="content">
     <div class="row">
         <div class="col-md-4"></div>
         <div class="col-md-4">
             <div class="card mt-5">
                 <div class="card-body">
                     <form class="form-signin" action="{{ route('login') }}" method="post">
                         @csrf
                         <div class="text-center mb-4 mt-5">
                             <img class="mb-4" src="{{asset('img/logo.jpeg')}}" alt="" width="100%" height="100px">
                         </div>

                         <div class="form-label-group">
                             <label for="inputEmail">Correo</label>

                             <input type="email" id="inputEmail" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    placeholder="Email address" required autofocus>
                             @error('email')
                             <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                             @enderror
                         </div>

                         <div class="form-label-group mt-3">
                             <label for="inputPassword">Contraseña</label>

                             <input type="password" id="inputPassword" class="form-control form-control-sm @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="Password" required>
                             @error('password')
                             <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                             @enderror
                         </div>
                         <div class="form-group">
                             <button type="submit" class="btn btn-sm btn-primary btn-block mt-3">
                                 Iniciar
                             </button>
                         </div>
                     </form></div>
                 </div>
             </div>

         <div class="col-md-4"></div>
     </div>
 </div>
</div>
</body>
