@extends('layouts.app')

@section('styles')
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 40px;
            text-align: center;
        }

        .login-form {
            width: 300px;
        }

        .login-form h2 {
            color: #007bff;
            font-size: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            text-align: left;
            margin-bottom: 10px;

        }

        .form-group input {
            width: 90%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 18px;
        }

        .error-message {
            color: #ff0000;
            font-size: 16px;
            margin-top: 10px;
        }

        .form-group.form-check {
            display: flex;
            align-items: center;
            justify-content: center; /* Centraliza horizontalmente */
        }

        .form-check-input {
            margin-right: 10px;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 15px 30px;
            font-size: 20px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .forgot-password {
            color: #007bff;
            text-decoration: none;
            display: block;
            font-size: 18px;
            margin-top: 15px;
        }

        .link{
            margin-top: 25px;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>

            @if (session()->has('sucesso'))
                {{ session()->get('sucesso') }}
            @endif

            @error('error')
                <span class="error-message">
                    {{ $message }}
                </span>
            @enderror

            @if ('session_expired')
                <span class="error-message">
                    {{ session('session_expired') }}
                </span>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Palavra-passe</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <div class="link">
                Não tem conta?
                <a href="{{ route('register.index') }}">
                    Registe-se
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
