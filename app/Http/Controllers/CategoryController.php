<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('mensaje', 'Categoria creada correctamente.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('mensaje', 'Categoria actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'No se puede eliminar la categoria porque tiene productos asociados.');
        }

        try {
            $category->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'No se puede eliminar la categoria porque tiene productos asociados.');
        }

        return redirect()->route('categories.index')->with('mensaje', 'Categoria eliminada correctamente.');
    }
}