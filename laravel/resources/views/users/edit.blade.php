@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h1>Editar dados de: {{ $user->name }}</h1>

      <a href="{{ route('users.index') }}" class="btn btn-secondary d-flex align-items-center">
        Voltar
      </a>
    </div>

    <form method="POST" action="{{ route('users.update', $user->id) }}" class="mt-3 container card p-4">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="mb-2 col">
          <label for="name" class="form-label">Nome</label>

          <input id="name" type="text" class="form-control" name="name" autofocus value="{{ $user->name }}">

          @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('name') }}</strong>
            </span>
          @endif
        </div>

        <div class="mb-2 col">
          <label for="email" class="form-label">E-mail</label>

          <input id="email" type="email" class="form-control" value="{{ $user->email }}" name="email" autofocus disabled>
        </div>
      </div>
      
      <div class="mb-2">
        <label for="password" class="form-label">Senha</label>

        <input id="password" type="password" class="form-control" value="{{ $user->password }}" name="password" autofocus>

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
      </div>

      <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
  </div>
@endsection