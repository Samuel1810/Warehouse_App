@extends('layouts.app')

@section('styles')
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .page-title {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            font-size: 25px;
            margin-bottom: 20px;
        }

        .page-title h1 {
            margin: 0;
        }

        .user-details {
            margin-top: 20px;
        }

        .user-details h2 {
            font-size: 24px;
            color: #333;
        }

        .separation {
            margin-top: 20px;
            border-top: 2px solid #007bff;
        }

        .user-info {
            margin-top: 20px;
            text-align: left;
        }

        .user-info h2 {
            font-size: 20px;
            color: #007bff;
        }

        .user-info p {
            font-size: 16px;
            color: #333;
            margin: 10px 0;
        }

        .user-movements {
            margin-top: 20px;
        }

        .user-movements h2 {
            font-size: 20px;
            color: #007bff;
        }

        .movements-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .movements-table th,
        .movements-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center; /* Centraliza o conteúdo */
        }

        .movements-table th {
            background-color: #007bff;
            color: #fff;
        }

        .movements-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .back-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 18px;
            text-decoration: none;
            margin-right: 1200px;
            display: block;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .green-text {
            color: green;
        }

        .red-text {
            color: red;
        }
    </style>
@endsection

<title>Página do Utilizador</title>

@section('content')
    <div class="container">
        <div class="page-title">
            <h1>Detalhes do Utilizador</h1>
        </div>

        <div class="text-left">
            <a href="{{ route('admin.page') }}" class="btn btn-danger">Voltar</a>
        </div>

        <div class="user-details">
            <h2>Informações do Utilizador</h2>
        </div>

        <div class="separation"></div>

        <div class="user-info">
                <h2>Dados Pessoais</h2>
                <p><strong>ID:</strong> {{ $user->id }}</p>
                <p><strong>Nome:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Número de Movimentos:</strong> {{ $user->movements->count() }}</p>          
        </div>

        <div class="separation"></div>

        <div class="user-movements">
            <h2>Movimentos</h2>
        </div>

        <table class="movements-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Material</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($movements as $movement)
                        <tr>
                            <td> 
                                <strong>
                                    <p> {{ $movement->data_movimento }}</p>
                                </strong>
                            </td>
                            <td>
                                <strong>
                                    <p> {{ $movement->material->nome }}</p>
                                </strong>
                            </td>
                            <td>
                                <strong>
                                    @if ($movement->tipo_movimento == 1)
                                        <p class="green-text">+ {{ $movement->quantidade }} kg</p>
                                    @else
                                        <p class="red-text">- {{ $movement->quantidade }} kg</p>
                                    @endif
                                </strong>
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
@endsection
