<p>Olá {{ $user->name }},</p>

@if($review->status === 'ativo')
    <p>A sua avaliação para o livro <strong>{{ $livro->nome }}</strong> foi aprovada e já está visível no detalhe do livro. Obrigado pelo feedback!</p>
@else
    <p>A sua avaliação para o livro <strong>{{ $livro->nome }}</strong> foi recusada pelo administrador.</p>
    @if($review->justificacao_recusa)
        <p><strong>Justificação:</strong> {{ $review->justificacao_recusa }}</p>
    @endif
@endif

<p>Atenciosamente,<br />Sistema Biblioteca</p>
