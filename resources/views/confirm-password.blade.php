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

    .password-change {
        margin-top: 20px;
    }

    .password-change h2 {
        font-size: 20px;
        color: #007bff;
    }


    .password-change p {
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

    .user-title {
        margin-top: 20px;
    }

    .user-title h2 {
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

    .form-group.form-check {
        display: flex;
        align-items: center;
        justify-content: center; /* Centraliza horizontalmente */
    }
</style>    

<title>
    Página do Gestor
</title>

<body>
    @section('content')
    <div class="header">
        <h1><a href="{{ auth()->user()->isAdmin() ? route('admin.page') : route('user.material') }}">Armazém do Minho</a></h1>
        <div class="user-info">
            @if (auth()->check())
                <div class="user-dropdown">
                    <div class="user-name" id="userDropdown">
                        {{ auth()->user()->name }}
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="{{ route('my.account', ['user' => auth()->user()->id]) }}">Minha Conta</a>
                        <a href="{{ route('admin.stock') }}">Estoque</a>
                    </div>
                </div>
                <a class="log-out" href="{{ route('login.destroy') }}">LogOut</a>
            @endif
        </div>
    </div>
    <div class="container">
        <div class="page-title">
            <h1>Atualizar Palavra-Passe</h1>
        </div>

        <div>
            <a onclick="javascript:history.go(-1)" class="back-button">Voltar</a>
        </div>

        <div class="form-group">
            <h2>Alterar Palavra-Passe</h2>
            <p><strong>Palavra-Passe Antiga:</strong></p>
            <input type="text" name="" id="">
            <p><strong>Palavra-Passe Nova:</strong></p>
            <input type="text" name="" id="">
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror 
            <br>
            <button type="submit" class="btn btn-primary">Alterar Palavra-Passe</button>
        </div>
    </div>