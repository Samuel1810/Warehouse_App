    @extends('layouts.app')

    @section('styles')
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f2f2f2;
                margin: 0;
                padding: 0;
            }

            h1 a{
                text-decoration: none;
                color: #fff;
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

            .material-details {
                margin-top: 20px;
            }

            .material-details h2 {
                font-size: 24px;
                color: #333;
            }

            .separation {
                margin-top: 20px;
                border-top: 2px solid #007bff;
            }

            .material-info {
                margin-top: 20px;
                text-align: left;
            }

            .material-info h2 {
                font-size: 20px;
                color: #007bff;
            }

            .material-info p {
                font-size: 16px;
                color: #333;
                margin: 10px 0;
            }

            .quantity-form {
                margin-top: 20px;
            }

            .quantity-form input {
                padding: 10px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .acquire-button {
                margin-top: 20px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                font-size: 18px;
                text-decoration: none;
                display: inline-block;
            }

            .acquire-button:hover {
                background-color: #0056b3;
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

            .message {
                max-width: 300;
                font-size: 18px;
                padding: 10px;
                margin: 10px 0;
                border: 2px solid;
                border-radius: 5px;
                margin: auto;  
                margin-top: 20px;
            }

            .error-message {
                color: red;
                background-color: #fbebe9;
            }

            .success-message {
                color: #155724;
                background-color: #D4EDDA; 
            }

            .material-location{
                font-size: 100;
                position: relative;
            }

            .img{
                height: 250px;
                width: 450px;
            }
        </style>
    @endsection

    <title>Página do Material - {{ $material->nome }}</title>

    @section('content')
        <div class="container">
            <div class="page-title">
                <h1><a href="{{ auth()->user()->isAdmin() ? route('admin.page') : route('user.material') }}">Armazém do Minho</a></h1>
            </div>

            <div class="text-left">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.material.project', ['materialId' => $material->id]) : route('user.material.project', ['materialId' => $material->id]) }}" class="btn btn-danger">Voltar</a>
            </div>
            
            <div class="material-details">
                <h2>Detalhes do Material</h2>
            </div>

            <div class="separation"></div>

            <div class="material-info">
                <h2>Informações</h2>
                <p><strong>Nome:</strong> {{ $material->nome }}</p>
                <p><strong>Descrição:</strong> {{ $material->descricao }}</p>
                <p>
                    <strong>Ficha Técnica:</strong>
                    <img src="data:image/png;base64,{{ base64_encode($material->ficha_tecnica) }}" alt="Ficha Técnica">
                </p>
                <p><strong>Pertence ao: </strong>Projeto {{ $project->id }}</p>
                <p><strong>Armazém: </strong>A{{ $materialProject->warehouse_id }}</p>
                <p><strong>Estante: </strong>E{{ $materialProject->cabinet_id }}</p>
                <div class="separation"></div>
                <p><strong>Quantidade:</strong> {{ $material->quantidade }} kg</p>
            </div>

            <div class="separation"></div>

            <form method="POST" action="{{ route('acquire.material', ['projectId' => $project->id, 'materialId' => $material->id, 'warehouseId' => $materialProject->warehouse_id, 'cabinetId' => $materialProject->cabinet_id]) }}">
            @csrf
                <input type="hidden" name="materialQuantity" value="{{ $material->quantidade }}">
                <div class="quantity-form">
                    <h2>Quantidade Desejada (em kg):</h2>
                    <input type="number" name="quantidade_desejada" id="quantidade_desejada" min="0.1" step="0.1" required>
                    <button type="submit" class="acquire-button">Adquirir</button>

                        @if (session('success_message'))
                            <p class="message success-message">{{ session('success_message') }}</p>
                        @endif

                        @if (session('error_message'))
                            <p class="message error-message">{{ session('error_message') }}</p>
                        @endif

                </div>
            </form>

            @if ($material->quantidade > 0)
                <form method="POST" action="{{ route('material.return', ['projectId' => $project->id, 'materialId' => $material->id, 'warehouseId' => $materialProject->warehouse_id, 'cabinetId' => $materialProject->cabinet_id]) }}">
                @csrf
                    <input type="hidden" name="materialQuantity" value="{{ $material->quantidade }}">
                    <div class="quantity-form">
                        <h2>Quantidade a Devolver (em kg):</h2>
                        <input type="number" name="quantidade_devolucao" id="quantidade_devolucao" min="0.1" step="0.1" required>
                        <button type="submit" class="acquire-button">Devolver</button>

                        @if (session('success_message1'))
                            <p class="message success-message">{{ session('success_message1') }}</p>
                        @endif

                        @if (session('error_message1'))
                            <p class="message error-message">{{ session('error_message1') }}</p>
                        @endif

                    </div>
                </form>
            @endif

        </div>
    @endsection
