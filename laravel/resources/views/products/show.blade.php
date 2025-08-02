@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h1>Detalhes de: {{ $product->name }}</h1>

      <a href="{{ route('products.index') }}" class="btn btn-secondary d-flex align-items-center">
        Voltar
      </a>
    </div>

    <table class="mt-3 table table-bordered">
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
          @if ($product->type !== 'compound')
            <th class="table-secondary text-center">Preço de custo</th>
          @endif
          <th class="table-secondary text-center">Tipo</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-center">{{ $product->id }}</td>
          <td class="text-center">{{ $product->name }}</td>
          <td class="text-center">{{ $product->sale_price }}</td>
          @if ($product->type !== 'compound')
            <td class="text-center">{{ $product->cost_price }}</td>
          @endif
          <td class="text-center">{{ $types[$product->type] }}</td>
        </tr>
      </tbody>
    </table>

    @if ($product->type === 'compound')
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="table-secondary text-center">Componentes</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($compoundComponents as $component)
            <tr>
              <td>{{ $component['component_name'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
@endsection