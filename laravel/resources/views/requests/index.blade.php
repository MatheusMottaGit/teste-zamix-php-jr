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

    <div class="d-flex justify-content-between align-content-center">
      <h1>Controle de requisições</h1>

      <a href="{{ route('requests.create') }}" class="btn btn-primary d-flex align-items-center">
        Adicionar
      </a>
    </div>

    @if (count($requests) === 0)
      <div class="card mt-3 p-3 pb-0">
        <p class="text-center">
          <i>Não existem requisições a serem controladas no momento.</i>
        </p>
      </div>
    @else
      <table class="mt-3 table table-bordered">
        <thead>
          <tr>
            <th class="table-secondary text-center">ID</th>
            <th class="table-secondary text-center">Data de retirada</th>
            <th class="table-secondary text-center">Funcionário</th>
            <th class="table-secondary text-center">Descrição</th>
            <th class="table-secondary text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($requests as $request)
            <tr>
              <td class="text-center">{{ $request->id }}</td>
              <td class="text-center">{{ $request->request_date }}</td>
              <td class="text-center">{{ $request->user_id }}</td>
              <td class="text-center">{{ $request->description }}</td>
              <td class="text-center">
                <div class="d-flex align-items-center justify-content-center">
                  <a href="{{ route('requests.show', $request->id) }}" class="text-white btn btn-info">Ver detalhes</a>

                  <a href="{{ route('requests.edit', $request->id) }}" class="text-white btn btn-warning mx-2">Editar</a>
                  
                  <form action="{{ route('requests.delete', $request->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button onclick="return confirm('Quer mesmo retirar essa requisição?')" id="delete-btn" type="submit" class="btn btn-danger">Retirar</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
@endsection