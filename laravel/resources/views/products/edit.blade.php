@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h1>Editar produto: {{ $product->name }}</h1>
      
      <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">Voltar</a>
    </div>

    <form method="POST" action="{{ route('products.update', $product->id) }}" class="mt-3 container card p-4">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="mb-2 col">
          <label for="name" class="form-label">Nome</label>

          <input id="name" type="text" class="form-control" name="name" autofocus placeholder="Nome do produto..." value="{{ $product->name }}">

          @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('name') }}</strong>
            </span>
          @endif
        </div>

        <div class="mb-2 col">
          <label for="type" class="form-label">Tipo</label>

          <!-- para exibir o tipo -->
          <input type="text" class="form-control" value="{{ $productTypes[$product->type] }}" name="type-show" autofocus readonly disabled>

          <input id="type" type="hidden" class="form-control" value="{{ $product->type }}" name="type" autofocus>
        </div>
      </div>
      
      <div class="mb-2">
        <label for="sale_price" class="form-label">Preço de venda (em reais)</label>

        <input id="sale_price" type="number" step=".01" class="form-control" value="{{ $product->sale_price }}" name="sale_price" autofocus>

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
      </div>

      @if ($product->type === 'simple')
        <div class="mb-2">
          <label for="cost_price" class="form-label">Preço de custo (em reais)</label>

          <input id="cost_price" type="number" step=".01" class="form-control" value="{{ $product->cost_price }}" name="cost_price" autofocus>
        </div>
      @endif

      @if ($product->type === 'compound')
        @foreach ($componentsArray as $key => $component)
          <div class="mb-2">
            <label for="components" class="form-label">{{ $component['name'] }}</label>

            <input type="hidden" name="components[{{ $key }}][id]" value="{{ $component['id'] }}">

            <input id="quantity-{{ $key }}" type="number" class="form-control" value="{{ $component['quantity'] }}" name="components[{{ $key }}][quantity]" autofocus>
          </div>
        @endforeach
      @endif

      <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>

    <i class="text-muted">*Não é possível alterar o tipo de um produto.</i>
  </div>
@endsection