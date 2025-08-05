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

        <div class="mb-2 col">
          <label for="description" class="form-label">Descrição</label>
          <input type="text" name="description" id="description" class="form-control" value="{{ $request->description }}">
        </div>

        <div class="mb-2 col">
          <label for="user_name" class="form-label">Funcionário</label>
          <input type="text" id="user_name" class="form-control" value="{{ $requestUser->name }}" readonly>

          <input type="hidden" name="user_id" id="user_id" value="{{ $requestUser->id }}">
        </div>
      </div>
      
      <div class="mb-2 border-top mt-2 pt-3" id="components-container">
        <strong>Atualize o(s) produto(s) que serão retirados, e sua(s) quantidade(s).</strong>

        <div class="row mt-3">
          @foreach ($items as $key => $item)
            <div class="mb-2 col">  
              <label for="prod-{{ $key }}" class="form-label">{{ $item['product_name'] }}</label>
              <input type="number" name="items[{{ $key }}][items_quantity]" id="prod-{{ $key }}" class="form-control" value="{{ $item['items_quantity'] }}">

              <input type="hidden" name="items[{{ $key }}][product_id]" id="prod-{{ $key }}" value="{{ $item['product_id'] }}">
            </div>
          @endforeach
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
  </div>
@endsection   