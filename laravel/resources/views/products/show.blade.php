@extends('layouts.app')

@section('content')
  <div class=" container">
    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('errors'))s
      <div class="alert alert-danger">
        {{ session('errors') }}
      </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
      <h1>Detalhes de: {{ $product->name }}</h1>

      <a href="{{ route('products.index') }}" class="btn btn-secondary d-flex align-items-center mr-2">
        Voltar
      </a>
    </div>

    @if ($product->type === 'simple')
      <table class="table table-bordered mt-3">
        @php
          $types = [
            'simple' => 'Simples',
            'compound' => 'Composto'
          ];
        @endphp
        <thead>
          <tr>
            <th class="table-secondary text-center">Id</th>
            <th class="table-secondary text-center">Nome</th>
            <th class="table-secondary text-center">Preço de venda</th>
            <th class="table-secondary text-center">Preço de custo</th>
            <th class="table-secondary text-center">Tipo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center">{{ $product->id }}</td>
            <td class="text-center">{{ $product->name }}</td>
            <td class="text-center">{{ $product->sale_price }}</td>
            <td class="text-center">{{ $product->cost_price }}</td>
            <td class="text-center">{{ $types[$product->type] }}</td>
          </tr>
        </tbody>
      </table>

      <div class="card p-3 mb-3">
        <p class="text-muted text-xl-start">
          <i>Quantidade em estoque: {{ $stockQuantity }}</i>
        </p>
    </div>

    @elseif ($product->type === 'compound')
      <table class="table table-bordered mt-3">
        <thead>
          <tr>
            <th class="table-secondary text-center">Componentes</th>
            <th class="table-secondary text-center">Quantidade</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($compoundComponents as $component)
            <tr>
              <td>{{ $component['component_name'] }}</td>
              <td>{{ $component['component_quantity'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="card p-3 mb-3">
        <p class="text-muted text-xl-start">
          <i>Quantidade de componentes em estoque: {{ $stockQuantity }}</i>
        </p>
       </div>
    @endif

    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Entrada no estoque</h3>
        <form action="{{ route('stock.products.checkIn', $product->id) }}" method="POST">
          @csrf
          @method('POST')

          <div class="form-group">
            <label for="product_quantity">Quantidade requisitada</label>
            <input type="number" name="product_quantity" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary">Registrar entrada</button>
        </form>
      </div>
    </div>
  </div>
@endsection