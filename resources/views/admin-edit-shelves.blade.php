@extends('layouts.app')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#userDropdown").click(function() {
            $(".dropdown-content").toggle();
        });
    });

    function moveArrowWarehouse(direction, warehouseId) {
        moveArrow('warehouse', direction, warehouseId);
    }

    function moveArrowShelf(direction, shelfId, warehouseId) {
        moveArrow('shelf', direction, shelfId, warehouseId);
    }

    function moveArrow(entity, direction, entityId, warehouseId) {
        var token = $('meta[name="csrf-token"]').attr('content');
        console.log('moveArrow:', entity, direction, entityId, warehouseId);

        var arrow = document.getElementById(entity + 'Arrow');
        var step = 5;

        if (direction === 'up') {
        arrow.style.top = (parseInt(arrow.style.top) - step) + 'px';
        } else if (direction === 'down') {
            arrow.style.top = (parseInt(arrow.style.top) + step) + 'px';
        } else if (direction === 'left') {
            arrow.style.left = (parseInt(arrow.style.left) - step) + 'px';
        } else if (direction === 'right') {
            arrow.style.left = (parseInt(arrow.style.left) + step) + 'px';
        }

        var topValue = parseInt(arrow.style.top);
        var leftValue = parseInt(arrow.style.left);

        var url;

        if (entity === 'warehouse') {
            url = '/update-location/' + entity + '/' + entityId;
        } else if (entity === 'shelf') {
            // Corrigindo a construção da URL para 'shelf'
            url = '/update-location/' + entity + '/' + entityId;
            if (warehouseId) {
                url += '/' + warehouseId;
            }
        }

        var data = {
            top: topValue,
            left: leftValue,
            _token: token,
        };

        if (entity === 'shelf' && warehouseId) {
            data.warehouse_id = warehouseId;
        }

        sendUpdateRequest(url, data, 'POST');
    }


    function sendUpdateRequest(url, data, method) {
    $.ajax({
        type: method || 'POST',
        url: url,
        data: data,
        success: function(response) {
            console.log(response.message);
        },
        error: function(error) {
            console.error('Erro ao atualizar a localização:', (error.responseJSON && error.responseJSON.error) || 'Erro desconhecido');
        }
    });
}



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
        text-align: center;
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

    .edit-button{
        text-decoration: none;
    }

    .edit-button:hover {
        text-decoration: underline;
    }

    .material-location{
        font-size: 100;
        position: relative;
    }

    .img{
        height: 250px;
        width: 450px;
    }

    .location{
        text-align: left;
    }

    .warehouse-buttons-container,
    .shelf-buttons-container {
        position: absolute;
        top: 50%;
        right: 60%;
        transform: translate(0, -50%);
        display: flex;
        flex-direction: column;
    }

    .warehouse-buttons-container button,
    .shelf-buttons-container button {
        margin-bottom: 10px;
    
    }

    .shelf-container{
        align-content: center;
    }

    .warehouse, .shelf{
        position: absolute;
        pointer-events: none;
        transform: translate(-50%, -100%); 
    }

    .save-button {
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

    .save-button:hover {
        background-color: #0056b3;
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
            <h1>Detalhes da Prateleira</h1>
        </div>

        <div>
            <a onclick="javascript:history.go(-1)" class="back-button">Voltar</a>
        </div>

        <div class="warehouse-title">
            <h2>Informações da P{{ $shelf->id }}</h2>
        </div>

        <div class="separation"></div>

        <div class="user-details">
            <h2>Informações</h2>
            <p><strong>Nome da Prateleira:</strong> P{{ $shelf->id }}</p>
            <p><strong>Pertence ao Armazém:</strong> A{{ $warehouse->id }}</p>
            <p>
                <strong>
                    Visão Frontal:
                </strong> 
                <div style="position: relative;">
                    <img class="img" src="{{ asset($shelf->front_view) }}" alt="Imagem da Prateleira">
                    <div class="shelf" style="top: {{ $shelf->top }}px; left: {{ $shelf->left }}px;">
                        <span class="material-location">&#x25B4;</span>
                    </div>
                </div>
            </p>       
        </div>
        <div class="separation"></div>
    </div>
        <div class="user-movements">
            <h2>Alterar Localização</h2>
        </div>
        <div class="location">
            <p>
                <strong>
                    Planta
                </strong>
            </p>
            <div class="warehouse-container" style="position: relative;">
                <img class="img" src="{{ asset($warehouse->layout) }}">
                <div class="warehouse-buttons-container">
                    <button class="btn btn-primary" onclick="moveArrowWarehouse('up', '{{ $warehouse->id }}')">↑</button>
                    <button class="btn btn-primary" onclick="moveArrowWarehouse('down', '{{ $warehouse->id }}')">↓</button>
                    <button class="btn btn-primary" onclick="moveArrowWarehouse('left', '{{ $warehouse->id }}')">←</button>
                    <button class="btn btn-primary" onclick="moveArrowWarehouse('right', '{{ $warehouse->id }}')">→</button>
                </div>
                <div id="warehouseArrow" class="warehouse" style="top: {{ $warehouse->top }}px; left: {{ $warehouse->left }}px;">
                    <span class="material-location">&#x25B4;</span>
                </div>
            </div>
            <p>
                <strong>
                    Visão Frontal
                </strong>
            </p>
            <div class="shelf-container" style="position: relative;">
                <img class="img" src="{{ asset($shelf->front_view) }}">
                <div class="shelf-buttons-container">
                <button class="btn btn-primary" onclick="moveArrowShelf('up', '{{ $shelf->id }}', '{{ $shelf->warehouse_id }}')">↑</button>
                <button class="btn btn-primary" onclick="moveArrowShelf('down', '{{ $shelf->id }}', '{{ $shelf->warehouse_id }}')">↓</button>
                <button class="btn btn-primary" onclick="moveArrowShelf('left', '{{ $shelf->id }}', '{{ $shelf->warehouse_id }}')">←</button>
                <button class="btn btn-primary" onclick="moveArrowShelf('right', '{{ $shelf->id }}', '{{ $shelf->warehouse_id }}')">→</button>
                </div>
                <div id="shelfArrow" class="shelf" style="top: {{ $shelf->top }}px; left: {{ $shelf->left }}px;">
                    <span class="material-location">&#x25B4;</span>
                </div>
            </div>
        </div>
