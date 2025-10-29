<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
  public function index(): View
  {
    return view('inventory.category.index', [
      'categories' => Category::orderBy('name')->get(),
      'type' => 'show'
    ]);
  }

  public function create(): View
  {
    return view('inventory.category.form', [
      'type' => 'create'
    ]);
  }

  public function store(Request $request): RedirectResponse|JsonResponse
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:categories,name',
    ]);

    $category = Category::create([
      'name' => $request->name
    ]);


    if ($request->ajax()) {
      return response()->json($category);
    }

    return redirect()->route('category.index')->with('status', 'Kategori berhasil ditambahkan');
  }

  public function edit(Category $category): View
  {
    return view('inventory.category.form', [
      'category' => $category,
      'type' => 'edit'
    ]);
  }

  public function update(Request $request, Category $category): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:categories,name,' . $category->id
    ]);

    $category->update([
      'name' => $request->name
    ]);

    return redirect()->route('category.index')->with('status', 'Kategori berhasil diubah');
  }

  public function destroy(Category $category): RedirectResponse
  {
    $category->delete();

    return redirect()->route('category.index')->with('status', 'Kategori berhasil dihapus');
  }
}
