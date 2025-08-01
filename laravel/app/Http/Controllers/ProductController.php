<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductCompose;
use App\Stock;
use App\StockMovement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function listAll() {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function createProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            'cost_price' => 'nullable|numeric',
            'type' => 'required|string|in:simple,compound',
            'start_quantity' => 'nullable|numeric|min:0',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar produto.',
                'errors' => $validator->errors(),
            ], 400);
        }

        if ($request->type === 'compound') {
            $simpleProducts = Product::where('type', 'simple')->get();

            if (count($simpleProducts) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Para criar um produto composto, é necessário ter produtos simples no estoque.',
                ], 400);
            }

            $compoundProduct = Product::create([
                'name' => $request->name,
                'sale_price' => $request->sale_price,
                'cost_price' => null,
                'type' => 'compound',
            ]);

            $costPrice = 0;
            foreach ($request->components as $component) {
                $simpleProduct = Product::find($component['id']);

                if (!$simpleProduct) {
                    $compoundProduct->delete();

                    return response()->json([
                        'success' => false,
                        'message' => 'Produto simples não encontrado.',
                    ], 404);
                }

                // add simple product to create compound product
                ProductCompose::create([
                    'simple_product_id' => $component['id'],
                    'compound_product_id' => $compoundProduct->id,
                    'simple_product_quantity' => $component['quantity'],
                ]);

                // calculate cost price
                $product = Product::find($component['id']);
                $costPrice += $product->cost_price * $component['quantity'];
            }

            $compoundProduct->cost_price = $costPrice;
            $compoundProduct->save();

            return response()->json([
                'success' => true,
                'message' => 'Produto composto criado e adicionado ao estoque.',
                'product' => $compoundProduct,
            ], 201);

        } else if ($request->type === 'simple') {
            $product = Product::create($request->all());

            Stock::create([
                'product_id' => $product->id,
                'product_quantity' => $request->start_quantity > 0 ? $request->start_quantity : 0,
            ]);

            if($request->start_quantity > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $request->start_quantity,
                    'movement_date' => now(),
                    'cost_price' => $request->cost_price,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produto criado e adicionado ao estoque.',
            'product' => $product,
        ], 201);
    }

    public function updateProduct(Request $request, int $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            'cost_price' => 'nullable|numeric',
            'type' => 'required|string|in:simple,compound',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar produto.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado.',
            ], 404);
        }

        $oldType = $product->type;
        $newType = $request->type;

        if ($oldType !== $newType) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível "transformar" um produto simples em composto e vice-versa.',
            ], 400);
        }

        if($newType === 'simple') {
            $product->update($request->all());
        } else if($newType === 'compound') {
            $product->update([
                'name' => $request->name,
                'sale_price' => $request->sale_price
            ]);

            $costPrice = 0;
            if ($request->components && count($request->components) > 0) {
                ProductCompose::where('compound_product_id', $product->id)->delete();

                foreach ($request->components as $component) {
                    $simpleProd = Product::find($component['id']);

                    if ($simpleProd->type !== 'simple') {
                        return response()->json([
                            'success' => false,
                            'message' => 'Este produto está classificado como composto.',
                        ]);
                    }

                    ProductCompose::create([
                        'simple_product_id' => $component['id'],
                        'compound_product_id' => $product->id,
                        'simple_product_quantity' => $component['quantity']
                    ]);

                    $costPrice += $component['quantity'] * $simpleProd->cost_price;
                }

                $product->update([
                    'cost_price' => $costPrice
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produto atualizado.',
            'product' => $product,
        ]);
    }

    public function deleteProduct(int $id) {
        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado.',
            ]);
        }

        if($product->type === 'simple') {
            $compoundProdComponent = ProductCompose::where('simple_product_id', $product->id)->first();

            if ($compoundProdComponent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este produto está sendo usado em compostos, não é possível remover.',
                ], 500);
            }

            Stock::where('product_id', $product->id)->delete();
            $product->delete();
        } else if ($product->type === 'compound') {
            ProductCompose::where('compound_product_id', $product->id)->delete();
            $product->delete();
        }   

        return response()->json([
            'success' => true,
            'message' => 'Produto removido.',
        ]);
    }
}
