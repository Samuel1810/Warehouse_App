@extends('layouts.app')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#userDropdown").click(function() {
            $(".dropdown-content").toggle();
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
        color: #fff;
    }

    h1 a:hover{
        text-decoration: none;
        color: #fff;
    }

    .container {
        text-align: center;
        padding: 20px;
    }

    .page-title {
        background-color: #007bff;
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
        margin-right: 1050px;
    } 

    .button {
        background-color: #009bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        text-decoration: none;
        font-weight: bold;
    }

    .button:hover {
        background-color: #0056b3;
    }

    .user-table {
        margin-top: 30px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 80%; /* Largura da tabela */
        margin: 10 auto; /* Centralizar a tabela */
    }

    .user-table table {
        width: 100%;
    }

    .user-table th, .user-table td {
        padding: 20px;
        text-align: center; /* Centralizar o texto */
        border-bottom: 1px solid #ccc;
    }

    .user-table th {
        background-color: #007bff;
        color: #fff;
        padding-left: 112px;
    }

    .user-table td {
        padding-left: 112px;
    }

    .user-table a {
        color: #007bff;
        text-decoration: none;
    }

    .user-table a:hover{
        text-decoration: underline;
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

    .search-bar {
        display: flex;
        margin-bottom: 20px;
    }

    .input-container {
        flex: 1;
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .input {
        width: 40%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn-search,
    .btn-clear {
        background-color: #009bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        text-decoration: none;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-search {
        height: 40px;
        font-size: 17px;
    }

    .btn-search:hover,
    .btn-clear:hover {
        background-color: #0056b3;
    }

    .btn-clear {
        background-color: #ccc;
        margin-left: 0;
    }

    .btn-clear:hover {
        background-color: #999;
    }
</style>    

<title>
    {{ $material->nome }}
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
                        <a href="{{ route('admin.stock') }}">Pré-Armazém</a>
                        <a href="{{ route('admin.manage.project') }}">Projetos</a>
                        <a href="{{ route('admin.warehouses.index' ) }}" >Gestão de Armazéns</a>
                    </div>
                </div>
                <a class="log-out" href="{{ route('login.destroy') }}">LogOut</a>
            @endif
        </div>
    </div>
    <div class="container">
        <div class="page-title">
            <h1>{{ $material->nome }}</h1>
        </div>

        <div class="text-left">
            <a href=" {{ route('admin.material') }} " class="btn btn-danger">Voltar</a>
        </div>

        <!-- search bar -->
        <!-- <form method="GET" action="" class="mb-4 flex items-center space-x-2">
            <div class="input-container">
                <input type="text" name="material" placeholder="Pesquisar..." value="{{ request('material') }}" class="input">
                <button type="submit" class="btn-search">Pesquisar</button>
                <a href="" class="btn-clear">Limpar</a>
            </div>
        </form> -->


        <div>
            <table class="user-table">
            <thead>
                    <tr>
                        <th>Projeto</th>
                        <th>Armazém</th>
                        <th>Estante</th>
                        <th>Quantidade (em kg)</th>
                        <th>Requisitar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($materialsProjects as $project)
                        <tr class="items">
                            <td>{{ $project->project_id }}</td>
                            <td>{{ $project->warehouse_id }}</td>
                            <td>{{ $project->cabinet_id }}</td>
                            <td>{{ $project->quantidade }}</td>
                            <td>
                            <a href="{{ route('material.show', ['projectId' => $project->project_id, 'materialId' => $material->id, 'warehouseId' => $project->warehouse_id, 'cabinetId' => $project->cabinet_id]) }}">Ver mais</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>    
        </div>
    </div>
    @endsection
</body>
</html>
