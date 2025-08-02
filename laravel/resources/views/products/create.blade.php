@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h1>Adicionar produto</h1>

      <a href="{{ route('products.index') }}" class="btn btn-secondary d-flex align-items-center">
        Voltar
      </a>
    </div>

    <form method="POST" action="{{ route('products.register') }}" class="mt-4 container card p-4">
      @csrf

      <div class="row">
        <div class="mb-2 col">
          <label for="name" class="form-label">Nome</label>

          <input id="name" type="text" class="form-control" name="name" autofocus placeholder="Nome do produto...">

          @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('name') }}</strong>
            </span>
          @endif
        </div>

        <div class="mb-2 col">
          <label for="type" class="form-label">Tipo</label>
  
          <select class="form-select w-100 prod-type" name="type" id="type" autofocus>
            <option selected>Escolha o tipo do produto</option>
            @foreach ($productTypes as $key => $type)
              <option value="{{ $type }}">{{ $key }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mb-2">
        <label for="sale_price" class="form-label">Preço de venda (em reais)</label>

        <input id="sale_price" type="number" step=".01" class="form-control" name="sale_price" autofocus>

        @if ($errors->has('sale_price'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('sale_price') }}</strong>
            </span>
        @endif
      </div>

      <div class="mb-2 border-top mt-2 pt-3" id="components-container">
        <strong>Defina a quantidade de cada componente</strong>

        <div class="row mt-2">
          @foreach ($simpleProducts as $key => $product)
            <div class="mb-2 col">
              <label for="prod-{{ $key }}" class="form-label">{{ $product->name }}</label>

              <input type="hidden" name="components[{{ $key }}][id]" value="{{ $product->id }}">

              <input id="prod-{{ $key }}" type="number" class="form-control" name="components[{{ $key }}][quantity]" autofocus>
            </div>
          @endforeach
        </div>
      </div>

      <div class="mb-2" id="cost-price-container">
        <label for="cost_price" class="form-label">Preço de custo (em reais)</label>

        <input id="cost_price" type="number" step=".01" class="form-control prod-cost-price" name="cost_price" autofocus>

        @if ($errors->has('cost_price'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('cost_price') }}</strong>
            </span>
        @endif
      </div>

      <div class="mb-2" id="start-quantity-container">
        <label for="start_quantity" class="form-label">Quantia inicial (opcional)</label>

        <input id="start_quantity" type="text" class="form-control" name="start_quantity" autofocus>

        @if ($errors->has('start_quantity'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('start_quantity') }}</strong>
            </span>
        @endif
      </div>

      <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const prodType = document.getElementById('type');
      const prodCostPrice = document.getElementById('cost-price-container');
      const prodStartQuantity = document.getElementById('start-quantity-container');
      const componentsContainer = document.getElementById('components-container');
      
      componentsContainer.style.display = 'none';

      prodType.addEventListener('change', () => {
        if (prodType.value === 'compound') {
          // console.log(prodType);
          prodStartQuantity.style.display = 'none';
          componentsContainer.style.display = 'block';
          prodCostPrice.style.display = 'none';

        } else if (prodType.value === 'simple') {
          prodCostPrice.style.display = 'block';
          prodStartQuantity.style.display = 'block';
          componentsContainer.style.display = 'none';
        }
      })
    });
  </script>
@endsection