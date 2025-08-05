<?php

namespace App\Http\Controllers;

use App\ProductCompose;
use App\Stock;
use App\Product;
use App\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StockCheckInRequest;

class StockController extends Controller
{
    public function stockProductsCheckIn(StockCheckInRequest $request, int $productId) {
        $validated = $request->validated();
        
        $product = Product::find($productId);
        
        if ($product->type === 'simple') {
            $prodStock = Stock::where('product_id', $product->id)->first();

            StockMovement::create([
                'request_id' => null, // requisições são apenas de saída
                'product_id' => $product->id,
                'quantity' => $validated['product_quantity'],
                'type' => 'in',
                'movement_date' => now(),
                'cost_price' => $product->cost_price,
            ]);

            $prodStock->product_quantity += $validated['product_quantity'];
            $prodStock->save();
            
        } else if ($product->type === 'compound') {
            $components = ProductCompose::where('compound_product_id', $product->id)->get();

            if (count($components) === 0) {
                return redirect()->route('products.show', $product->id)->with('errors', 'Produtos compostos não constam no estoque. Adicione componentes.');
            }

            StockMovement::create([ // movimentação do produto composto
                'request_id' => null,
                'product_id' => $product->id,
                'quantity' => $validated['product_quantity'], 
                'type' => 'in',
                'movement_date' => now(),
                'cost_price' => $product->cost_price
            ]);

            foreach ($components as $component) {
                $prodStock = Stock::where('product_id', $component->simple_product_id)->first();

                StockMovement::create([ // movimentação do produto componente
                    'request_id' => null,
                    'product_id' => $component->simple_product_id,
                    'quantity' => $component->simple_product_quantity * $validated['product_quantity'], 
                    'type' => 'in',
                    'movement_date' => now(),
                    'cost_price' => $product->cost_price
                ]);

                $prodStock->product_quantity += $component->simple_product_quantity * $validated['product_quantity']; // ex: 12 fardos de 12 unidades = 144 unidades para o estoque
                $prodStock->save();
            }
        }

        return redirect()->route('products.show', $product->id)->with('success', 'Componentes adicionados ao estoque!');
    }
}
