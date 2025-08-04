@extends('layouts.app')

@section('content')
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('errors'))
      <div class="alert alert-danger">
        {{ session('errors') }}
      </div>
    @endif

    <div class="d-flex justify-content-between align-content-center">
      <h1>Lista de produtos</h1>

      <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center">
        Adicionar
      </a>
    </div>

    @if (count($products) === 0)
      <div class="card mt-3 p-3 pb-0">
        <p class="text-center">
          <i>Não existem produtos no estoque, no momento.</i>
        </p>
      </div>
    @else
      <table class="mt-3 table table-bordered">
        <thead>
          <tr>
            <th class="table-secondary text-center">Id</th>
            <th class="table-secondary text-center">Nome</th>
            <th class="table-secondary text-center">Tipo</th>
            <th class="table-secondary text-center">Preço de venda</th>
            <th class="table-secondary text-center">Preço de custo</th>
            <th class="table-secondary text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($products as $prod)
            @php
              $types = [
                'simple' => 'Simples',
                'compound' => 'Composto'
              ];
            @endphp
            <tr>
              <td class="text-center">{{ $prod->id }}</td>
              <td class="text-center">{{ $prod->name }}</td>
              <td class="text-center">{{ $types[$prod->type] }}</td>
              <td class="text-center">{{ $prod->sale_price }}</td>
              <td class="text-center">{{ $prod->cost_price }}</td>
              <td class="text-center">
                <div class="d-flex align-items-center justify-content-center">
                  <a href="{{ route('products.show', $prod->id) }}" class="text-white btn btn-info mx-2">Ver detalhes</a>

                  <a href="{{ route('products.edit', $prod->id) }}" class="text-white btn btn-warning mx-2">Editar</a>
                  
                  <form action="{{ route('products.delete', $prod->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button onclick="return confirm('Quer mesmo remover esse produto?')" id="delete-btn" type="submit" class="btn btn-danger">Remover</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
@endsection