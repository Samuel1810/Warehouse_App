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
        margin-right: 10px;
        background-color: #006bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        text-decoration: none;    
        font-weight: bold;
    }

    .btn:hover {
        background-color: gray;
    }

    .user-table {
        margin-top: 30px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 99%; /* Largura da tabela */
        margin: 10 auto; /* Centralizar a tabela */
    }

    .user-table table {
        width: 100%;
    }

    .user-table th, .user-table td {
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #ccc;
    }

    .user-table th {
        background-color: black;
        color: #fff;
    }

    .user-table .edit {
        color: #007bff;
        text-decoration: none;
    }

    .user-table .edit:hover{
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
    Projetos
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
            <h1>Gestão de Projetos</h1>
        </div>

        <div class="user-table">
            <h2 class="text-h2">Projetos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Número do Projeto</th>
                        <th>Descrição</th>
                        <th>Editar</th>
                        <th>Remover</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr class="items">
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->description }}</td>
                            <td>
                                <a class="edit-button" href="{{ route('admin.edit.project', ['projectId' => $project->id]) }}">Editar</a>
                            </td>
                            <td>
                                <a  class="edit-button" href="{{ route('admin.remove.project', ['projectId' => $project->id]) }}" onclick="return confirm('Tem certeza que deseja remover este projeto?')">Remover</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a class="btn btn-info" data-toggle="modal" data-target="#createProjModal">
        Criar Novo Projeto
    </a>

    @if (session('success_message'))
        <p class="message success-message">{{ session('success_message') }}</p>
    @endif
</body>

<!-- Modal 1 -->
<div class="modal fade" id="createProjModal" tabindex="-1" role="dialog" aria-labelledby="createProjModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Criar um novo projeto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <h5>Criar Projeto:</h5>
                                    <form action="{{ route('admin.store.project') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="description">Descrição</label>
                                        <textarea class="form-control" id="description" name="description" required></textarea>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</html>
