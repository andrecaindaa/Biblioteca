<p>Olá Admin,</p>

<p>Foi submetida uma nova review em estado <strong>suspenso</strong>:</p>

<ul>
    <li><strong>Cidadão:</strong> {{ $user->name }} ({{ $user->email }})</li>
    <li><strong>Livro:</strong> {{ $livro->nome }}</li>
    <li><strong>Avaliação:</strong> {{ $review->rating ?? '—' }}</li>
    <li><strong>Comentário:</strong> {{ Str::limit($review->comentario, 300) }}</li>
</ul>

<p>Ver a review: <a href="{{ $link }}">{{ $link }}</a></p>
