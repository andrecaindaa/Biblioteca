<div class="p-6">
  <div class="flex gap-2 mb-4">
    <input wire:model.debounce.300ms="search" placeholder="Pesquisar ISBN/Nome" class="input input-bordered w-full" />
    <select wire:model="editoraFilter" class="select select-bordered">
      <option value="">Todas Editoras</option>
      @foreach($editoras as $pub)
        <option value="{{ $pub->id }}">{{ $pub->nome }}</option>
      @endforeach
    </select>
    <select wire:model="autorFilter" class="select select-bordered">
      <option value="">Todos Autores</option>
      @foreach($autores as $a)
        <option value="{{ $a->id }}">{{ $a->nome }}</option>
      @endforeach
    </select>
    <select wire:model="perPage" class="select select-bordered w-24">
      <option>10</option><option>25</option><option>50</option>
    </select>
    <button wire:click="exportExcel" class="btn btn-primary">Exportar Excel</button>
    <a href="{{ route('livros.create') }}" class="btn btn-secondary">Novo Livro</a>
  </div>

  <div class="overflow-x-auto">
    <table class="table w-full">
      <thead>
        <tr>
          <th wire:click="sortBy('isbn')" class="cursor-pointer">ISBN</th>
          <th wire:click="sortBy('nome')" class="cursor-pointer">Nome</th>
          <th>Editora</th>
          <th>Autores</th>
          <th wire:click="sortBy('preco')" class="cursor-pointer">Preço</th>
          <th>Imagem</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($livros as $livro)
        <tr>
          <td>{{ $livro->isbn }}</td>
          <td>{{ $livro->nome }}</td>
          <td>{{ $livro->editora->nome ?? '' }}</td>
          <td>
            @foreach($livro->autores as $autor)
              <span class="badge mr-1">{{ $autor->nome }}</span>
            @endforeach
          </td>
          <td>{{ number_format($livro->preco,2) }}</td>
          <td>
            @if($livro->imagem_capa)
              <img src="{{ Storage::url($livro->imagem_capa) }}" class="h-16 object-cover" />
            @endif
          </td>
          <td class="flex gap-2">
            <a href="{{ route('livros.edit', $livro) }}" class="btn btn-sm btn-info">Editar</a>
            <form method="POST" action="{{ route('livros.destroy', $livro) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-error" onclick="return confirm('Eliminar este livro?')">Eliminar</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $livros->links() }}
  </div>
</div>
