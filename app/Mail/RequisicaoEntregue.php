<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Livro Entregue</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Olá, {{ $user->name }}!</h2>

    <p>Informamos que o livro requisitado foi marcado como entregue:</p>

    <table>
        <tr>
            <td><strong>Livro:</strong></td>
            <td>{{ $livro->nome }}</td>
        </tr>
        <tr>
            <td><strong>ISBN:</strong></td>
            <td>{{ $livro->isbn }}</td>
        </tr>
        <tr>
            <td><strong>Editora:</strong></td>
            <td>{{ $livro->editora?->nome ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Data Requisição:</strong></td>
            <td>{{ $requisicao->data_requisicao->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Data Prevista de Entrega:</strong></td>
            <td>{{ $requisicao->data_prevista_entrega->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Data Real de Entrega:</strong></td>
            <td>{{ $requisicao->data_entrega->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Capa do Livro:</strong></td>
            <td>
                @if($livro->imagem_capa)
                    <img src="{{ asset('storage/' . $livro->imagem_capa) }}" alt="Capa do Livro" width="120">
                @else
                    Sem imagem disponível
                @endif
            </td>
        </tr>
    </table>

    <p>Obrigado por utilizar a Biblioteca Inovcorp.</p>

    <p>📚 Equipa Biblioteca</p>
</body>
</html>
