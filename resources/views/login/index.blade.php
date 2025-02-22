<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Laravel App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            max-width: 450px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 25px;
            padding: 10px 15px;
        }
        .btn-custom {
            background: #6a11cb;
            color: white;
            border-radius: 25px;
        }
        .btn-custom:hover {
            background: #2575fc;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-container">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Welcome Back!</h2>
                <p>Sign in to continue</p>
            </div>

            @if(Session::has('error'))
            <div class="alert alert-danger text-center">
                <strong>{{ Session::get('error') }}</strong>
            </div>
            @endif

            @if(Session::has('success'))
            <div class="alert alert-success text-center">
                <strong>{{ Session::get('success') }}</strong>
            </div>
            @endif

            <form action="{{ route('login-user') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email">
                    </div>
                    @error('email')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password">
                    </div>
                    @error('password')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-custom btn-lg w-100">Login</button>
            </form>

            <!-- <div class="text-center mt-3">
                <a href="#" class="text-decoration-none text-primary">Forgot Password?</a>
            </div> -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
