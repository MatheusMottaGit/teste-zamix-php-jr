@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-content-center">
      <h1>Lista de produtos</h1>

      <a href="{{ route('products.create') }}" class="btn btn-primary">Criar</a>
    </div>

    <table class="mt-3 table table-bordered">
      <thead>
        <tr>
          <th class="table-info text-center">Id</th>
          <th class="table-info text-center">Nome</th>
          <th class="table-info text-center">Tipo</th>
          <th class="table-info text-center">Preço de venda</th>
          <th class="table-info text-center">Preço de custo</th>
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
            <th class="text-center">{{ $prod->id }}</th>
            <th class="text-center">{{ $prod->name }}</th>
            <th class="text-center">{{ $types[$prod->type] }}</th>
            <th class="text-center">{{ $prod->sale_price }}</th>
            <th class="text-center">{{ $prod->cost_price }}</th>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection