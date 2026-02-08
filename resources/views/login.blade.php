<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        /* Reset & base */
        * {
            box-sizing: border-box;
            font-family: 'Instrument Sans', sans-serif;
        }

        body {
            background: #f2f2f2;
        }

        /* Card container */
        .container {
            max-width: 350px;
            background: linear-gradient(0deg, #ffffff 0%, #f1f1f1 100%);
            border-radius: 40px;
            padding: 25px 35px;
            border: 5px solid #ffffff;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 30px 30px -20px;
            margin: 20px;
        }

        /* Heading */
        .heading {
            text-align: center;
            font-weight: 900;
            font-size: 30px;
            color: rgba(0, 0, 0, 0.8);
        }

        /* Form */
        .form {
            margin-top: 20px;
        }

        /* Inputs */
        .form .input {
            width: 100%;
            background: white;
            border: none;
            padding: 15px 20px;
            border-radius: 20px;
            margin-top: 15px;
            box-shadow: rgba(0, 0, 0, 0.08) 0px 10px 10px -5px;
            border-inline: 2px solid transparent;
        }

        .form .input::placeholder {
            color: #aaaaaa;
        }

        .form .input:focus {
            outline: none;
            border-inline: 2px solid rgba(0, 0, 0, 0.8);
        }

        /* Forgot password */
        .form .forgot-password {
            display: block;
            margin-top: 10px;
            margin-left: 10px;
        }

        .form .forgot-password a {
            font-size: 11px;
            color: rgba(0, 0, 0, 0.8);
            text-decoration: none;
        }

        .form .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Login button */
        .form .login-button {
            display: block;
            width: 100%;
            font-weight: bold;
            background: linear-gradient(45deg,
                    rgba(0, 0, 0, 0.85) 0%,
                    rgba(0, 0, 0, 0.65) 100%);
            color: #ffffff;
            padding-block: 15px;
            margin: 20px auto;
            border-radius: 20px;
            box-shadow: rgba(0, 0, 0, 0.5) 0px 20px 10px -15px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        /* Button hover */
        .form .login-button:hover {
            transform: scale(1.03);
            box-shadow: rgba(0, 0, 0, 0.6) 0px 23px 12px -18px;
        }

        /* Button active */
        .form .login-button:active {
            transform: scale(0.95);
            box-shadow: rgba(0, 0, 0, 0.7) 0px 15px 10px -10px;
        }

        /* Social section */
        .social-account-container {
            margin-top: 25px;
        }

        .social-account-container .title {
            display: block;
            text-align: center;
            font-size: 10px;
            color: rgba(0, 0, 0, 0.6);
        }

        /* Social buttons */
        .social-account-container .social-accounts {
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 5px;
        }

        .social-account-container .social-accounts .social-button {
            background: linear-gradient(45deg, #000000 0%, #444444 100%);
            border: 5px solid white;
            padding: 5px;
            border-radius: 50%;
            width: 40px;
            aspect-ratio: 1;
            display: grid;
            place-content: center;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 12px 10px -8px;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .social-account-container .social-accounts .social-button:hover {
            transform: scale(1.2);
            box-shadow: rgba(0, 0, 0, 0.6) 0px 16px 12px -10px;
        }

        .social-account-container .social-accounts .social-button:active {
            transform: scale(0.9);
        }

        /* Agreement */
        .agreement {
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        .agreement a {
            text-decoration: none;
            color: rgba(0, 0, 0, 0.8);
            font-size: 9px;
        }

        .agreement a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="form-back">
        <div class="container ms-auto me-auto py-5 mt-5" >
            <div class="heading">Sign In</div>
            <form method="POST" action="{{ route('login') }}" class="form">
                @csrf

                <input required="" class="input" type="email" name="email" id="email" placeholder="E-mail">
                <input required="" class="input" type="password" name="password" id="password" placeholder="Password">
                <span class="forgot-password"><a href="#">Forgot Password ?</a></span>
                <input class="login-button" type="submit" value="Sign In">
                <span class="forgot-password">New to the Website <a href="{{ route('view') }}" style="color: rgba(88, 80, 80, 0.8); font-size:15px;">Sign in!!</a></span>
            </form>
        </div>
        @if ($errors->any())
        <div class="alert text-danger">

            @foreach ($errors->all() as $error)
            <p>{{$error}}</p>
            @endforeach
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>