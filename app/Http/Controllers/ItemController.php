<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\WholesalePrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ItemController extends Controller
{
  /**
   * @param Request $request
   * @param Item $item
   * @return void
   */
  private static function addWholeSalePrice(Request $request, Item $item): void
  {
    if ($request->wholesale_price) {
      $wholesale_price = [];
      foreach ($request->wholesale_price as $key => $price) {
        $wholesale_price[] = [
          'price' => $price,
          'min_qty' => $request->min_qty[$key]
        ];
      }

      foreach ($wholesale_price as $price) {
        WholesalePrice::create([
          'item_id' => $item->id,
          'min_qty' => $price['min_qty'],
          'price' => $price['price']
        ]);
      }
    }
  }

  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('inventory.item.index', [
      'user' => auth()->user(),
      'items' => Item::with('category')->orderBy('name')->get()->load('wholesalePrices'),
      'type' => 'show'
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show(Item $item): string
  {
    return json_encode($item);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    return view('inventory.item.form', [
      'categories' => Category::orderBy('name')->get(),
      'type' => 'create'
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:items,name',
      'code' => 'required|string|max:255|unique:items,code',
      'category' => 'required|exists:categories,id',
      'cost_price' => 'required|numeric|min:1',
      'selling_price' => 'required|numeric|min:1|gte:cost_price',
      'whole_sale_price' => 'array',
      'min_qty' => 'array',
      'stock' => 'required|numeric|min:0',
      'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
    ]);

    if ($request->hasFile('picture')) {
      $filename = time() . '.' . $request->picture->extension();
      $request->picture->move(public_path('assets/images/items'), $filename);
      $picture_name = $filename;
    } else {
      $picture_name = 'default.png';
    }

    $item = Item::create([
      'name' => $request->name,
      'code' => $request->code,
      'category_id' => $request->category,
      'cost_price' => $request->cost_price,
      'selling_price' => $request->selling_price,
      'stock' => $request->stock,
      'picture' => $picture_name
    ]);

    self::addWholeSalePrice($request, $item);

    return redirect()->route('item.index')->with('status', 'Barang  berhasil ditambahkan');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Item $item): View
  {
    return view('inventory.item.form', [
      'item' => $item,
      'categories' => Category::all(),
      'type' => 'edit'
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Item $item): RedirectResponse
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:items,name,' . $item->id,
      'code' => 'required|string|max:255|unique:items,code,' . $item->id,
      'category' => 'required|exists:categories,id',
      'cost_price' => 'required|numeric|min:1',
      'selling_price' => 'required|numeric|min:1|gte:cost_price',
      'whole_sale_price' => 'array',
      'min_qty' => 'array',
      'stock' => 'required|numeric|min:0',
      'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096'
    ]);

    if ($request->hasFile('picture')) {
      $filename = time() . '.' . $request->picture->extension();
      $request->picture->move(public_path('assets/images/items'), $filename);
      $item->picture = $filename;
      $picture_name = $filename;
    } else {
      $picture_name = $item->picture;
    }

    $item->update([
      'name' => $request->name,
      'code' => $request->code,
      'category_id' => $request->category,
      'cost_price' => $request->cost_price,
      'selling_price' => $request->selling_price,
      'stock' => $request->stock,
      'picture' => $picture_name
    ]);

    $item->wholesalePrices()->delete();

    self::addWholeSalePrice($request, $item);

    return redirect()->route('item.index')->with('status', 'Barang berhasil diubah');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Item $item): RedirectResponse
  {
    // delete item
    $item->delete();
    // delete image from storage if not default image
    if (isset($item->picture)) {
      if ($item->picture !== 'default.png') {
        File::delete('public/images/items/' . $item->picture);
      }
    }
    // redirect to index with success message
    return redirect()->route('item.index')->with('status', 'Barang berhasil dihapus');
  }
}
