<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquisição de Material</title>
</head>
<body>
    <h1>Aquisição de Material</h1>
    
    <p><strong>Quem Realizou:</strong> {{ $user->name }}</p>
    <p><strong>Material:</strong> {{ $material->nome }}</p>
    <p><strong>Dono do Material:</strong>
        {{ $material->owners->first()->name }}
    </p>
    <p><strong>Tipo de Movimentação:</strong> {{ $tipoMovimento }}</p>
    <p><strong>Quantidade:</strong> {{ $quantidadeMovimentada }} kg</p>
    <p><strong>Data do Movimento:</strong> {{ $dataMovimento->format('d/m/Y H:i:s') }}</p>

    <p>Movimentação Realizada!</p>

    @if($isOwner)
        <strong>
            <p>Deseja cancelar a movimentação?
                <a href="{{ route('material.movement.reject.form', [
                        'material' => $material->id,
                        'token' => encrypt(auth()->user()->id),
                        'redirectToRejectionForm' => 1,
                        'quantidadeMovimentada' => encrypt($quantidadeMovimentada),
                        'tipoMovimento' => encrypt($tipoMovimento),
                    ]) }}">Cancelar Movimentação
                </a>


            </p>
        </strong>
    @endif
    <p><strong>Armazém do Minho - Departamento de Polímeros</strong></p>

</body>
</html>