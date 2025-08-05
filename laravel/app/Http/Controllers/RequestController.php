<?php

namespace App\Http\Controllers;

use App\ProductCompose;
use App\Stock;
use App\StockMovement;
use Illuminate\Http\Request as RequestHttp;
use App\Request;
use App\RequestItem;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RequestController extends Controller
{
  public function listAll() {
    $requests = Request::all();

    $requests = $requests->map(function ($request) {
      return [
        'id' => $request->id,
        'request_date' => $request->request_date,
        'user_name' => User::find($request->user_id)->name,
        'description' => $request->description,
      ];
    });

    return view('requests.index', compact('requests'));
  }

  public function seeRequestDetails(int $id) {
    $request = Request::find($id);

    $requestUser = User::find($request->user_id);

    $items = RequestItem::where('request_id', $id)->get();

    $items = $items->map(function ($item) {
      return [
        'id' => $item->id,
        'product_id' => $item->product_id,
        'items_quantity' => $item->items_quantity,
        'product_name' => Product::find($item->product_id)->name,
      ];
    });

    return view('requests.show', compact('request', 'items', 'requestUser'));
  }

  public function startARequest(RequestHttp $httpRequest) {
    $validator = Validator::make($httpRequest->all(), [
      // 'user_id' => 'required|exists:users,id',
      'request_date' => 'required|date',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.items_quantity' => 'nullable|integer|min:1',
    ]);

    if($validator->fails()) {
      return redirect()->route('requests.create')->with('errors', $validator->errors());
    }

    $request = Request::create([
      'user_id' => Auth::user()->id,
      'request_date' => $httpRequest->request_date,
      'description' => $httpRequest->description,
    ]);
    
    $items = array_filter($httpRequest->items, function ($item) {
      return $item['items_quantity'] !== null && $item['items_quantity'] > 0;
    });

    foreach($items as $item) {
      RequestItem::create([
        'request_id' => $request->id,
        'product_id' => $item['product_id'],
        'items_quantity' => $item['items_quantity'],
      ]);
    }

    return redirect()->route('requests.index')->with('success', 'Requisição criada.');
  }

  public function executeRequest(RequestHttp $httpRequest, int $requestId) { // apenas de saída do estoque
    $validator = Validator::make($httpRequest->all(), [
      'user_id' => 'required|exists:users,id',
      'request_date' => 'required|date',
      'items' => 'required|array',
      'items.*.id' => 'required|exists:request_items,id',
      'items.*.items_quantity' => 'required|integer|min:1',
      'items.*.product_id' => 'required|exists:products,id',
    ]);

    $request = Request::find($requestId);

    if(!$request) {
      return redirect()->route('requests.index')->with('errors', 'Requisição não encontrada.');
    }

    if($validator->fails()) {
      return redirect()->route('requests.show', $requestId)->with('errors', $validator->errors()->first());
    }
    
    foreach ($httpRequest->items as $item) {
      $product = Product::find($item['product_id']);
      
      if ($product->type === 'simple') {
        $prodStock = Stock::where('product_id', $product->id)->first(); // checar o único produto simples

        if ($prodStock->product_quantity < $item['items_quantity']) {
          return redirect()->route('requests.show', $requestId)->with('errors', 'Não é possível executar a requisição. Existem menos produtos no estoque do que a quantidade dada.');
        }

        StockMovement::create([
          'request_id' => $requestId,
          'product_id' => $product->id,
          'quantity' => $item['items_quantity'],
          'type' => 'out',
          'movement_date' => now(), // a data da requisição não é a data de movimentação - a data de movimentação é a data da execução da requisição
          'cost_price' => $product->cost_price,
        ]);

        $prodStock->product_quantity -= $item['items_quantity'];
        $prodStock->save();

      } else if ($product->type === 'compound') {
        $components = ProductCompose::where('compound_product_id', $product->id)->get();
        
        foreach ($components as $component) {
          $prodStock = Stock::where('product_id', $component->simple_product_id)->first();
          $requiredQuantity = $component->simple_product_quantity * $item['items_quantity'];

          if ($requiredQuantity > $prodStock->product_quantity) {
            return redirect()->route('requests.show', $requestId)->with('errors', 'Não é possível executar a requisição. Existem menos produtos no estoque do que a quantidade dada.');
          }

          StockMovement::create([
            'product_id' => $component->simple_product_id,
            'request_id' => $requestId,
            'type' => 'out',
            'quantity' => $requiredQuantity,
            'movement_date' => now(),
            'cost_price' => Product::find($component->simple_product_id)->cost_price,
          ]);

          $prodStock->product_quantity -= $requiredQuantity;
          $prodStock->save();
        }
      }
    }

    return redirect()->route('requests.index')->with('success', 'Requisição executada.');
  }

  public function updateRequest(RequestHttp $httpRequest, int $id) {
    $validator = Validator::make($httpRequest->all(), [
      'user_id' => 'required|exists:users,id',
      'request_date' => 'required|date',
      'items' => 'required|array',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.items_quantity' => 'required|integer|min:1',
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
        'items_quantity' => $item['items_quantity'],
      ]);
    }

    return redirect()->route('requests.index')->with('success', 'Requisição atualizada.');
  }

  public function deleteRequest(int $id) {
    $request = Request::find($id);
    $request->delete();
    return redirect()->route('requests.index')->with('success', 'Requisição deletada.');
  }

  public function createRequestForm() {
    $users = User::all();

    $products = Product::all();

    return view('requests.create', compact('users', 'products'));
  }

  public function updateRequestForm(int $id) {
    $request = Request::find($id);

    $items = RequestItem::where('request_id', $id)->get();
    
    $items = $items->map(function ($item) {
      return [
        'id' => $item->id,
        'product_id' => $item->product_id,
        'items_quantity' => $item->items_quantity,
        'product_name' => Product::find($item->product_id)->name,
      ];
    });
    
    $products = Product::all();

    $requestUser = User::find($request->user_id);
    
    return view('requests.edit', compact('request', 'items', 'products', 'requestUser'));
  }
}
