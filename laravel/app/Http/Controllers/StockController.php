<?php

namespace App\Http\Controllers;

use App\ProductCompose;
use App\Stock;
use App\Product;
use App\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function stockProductsCheckIn(Request $request, int $productId) {
        $product = Product::find($productId);

        $validator = Validator::make($request->all(), [
            'product_quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('products.show', $product->id)->with('errors', $validator->errors()->first());
        }

        if ($product->type === 'simple') {
            $prodStock = Stock::where('product_id', $product->id)->first();

            StockMovement::create([
                'request_id' => null, // requisições são apenas de saída
                'product_id' => $product->id,
                'quantity' => $request->product_quantity,
                'type' => 'in',
                'movement_date' => now(),
                'cost_price' => $product->cost_price,
            ]);

            $prodStock->product_quantity += $request->product_quantity;
            $prodStock->save();
        } else if ($product->type === 'compound') {
            $components = ProductCompose::where('compound_product_id', $product->id)->get();
            // dd($components);

            if (count($components) === 0) {
                return redirect()->route('products.show', $product->id)->with('errors', 'Produtos compostos não constam no estoque. Adicione componentes.');
            }

            foreach ($components as $component) {
                $prodStock = Stock::where('product_id', $component->simple_product_id)->first();

                StockMovement::create([
                    'request_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $request->product_quantity, 
                    'type' => 'in',
                    'movement_date' => now(),
                    'cost_price' => $product->cost_price
                ]);

                $prodStock->product_quantity += $component->simple_product_quantity * $request->product_quantity; // ex: 12 fardos de 12 unidades = 144 unidades para o estoque
                $prodStock->save();
            }
        }

        return redirect()->route('products.show', $product->id)->with('success', 'Componentes adicionados ao estoque!');
    }
}
