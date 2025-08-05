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

    <div class="d-flex justify-content-between align-items-center">
      <h1>Detalhes da requisição</h1>

      <div class="d-flex align-items-center">
        <a href="{{ route('requests.index') }}" class="btn btn-secondary d-flex align-items-center mr-2">
          Voltar
        </a>

        <form action="{{ route('requests.execute', $request->id) }}" method="POST">
          @csrf
          @method('POST')

          <input type="hidden" name="user_id" value="{{ $request->user_id }}" value="{{ $request->user_id }}">
          <input type="hidden" name="request_date" value="{{ $request->request_date }}" value="{{ $request->request_date }}">

          @foreach ($items as $key => $item)
            <input type="hidden" name="items[{{ $key }}][id]" value="{{ $item['id'] }}">
            <input type="hidden" name="items[{{ $key }}][items_quantity]" value="{{ $item['items_quantity'] }}">
            <input type="hidden" name="items[{{ $key }}][product_id]" value="{{ $item['product_id'] }}">
          @endforeach

          <button onclick="return confirm('Quer mesmo executar essa requisição?')" type="submit" class="btn btn-primary">Executar</button>
        </form>
      </div>
    </div>

    <table class="mt-3 table table-bordered">
      <thead>
        <tr>
          <th class="table-secondary text-center">ID</th>
          <th class="table-secondary text-center">Data de retirada</th>
          <th class="table-secondary text-center">Funcionário</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-center">{{ $request->id }}</td>
          <td class="text-center">{{ $request->request_date }}</td>
          <td class="text-center">{{ $requestUser->name }}</td>
        </tr>
      </tbody>
    </table>

    <h2 class="mt-3">Itens da requisição</h2>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="table-secondary text-center">ID</th>
            <th class="table-secondary text-center">Produto</th>
            <th class="table-secondary text-center">Quantidade</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
            <tr>
              <td class="text-center">{{ $item['id'] }}</td>
              <td class="text-center">{{ $item['product_name'] }}</td>
              <td class="text-center">{{ $item['items_quantity'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
  </div>
@endsection                 