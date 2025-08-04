<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  // a criação de um usuário (funcionário) se dá no register controller

  public function listAll()
  {
    $users = User::all();

    return view('users.index', compact('users'));
  }

  public function seeUserDetails(int $id) {
    $user = User::find($id);

    return view('users.show', compact('user'));
  }

  public function updateUserForm(int $id)
  {
    $user = User::find($id);
    return view('users.edit', compact('user'));
  }

  public function updateUser(Request $request, int $id) {
    // dd($request->all());
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      // 'email' => 'required|string|email|max:255|unique:users,email',
      'password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
      return redirect()->route('users.edit', $id)->with('errors', $validator->errors());
    }

    $user = User::find($id);
    
    $user->update([
      'name' => $request->name,
      // 'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);

    return redirect()->route('users.index')->with('success', 'Dados do usuário editados.');
  }

  public function deleteUser(int $id) {
    $user = User::find($id);

    $user->delete();

    return redirect()->route('users.index')->with('success', 'Usuário removido.');
  }
}
