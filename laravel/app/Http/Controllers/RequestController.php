<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as RequestHttp;
use App\Request;
use App\RequestItem;

use Validator;

class RequestController extends Controller
{
  public function listAll() {
    $requests = Request::all();
    return view('requests.index', compact('requests'));
  }

  public function startARequest(RequestHttp $httpRequest) {
    $validator = Validator::make($httpRequest->all(), [
      'user_id' => 'required|exists:users,id',
      'request_date' => 'required|date',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|integer|min:1',
    ]);

    if($validator->fails()) {
      return redirect()->route('requests.create')->with('errors', $validator->errors()->first());
    }

    $request = Request::create([
      'user_id' => $httpRequest->user_id,
      'request_date' => $httpRequest->request_date,
    ]);

    foreach($httpRequest->items as $item) {
      RequestItem::create([
        'request_id' => $request->id,
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
      ]);
    }

    return redirect()->route('requests.index')->with('success', 'Requisição criada.');
  }

  public function seeRequestDetails(int $id) {
    $request = Request::find($id);
    
    $items = RequestItem::where('request_id', $id)->get();
      
    return view('requests.show', compact('request', 'items'));
  }

  public function updateRequest(RequestHttp $httpRequest, int $id) {
    $validator = Validator::make($httpRequest->all(), [
      'user_id' => 'required|exists:users,id',
      'request_date' => 'required|date',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|integer|min:1',
    ]);

    if($validator->fails()) {
      return redirect()->route('requests.index')->with('errors', $validator->errors()->first());
    }

    $request = Request::find($id);

    if(!$request) {
      return redirect()->route('requests.index')->with('errors', 'Requisição não encontrada.');
    }

    $request->update([
      'user_id' => $httpRequest->user_id,
      'request_date' => $httpRequest->request_date,
    ]);

    RequestItem::where('request_id', $id)->delete();
    
    foreach($httpRequest->items as $item) {
      RequestItem::create([
        'request_id' => $request->id,
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
      ]);
    }

    return redirect()->route('requests.index')->with('success', 'Requisição atualizada.');
  }

  public function deleteRequest(int $id) {
    $request = Request::find($id);
    $request->delete();
    return redirect()->route('requests.index')->with('success', 'Requisição deletada.');
  }
}
