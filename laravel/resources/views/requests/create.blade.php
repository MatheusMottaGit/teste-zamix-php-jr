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
      <h1>Criar requisição</h1>

      <a href="{{ route('requests.index') }}" class="btn btn-secondary d-flex align-items-center">
        Voltar
      </a>
    </div>
    
    <form method="POST" action="{{ route('requests.register') }}" class="mt-4 container card p-4">
      @csrf

      <div class="row">
        <div class="mb-2 col">
          <label for="request_date" class="form-label">Data de retirada</label>
          <input type="date" name="request_date" id="request_date" class="form-control">
        </div>

        <div class="mb-2 col">
          <label for="description" class="form-label">Descrição (opcional)</label>

          <input type="text" name="description" id="description" class="form-control" placeholder="Ex: Retirada de 5 pacotes de arroz...">
        </div>
      </div>
      
      <div class="mb-2 border-top mt-2 pt-3" id="components-container">
        <strong>Defina a quantidade de cada produto (Preencher, no mínimo, um produto)</strong>

        <div class="row mt-2">
          @foreach ($products as $key => $product)
            <div class="mb-2 col-4">
              <label for="prod-{{ $key }}" class="form-label">{{ $product->name }}</label>

              <input type="hidden" name="items[{{ $key }}][product_id]" value="{{ $product->id }}">

              <input id="prod-{{ $key }}" type="number" class="form-control" name="items[{{ $key }}][items_quantity]" autofocus>
            </div>
          @endforeach
        </div>  
      </div>

      <button type="submit" class="btn btn-primary">Criar</button>
    </form>
  </div>
@endsection