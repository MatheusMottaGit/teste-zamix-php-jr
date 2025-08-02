@extends('layouts.app')

@section('content')
  <div class="container">
    <h1>Editar produto: {{ $product->name }}</h1>

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

        <div class="mb-2"></div>
      @endif

      <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>

    <i class="text-muted">*Não é possível alterar o tipo de um produto.</i>
  </div>
@endsection