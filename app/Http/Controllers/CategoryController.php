<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('admin.categories.index', ['categories'=> $categories]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
        ]);

        Category::create([
            'name'=>$request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')
                        -> with('success', 'kategori berhasil ditambahkan.');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}
