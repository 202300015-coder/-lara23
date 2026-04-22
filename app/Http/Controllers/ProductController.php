<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('id', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'descriptionLong' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        Product::create($data);

        return redirect()->route('products.index')->with('mensaje', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'descriptionLong' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $product->update($data);

        return redirect()->route('products.index')->with('mensaje', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('mensaje', 'Producto eliminado correctamente.');
    }
}
