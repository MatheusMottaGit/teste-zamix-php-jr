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

    public function seeProductDetails(int $id) {
        $product = Product::find($id);

        if ($product->type === 'compound') {
            $components = ProductCompose::where('compound_product_id', $product->id)->get();
            
            $compoundComponents = [];

            if (count($components) > 0) {
                foreach($components as $component) {
                    $compoundComponents[] = [
                        'component_name' => Product::find($component['simple_product_id'])->name 
                    ];
                }
            }
        }

        return view('products.show', compact('product', 'compoundComponents'));
    }

    public function createProduct(Request $request) {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            'cost_price' => 'nullable|numeric',
            'type' => 'required|string|in:simple,compound',
            'start_quantity' => 'nullable|numeric|min:0',
            'components' => 'nullable|array',
        ]);

        if($validator->fails()) {
            return redirect()->route('products.create')->with('errors', $validator->errors());
        }

        if ($request->type === 'compound') {
            $simpleProducts = Product::where('type', 'simple')->get();

            if (count($simpleProducts) === 0) {
                return redirect()->route('products.create')->with('errors', 'Para criar um produto composto, é necessário ter produtos simples no estoque.');
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

                    return redirect()->route('products.create')->with('errors', 'Produto simples não encontrado.');
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

            return redirect()->route('products.index')->with('success', 'Produto composto criado e adicionado ao estoque.');

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

        return redirect()->route('products.index')->with('success', 'Produto criado e adicionado ao estoque.');
    }

    public function createProductForm() {
        $productTypes = [
            'Simples' => 'simple',
            'Composto' => 'compound'
        ];

        $simpleProducts = Product::where('type', 'simple')->get();

        return view('products.create', compact('productTypes', 'simpleProducts'));
    }

    public function updateProduct(Request $request, int $id) {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            'cost_price' => 'nullable|numeric',
            'type' => 'required|string|in:simple,compound',
        ]);

        if($validator->fails()) {
            return redirect()->route('products.index')->with('errors', $validator->errors());
        }

        $product = Product::find($id);

        // if(!$product) {
        //     return view('products.edit')->with('errors', 'Produto não encontrado.');
        // }

        $oldType = $product->type;
        $newType = $request->type;

        if ($oldType !== $newType) {
            return redirect()->route('products.index')->with('errors', 'Não é possível "transformar" um produto simples em composto e vice-versa.');
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
                        return redirect()->route('products.index')->with('errors', 'Este produto está classificado como composto.');
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

        return redirect()->route('products.index')->with('success', 'Produto atualizado.');
    }

    public function updateProductForm(int $id) {
        $product = Product::find($id);

        $productTypes = [
            'simple' => 'Simples',
            'compound' => 'Composto'
        ];

        return view('products.edit', compact('product', 'productTypes'));
    }

    public function deleteProduct(int $id) {
        $product = Product::find($id);

        if(!$product) {
            return redirect()->route('products.index')->with('errors', 'Produto não encontrado.');
        }

        if($product->type === 'simple') {
            $compoundProdComponent = ProductCompose::where('simple_product_id', $product->id)->first();

            if ($compoundProdComponent) {
                return redirect()->route('products.index')->with('errors', 'Este produto está sendo usado em compostos, não é possível remover.');
            }
            
            $productMovements = StockMovement::where('product_id', $product->id)->get();

            if ($productMovements->count() > 0) {
                return redirect()->route('products.index')->with('errors', 'Este produto possui movimentações registradas, não é possível removê-lo.');
            }

            Stock::where('product_id', $product->id)->delete();

            $product->delete();
        } else if ($product->type === 'compound') {
            ProductCompose::where('compound_product_id', $product->id)->delete();

            $product->delete();
        }   

        return redirect()->route('products.index')->with('success', 'Produto removido.');
    }
}
