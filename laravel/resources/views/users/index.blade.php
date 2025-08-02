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

    <h1>Lista de funcionários</h1>

    <table class="mt-3 table table-bordered">
      <thead>
        <tr>
          <th class="table-secondary text-center">Id</th>
          <th class="table-secondary text-center">Nome</th>
          <th class="table-secondary text-center">Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
          <tr>
            <td class="text-center">{{ $user->id }}</td>
            <td class="text-center">{{ $user->name }}</td>
            <td class="text-center">
              <div class="d-flex align-items-center justify-content-center">
                <a href="{{ route('users.show', $user->id) }}" class="text-white btn btn-info">Ver detalhes</a>

                <a href="{{ route('users.edit', $user->id) }}" class="text-white btn btn-warning mx-2">Editar</a>
                
                <form action="{{ route('users.delete', $user->id) }}" method="POST">
                  @csrf
                  @method('DELETE')

                  <button onclick="return confirm('Quer mesmo remover esse funcionário?')" id="delete-btn" type="submit" class="btn btn-danger">Remover</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection