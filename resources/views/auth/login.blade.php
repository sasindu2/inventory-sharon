<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accesso | {{ config('app.name', 'Bio Motori') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(18, 115, 105, 0.16), transparent 22rem),
                linear-gradient(135deg, #09111f 0%, #123b52 55%, #f5f7fb 55%, #f5f7fb 100%);
        }

        .login-card {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 1.5rem 3rem rgba(0, 0, 0, 0.12);
        }

        .brand-logo {
            max-width: min(280px, 100%);
            height: auto;
        }

        @media (max-width: 991.98px) {
            .login-intro {
                text-align: center;
            }

            .brand-logo {
                display: block;
                margin-inline: auto;
            }
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center align-items-center g-4">
            <div class="col-lg-5">
                <div class="text-white login-intro">
                   
                    <h1 class="display-5 fw-bold mb-3">Gestisci scorte, posizioni di magazzino e visibilita dei prodotti da un unico posto.</h1>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card login-card">
                    <div class="card-body p-4 p-lg-5">
                        @include('partials.flash')
                        <div class="text-center mb-4">
                            <img
                                src="{{ asset('logo/Bio%20motori%20v4.png') }}"
                                alt="{{ config('app.name', 'Bio Motori') }}"
                                class="brand-logo"
                            >
                        </div>
                        <h2 class="h3 mb-4">Accedi</h2>
                        <form method="POST" action="{{ route('login.store') }}" class="vstack gap-3">
                            @csrf
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div>
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ricordami</label>
                            </div>
                            <button class="btn btn-dark btn-lg w-100">Accedi</button>
                        </form>

                        <hr class="my-4">

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
