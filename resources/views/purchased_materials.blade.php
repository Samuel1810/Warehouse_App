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
            margin-bottom: 25px;
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

        .material-details {
            margin-top: 20px;
        }

        .material-details h2 {
            font-size: 24px;
            color: #333;
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

        .btn {
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
        }

        .btn-purchase {
            background-color: #4CAF50;
            margin-right: 10px;
        }

        .btn-devolution {
            background-color: #FF5252;
        }

        .btn-purchase:hover {
            background-color: #388E3C;
        }

        .btn-devolution:hover {
            background-color: #D32F2F;
        }

        .operation-buttons {
            display: flex;
            margin-top: 20px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            width: 60%;
            height: auto;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-content .closeBtn {
            background-color: gray;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: bolder;
        }

        .modal-content .openBtn {
            background-color: #006bff;
            color: #fff;
            border: none;  
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
        }

        .modal-content .closeBtn:hover {
            background-color: #6C757D;
        }

        .modal-content .openBtn:hover {
            background-color: #0056b3;
        }

        .form-content {
            margin-bottom: 20px;
        }

        .form-content label {
            margin-bottom: 5px;
        }

        .form-content input {
            padding: 8px;
            margin-bottom: 10px;
        }
    </style>    
@endsection
<script>
    function openModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.style.display = 'flex';
    }

    function closeModal(modalId) {
        var modal = document.getElementById(modalId); 
        modal.style.display = 'none';
    }

    window.onclick = function (event) {
        var purchaseModal = document.getElementById('purchaseModal');
        var devolutionModal = document.getElementById('devolutionModal');

        if (event.target == purchaseModal) {
            closeModal('purchaseModal');
        }

        if (event.target == devolutionModal) {
            closeModal('devolutionModal');
        }
    };
</script>

<title>Página do Utilizador</title>

@section('content')
    <div class="container">
        <div class="page-title">
            <h1>Compras de {{ $material->nome }}</h1>
        </div>

        <div class="text-left">
            <a href="{{ route('admin.stock') }}" class="btn btn-danger">Voltar</a>
        </div>

        <div class="material-details">
            <h2>Detalhes do Material</h2>
        </div>

            <div class="separation"></div>

            <div class="material-info">
                <h2>Informações</h2>
                <p><strong>Nome:</strong> {{ $material->nome }}</p>
                <p><strong>Descrição:</strong> {{ $material->descricao }}</p>
                <p><strong>Uso recomendado:</strong> {{ $material->uso_recomendado }}</p>
                <p>
                    <strong>Ficha Técnica:</strong>
                    <img src="data:image/png;base64,{{ base64_encode($material->ficha_tecnica) }}" alt="Ficha Técnica">
                </p>
            </div>

        <div class="separation"></div>

        <div class="operation-buttons">
            <a class="btn btn-purchase" onclick="openModal('purchaseModal')">
                Adicionar Compra
            </a>
            <a class="btn btn-devolution" onclick="openModal('devolutionModal')">
                Adicionar Devolução
            </a>
        </div>

        <div class="user-movements">
            <h2>Histórico</h2>
        </div>

        @if ($material)
            <table class="movements-table">
                <thead>
                    <th>Data do Documento</th>
                    <th>Quantidade</th>
                    <th>Fornecedor</th>
                    <th>Fabricante</th>
                    <th>Comprovativo</th>
                </thead>
                <tbody>
                    @foreach ($material->purchaseMaterials as $purchaseMaterial)
                        <tr class="items">
                            <td>{{ $purchaseMaterial->data_formatted }}</td>
                            @if ($purchaseMaterial->movement_type == 0)
                                <td class="green-text">+ {{                     $purchaseMaterial->quantity }} kg</td>
                            @else
                                <td class="red-text">- {{ $purchaseMaterial->quantity }} kg</td>
                            @endif
                            <td>{{ $purchaseMaterial->fornecedores->nome }}</td>
                            <td>{{ $purchaseMaterial->manufacturers->name }}</td>
                            <td>
                                @if ($purchaseMaterial->payment_proof)
                                <a href="{{ asset($purchaseMaterial->payment_proof) }}" target="_blank">
                                    Visualizar Comprovativo
                                </a>
                                @else
                                    <a>N/A</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <p>Sem histórico de compras para este material.</p>
                    @endif
                </tbody>
            </table>
        <div id="purchaseModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <form method="post" action="{{ route('admin.stock.store', ['material' => $material->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <h2>Adicionar Compra</h2>
                    <div class="form-content">
                        <label for="date">Data:</label>
                        <input type="date" class="form-control" id="date" name="date" required>

                        <label for="quantity">Quantidade:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                        <br>

                        <label for="supplier">Fornecedor:</label>
                        <input type="number" class="form-control" id="supplier" name="supplier" required>

                        <label for="manufacturer">Fabricante:</label>
                        <input type="number" class="form-control" id="manufacturer" name="manufacturer" required>
                        <br>

                        <label for="payment_proof">Comprovativo:</label>
                        <input type="file" id="payment_proof" name="payment_proof" accept=".png, .jpg, .jpeg, .pdf" class="form-control-file">

                        <!-- Hidden Value(material)
                        <input type="number" class="form-control" id="material" name="material" value="{{ $material->id }}"> -->

                        <input type="number" class="form-control" id="movement_type" name="movement_type" value="0" hidden>
                    </div>
                    <button class="closeBtn" onclick="closeModal('purchaseModal')" type="button">Fechar</button>
                    <button class="openBtn" type="submit">Salvar Compra</button>
                </form>
            </div>
        </div>
        <div id="devolutionModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <form method="post" action="{{ route('admin.stock.devolution', ['material' => $material->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <h2>Adicionar Devolução</h2>
                    <div class="form-content">
                        <label for="date">Data:</label>
                        <input type="date" class="form-control" id="date" name="date" required>

                        <label for="quantity">Quantidade:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                        <br>

                        <label for="supplier">Fornecedor:</label>
                        <input type="number" class="form-control" id="supplier" name="supplier" required>

                        <label for="manufacturer">Fabricante:</label>
                        <input type="number" class="form-control" id="manufacturer" name="manufacturer" required>
                        <br>

                        <!-- Hidden Value(material)
                        <input type="number" class="form-control" id="material" name="material" value="{{ $material->id }}" hidden> -->

                        <input type="number" class="form-control" id="movement_type" name="movement_type" value="1" hidden>
                    </div>
                    <button class="closeBtn" onclick="closeModal('devolutionModal')" type="button">Fechar</button>
                    <button class="openBtn" type="submit">Salvar Compra</button>
                </form>
                @if (session('success_message'))
                    <p class="message success-message">{{ session('success_message') }}</p>
                @endif
                @if (session('error_message'))
                    <p class="message error-message">{{ session('error_message') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
