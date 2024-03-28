@extends('layouts.app')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#userDropdown").click(function() {
            $(".dropdown-content").toggle();
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var materialSelect = document.getElementById('material');
        var currentQuantityInput = document.getElementById('currentQuantity');

        materialSelect.addEventListener('change', function() {
            document.getElementById("materialQuantity").hidden = false;

            var selectedOption = materialSelect.options[materialSelect.selectedIndex];
            var quantity = selectedOption.getAttribute('data-quantity');
            currentQuantityInput.value = quantity;
        });
    });
</script>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
    }

    h1 a{
        text-decoration: none;
        color: #fff;
    }

    .container {
        text-align: center;
        padding: 20px;
    }

    .page-title {
        background-color: black;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        font-size: 28px;
    }

    .page-title h1 {
        margin: 0;
    }

    .button-container {
        margin-top: 20px;
        margin-bottom: 20px;
        margin-right: 1090px;
    } 

    .btn {
        background-color: #009bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .items{
        color: #3E4346;
        font-weight: bold;
    }

    .text-h2 {
        margin-bottom: 10px;
        margin-top: 10px;
        margin-right: 9px;
        font-weight: bold; 
        font-size: 20px;
    }

    .header {
        background-color: gray;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .header h1 {
        margin: 0;
    }

    .user-info {
        font-weight: bold; 
    }

    .user-info a {
        color: #fff;
    }

    .user-dropdown {
        position: relative;
        display: inline-block;
        margin-right: 20px;
    }

    .user-name {
        cursor: pointer;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f2f2f2;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border: 1px solid #ccc; 
        border-radius: 5px;
        left: -35px;
    }

    .dropdown-content a {
        color: gray;
        padding: 12px 10px;
        text-decoration: none;
        display: block;
        font-size: 14px;
        border-bottom: 1px solid #ccc;
    }

    .dropdown-content a:hover {
        background-color: gray;
        color: #f2f2f2;
    }

    .user-dropdown:hover .dropdown-content {
        display: block;
    }

    .user-dropdown i {
        margin-left: 5px;
    }

    .user-info .log-out:hover {
        color: #EA564D;
    }

    .user-details {
            margin-top: 20px;
            text-align: left;
        }

    .user-details h2 {
        font-size: 20px;
        color: #007bff;
    }


    .user-details p {
        font-size: 16px;
        color: #333;
        margin: 10px 0;
    }

    .user-movements {
        margin-top: 20px;
    }

    .user-movements h2 {
        font-size: 20px;
        color: black;
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
        text-align: center;
        font-weight: bold;
    }

    .movements-table th {
        background-color: black;
        color: #fff;
    }

    .movements-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .back-button {
        background-color: black;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 18px;
        text-decoration: none;
        margin-right: 1200px;
        margin-top: 10px;
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

    .separation {
        margin-top: 20px;
        border-top: 2px solid #007bff;
    }

    .warehouse-title {
        margin-top: 20px;
    }

    .warehouse-title h2 {
        font-size: 24px;
        color: #333;
    }

    .btn-more {
        background-color: black;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 18px;
        text-decoration: none;
        margin-top: 10px;
        display: block;
    }

    .btn-more:hover {
        background-color: #0056b3;
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

    .img{
        height: 250px;
        width: 450px;
    }

    .edit-button{
        text-decoration: none;
    }

    .edit-button:hover {
        text-decoration: underline;
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

</style>    

<title>
    Gestão de Armazém
</title>

<body>
    @section('content')
    <div class="header">
        <h1><a href="{{ route('admin.page') }}">Armazém do Minho</a></h1>
        <div class="user-info">
            @if (auth()->check())
                <div class="user-dropdown">
                    <div class="user-name" id="userDropdown">
                        {{ auth()->user()->name }}
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="{{ route('my.account', ['user' => auth()->user()->id]) }}">Minha Conta</a>
                        <a href="{{ route('admin.warehouses.index' ) }}">Gestão de Armazéns</a>
                        <a href="{{ route('admin.stock') }}">Stock</a>
                    </div>
                </div>
                <a class="log-out" href="{{ route('login.destroy') }}">LogOut</a>
            @endif
        </div>
    </div>
    <div class="container">
        <div class="page-title">
            <h1>Detalhes do Armazém</h1>
        </div>

        <div>
            <a onclick="javascript:history.go(-1)" class="btn btn-danger">Voltar</a>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                Abastecimento Armazém
            </button>
        </div>

        <div class="warehouse-title">
            <h2>Informações do A{{ $warehouse->id }}</h2>
        </div>

        <div class="separation"></div>

        <div class="user-details">
                <h2>Informações</h2>
                <p><strong>Nome:</strong> A{{ $warehouse->id }}</p>
                <p>
                    <strong>
                        Planta:
                    </strong> 
                    <div style="position: relative;">
                        <img class="img" src="{{ asset($warehouse->layout) }}">
                    </div>
                </p>       
        </div>

        <div class="separation"></div>

        <div class="user-movements">
            <h2>Estantes</h2>
        </div>

        <table class="movements-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Editar</th>
                    <th>Remover</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cabinets as $cabinet)
                    <tr>
                        <td>P{{ $cabinet->id }}</td>
                        <td>
                            <a class="edit-button" href="{{ route('admin.cabinets.edit', ['warehouse' => $warehouse->id, 'cabinet' => $cabinet->id]) }}">Editar</a>
                        </td>
                        <td>
                            <a class="edit-button" href="{{ route('admin.cabinets.remove', ['warehouse' => $warehouse->id, 'cabinet' => $cabinet->id]) }}">Remover</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (session('success_message'))
        <p class="message success-message">{{ session('success_message') }}</p>
        @endif
        @if (session('error_message'))
            <p class="message error-message">{{ session('error_message') }}</p>
        @endif
    </div>
    <a class="btn" href="{{ route('admin.cabinets.create', ['warehouse' => $warehouse]) }}">
        Criar Nova Prateleira
    </a>


    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Abastecimento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-inline" method="post" action="{{ route('admin.warehouses.stockmovement') }}">
                        @csrf
                        <input type="hidden" id="warehouse" name="warehouse" value="{{ $warehouse->id }}">

                        <div class="form-group mx-sm-3 mb-2">
                            <label for="inputPassword2">Armazém:</label>
                            <input type="text" readonly class="form-control" id="inputPassword2" value="A{{ $warehouse->id }}">
                        </div>

                        <div class="form-group mx-sm-3 mb-2">
                            <label for="material">Material:</label>
                            <select class="form-select form-select-lg mb-3" id="material" name="material" aria-label=".form-select-lg example">
                                <option selected value="">Selecione um Material</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}" data-quantity="{{ $material->quantidade }}">{{ $material->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mx-sm-3 mb-2" id="materialQuantity" hidden>
                            <label for="currentQuantity">Quantidade material:</label>
                            <input id="currentQuantity" type="text" value="" class="form-control"  placeholder="Selecione um Material" readonly>
                        </div>

                        <div class="form-group mx-sm-3 mb-2">
                            <label for="project">Projeto:</label>
                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="project" name="project">
                                <option selected value="">Associar a um Projeto</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->id }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mx-sm-3 mb-2">
                            <label for="quantity">Quantidade desejada:</label>
                            <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Quantidade">
                        </div>

                        <div class="form-group mx-sm-3 mb-2">
                            <label for="cabinet">Estante:</label>
                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="cabinet" name="cabinet">
                                <option selected value="">Selecione uma Estante</option>
                                @foreach ($cabinets as $cabinet)
                                    <option value="{{ $cabinet->id }}">{{ $cabinet->id }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mx-sm-3 mb-2">
                            <label for="inputPassword2">Prateleira:</label>
                            <input type="text" class="form-control" id="inputPassword2" placeholder="Prateleira">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar Abastecimento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>