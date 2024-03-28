@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Recusa de Movimentação</h1>
        <h2>Informações da Movimentação</h2>

        <p><strong>Quem Realizou:</strong> {{ $user->name }}</p>
        <p><strong>Material:</strong> {{ $material->nome }}</p>
        <p><strong>Dono do Material:</strong> {{ $material->owners->first()->name }}</p>
        <p><strong>Tipo de Movimentação:</strong> Aquisição</p>
        <p><strong>Quantidade:</strong> {{ $quantidadeMovimentada }} kg</p>   
        <form action="{{ route('material.movement.reject', ['material' => $material->id, 'token' => $token]) }}" method="post">
            @csrf

            <div class="form-group">
                <strong>
                    <p for="motivo">Motivo de Cancelamento:</p>
                </strong>
                <textarea name="motivo" id="motivo" rows="4" class="form-control"></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-danger">Cancelar Movimentação</button>
        </form>
    </div>
@endsection
