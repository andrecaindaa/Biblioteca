<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nova Requisição</title>
</head>
<body>
    <h2>Nova Requisição de Livro</h2>

    <p>Olá {{ $user->name }},</p>

    <p>Foi registada uma nova requisição do livro <strong>{{ $livro->nome }}</strong>.</p>

    <ul>
        <li><strong>ISBN:</strong> {{ $livro->isbn }}</li>
        <li><strong>Editora:</strong> {{ $livro->editora?->nome }}</li>
        <li><strong>Data Requisição:</strong> {{ $requisicao->data_requisicao->format('d/m/Y') }}</li>
        <li><strong>Data Prevista de Entrega:</strong> {{ $requisicao->data_prevista_entrega->format('d/m/Y') }}</li>
        <li><strong>Número da Requisição:</strong> {{ $requisicao->numero }}</li>
    </ul>

    @if($livro->imagem_capa)
        <p>Capa do livro anexada.</p>
    @endif

    <p>Obrigado,<br>Equipa Biblioteca</p>
</body>
</html>
