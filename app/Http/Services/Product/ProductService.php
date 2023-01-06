<?php

namespace App\Http\Services\Product;

use App\Models\Product;

class ProductService
{
  public function gets()
  {
    return Product::where('active', 1)->orderBy('price', 'asc')->get();
  }

  public function getProducts($menu, $request)
  {
    $query = $menu->products()
      ->select('id', 'name', 'price', 'price_sale', 'thumb')
      ->where('active', 1);

    if ($request->input('price')) {
      $query->orderBy('price', $request->input('price'));
    }

    return $query
      ->orderByDesc('id')
      ->paginate(12)
      ->withQueryString();
  }

  public function show($id)
  {
    return Product::where('id', $id)
      ->where('active', 1)
      ->with('menu')
      ->firstOrFail();
  }

  public function more($id)
  {
    return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
      ->where('active', 1)
      ->where('id', '!=', $id)
      ->orderBy('id', 'desc')
      ->limit(8)
      ->get();
  }
}
