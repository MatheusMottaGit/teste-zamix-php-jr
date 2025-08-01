@extends('layouts.app')

@section('content')
  <div class="container">
    <h1>Adicionar produto</h1>

    <form method="POST" action="{{ route('products.register') }}" class="mt-3 container card p-4">
      @csrf

      <div class="row">
        <div class="mb-2 col">
          <label for="name" class="form-label">Nome</label>

          <input id="name" type="text" class="form-control" name="name" required autofocus placeholder="Nome do produto...">

          @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('name') }}</strong>
            </span>
          @endif
        </div>

        <div class="mb-2 col">
          <label for="type" class="form-label">Tipo</label>
  
          <select class="form-select w-100 prod-type" name="type" id="type" required autofocus>
            <option selected>Escolha o tipo do produto</option>
            @foreach ($productTypes as $key => $type)
              <option value="{{ $type }}">{{ $key }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="mb-2">
        <label for="sale_price" class="form-label">Preço de venda (em reais)</label>

        <input id="sale_price" type="number" step=".01" class="form-control" name="sale_price" required autofocus>

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
      </div>

      <div class="mb-2 border-top" id="components-container">
        <h2>Produtos simples (componentes)</h2>

        @foreach ($simpleProducts as $product)
          <div class="mb-2">
            <label for="{{ $product->id }}" class="form-label">{{ $product->name }}</label>

            <input id="{{ $product->id }}" type="number" class="form-control" name="{{ $product->id }}" required autofocus>
          </div>
        @endforeach
      </div>

      <div class="mb-2" id="cost-price-container">
        <label for="cost_price" class="form-label">Preço de custo (em reais)</label>

        <input id="cost_price" type="number" step=".01" class="form-control prod-cost-price" name="cost_price" required autofocus>

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
      </div>

      <div class="mb-2" id="start-quantity-container">
        <label for="start_quantity" class="form-label">Quantia inicial (opcional)</label>

        <input id="start_quantity" type="text" class="form-control" name="start_quantity" required autofocus>

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
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