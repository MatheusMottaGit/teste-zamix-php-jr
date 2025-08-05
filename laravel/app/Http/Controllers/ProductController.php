<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
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

        $compoundComponents = [];

        if ($product->type === 'compound') {
            $components = ProductCompose::where('compound_product_id', $product->id)->get();

            if (count($components) > 0) {
                foreach($components as $component) {
                    $compoundComponents[] = [
                        'component_name' => Product::find($component['simple_product_id'])->name,
                        'component_quantity' => $component['simple_product_quantity']
                    ];
                }
            }
        }

        return view('products.show', compact('product', 'compoundComponents'));
    }

    public function createProduct(CreateProductRequest $request) {
        $validated = $request->validated();

        if ($validated['type'] === 'simple') {
            $product = Product::create([
                'name' => $validated['name'],
                'sale_price' => $validated['sale_price'],
                'cost_price' => $validated['cost_price'],
                'type' => 'simple',
            ]);

            Stock::create([
                'product_id' => $product->id,
                'product_quantity' => 0, // quantia será informada na requisição
            ]);

            return redirect()->route('products.index')->with('success', 'Produto criado.');

        } else if ($validated['type'] === 'compound') {
            // remove os campos nulos, caso o ao criar não precise ter algum produto
            $components = array_filter($validated['components'], function ($component) {
                return $component['quantity'] !== null && $component['quantity'] > 0;
            });

            if (count($components) === 0) {
                return redirect()->route('products.create')->with('errors', 'Informe pelo menos um componente com quantidade.');
            }

            $compoundProduct = Product::create([
                'name' => $validated['name'],
                'sale_price' => $validated['sale_price'],
                'cost_price' => null,
                'type' => 'compound',
            ]);

            $costPrice = 0;
            foreach ($components as $component) {
                $simpleProduct = Product::find($component['id']);

                if (!$simpleProduct) {
                    $compoundProduct->delete();

                    return redirect()->route('products.create')->with('errors', 'Produto simples não encontrado.');
                }

                // adicionar um produto simples ao composto
                ProductCompose::create([
                    'simple_product_id' => $component['id'],
                    'compound_product_id' => $compoundProduct->id,
                    'simple_product_quantity' => $component['quantity'],
                ]);

                // calcular preço de custo do produto composto
                $costPrice += $simpleProduct->cost_price * $component['quantity'];
            }

            $compoundProduct->cost_price = $costPrice;
            $compoundProduct->save();

            return redirect()->route('products.index')->with('success', 'Produto composto criado.');
        }
    }

    public function updateProduct(UpdateProductRequest $request, int $id) {
        $validated = $request->validated();

        $product = Product::find($id);

        if(!$product) {
            return redirect()->route('products.index')->with('errors', 'Produto não encontrado.');
        }

        $oldType = $product->type;
        $newType = $validated['type'];

        if ($oldType !== $newType) {
            return redirect()->route('products.index')->with('errors', 'Não é possível "transformar" um produto simples em composto e vice-versa.');
        }

        if($newType === 'simple') {
            $product->update([
                'name' => $validated['name'],
                'sale_price' => $validated['sale_price'],
                'cost_price' => $validated['cost_price'],
            ]);

        } else if($newType === 'compound') {
            $product->update([
                'name' => $validated['name'],
                'sale_price' => $validated['sale_price']
            ]);

            $totalCostPrice = 0;
            $components = array_filter($validated['components'], function ($component) {
                return $component['quantity'] !== null && $component['quantity'] > 0;
            });

            ProductCompose::where('compound_product_id', $product->id)->delete();

            foreach ($components as $component) {
                $simpleProd = Product::find($component['id']);

                if (!$simpleProd) {
                    return redirect()->route('products.index')->with('errors', 'Produto simples não encontrado.');
                }

                ProductCompose::create([
                    'simple_product_id' => $component['id'],
                    'compound_product_id' => $product->id,
                    'simple_product_quantity' => $component['quantity']
                ]);

                $totalCostPrice += $component['quantity'] * $simpleProd->cost_price;
            }

            $product->update([
                'cost_price' => $totalCostPrice
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produto atualizado.');
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

    public function createProductForm() {
        $simpleProducts = Product::where('type', 'simple')->get();

        $productTypes = [
            'simple' => 'Simples',
            'compound' => 'Composto'
        ];

        return view('products.create', compact('simpleProducts', 'productTypes'));
    }

    public function updateProductForm(int $id) {
        $product = Product::find($id);

        $productTypes = [
            'simple' => 'Simples',
            'compound' => 'Composto'
        ];

        $components = ProductCompose::where('compound_product_id', $product->id)->get();

        $componentsArray = [];

        foreach ($components as $component) {
            $componentsArray[] = [
                'id' => $component->simple_product_id,
                'name' => Product::find($component->simple_product_id)->name,
                'quantity' => $component->simple_product_quantity
            ];
        }

        return view('products.edit', compact('product', 'productTypes', 'componentsArray'));
    }
}
