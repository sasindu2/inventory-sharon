<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Sharon Inventory</title>
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
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center align-items-center g-4">
            <div class="col-lg-5">
                <div class="text-white">
                    <div class="text-uppercase fw-semibold mb-3">Inventory Management System</div>
                    <h1 class="display-5 fw-bold mb-3">Control stock, warehouse locations, and product visibility from one place.</h1>
                    <p class="lead text-white-50 mb-0">Demo accounts are seeded for both roles. Admins manage the full inventory lifecycle. Regular users browse live stock and locations.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card login-card">
                    <div class="card-body p-4 p-lg-5">
                        @include('partials.flash')
                        <h2 class="h3 mb-4">Sign in</h2>
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
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <button class="btn btn-dark btn-lg w-100">Login</button>
                        </form>

                        <hr class="my-4">

                        <div class="small text-body-secondary">
                            <div><strong>Admin:</strong> `admin@inventory.test` / `password`</div>
                            <div><strong>User:</strong> `user@inventory.test` / `password`</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
