@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h1>Detalhes de: {{ $user->name }}</h1>

      <a href="{{ route('users.index') }}" class="btn btn-secondary d-flex align-items-center">
        Voltar
      </a>
    </div>

    <table class="mt-3 table table-bordered">
      <thead>
        <tr>
          <th class="table-secondary text-center">Id</th>
          <th class="table-secondary text-center">Nome</th>
          <th class="table-secondary text-center">Email</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-center">{{ $user->id }}</td>
          <td class="text-center">{{ $user->name }}</td>
          <td class="text-center">{{ $user->email }}</td>
        </tr>
      </tbody>
    </table>
  </div>
@endsection