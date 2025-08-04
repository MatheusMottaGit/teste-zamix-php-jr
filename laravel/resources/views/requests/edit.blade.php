@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h1>Editar requisição</h1>

      <a href="{{ route('requests.index') }}" class="btn btn-secondary d-flex align-items-center">
        Voltar
      </a>
    </div>

    <form method="POST" action="{{ route('requests.update', $request->id) }}" class="mt-4 container card p-4">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="mb-2 col">
          <label for="request_date" class="form-label">Data de retirada</label>
          <input type="date" name="request_date" id="request_date" class="form-control" value="{{ $request->request_date }}">
        </div>
      </div>
      
      <div class="mb-2 border-top mt-2 pt-3" id="components-container">
        <strong>Defina a quantidade de cada produto (Preencher, no mínimo, um produto)</strong>

        <div class="row mt-2">
          @foreach ($products as $key => $product)
            <div class="mb-2 col-4">
              <label for="prod-{{ $key }}" class="form-label">{{ $product->name }}</label>
              <input type="number" name="items[{{ $key }}][quantity]" id="prod-{{ $key }}" class="form-control" value="{{ $request->items[$key]['quantity'] }}">
            </div>
          @endforeach
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
  </div>
@endsection   