<?php

namespace App\Http\Services\Product;

use App\Models\Menu;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use PDO;

class ProductAdminService
{
  public function getMenu()
  {
    return Menu::where('active', 1)->get();
  }

  protected function isValidPrice($request)
  {
    if ($request->price != 0 && $request->price_sale != 0 && $request->price_sale >= $request->price) {
      Session::flash('error', 'Price sale must be less than original price');
      return false;
    }

    if ($request->price_sale != 0 && (int)$request->price == 0) {
      Session::flash('error', 'Fiel Price must required');
      return false;
    }

    return true;
  }

  public function insert($productReuest)
  {
    $isValidPrice = $this->isValidPrice($productReuest);
    if ($isValidPrice !== false) {
      try {
        $productReuest->except('_token');
        Product::create($productReuest->all());

        Session::flash('success', 'Add product successfully');
      } catch (\Exception $err) {
        Session::flash('error', 'Add product failed' . $err);
        // Log::info($err->getMessage());

        return false;
      }
    }
    return false;
  }

  public function gets()
  {
    return Product::orderBy('id', 'desc')->paginate(20);
  }

  public function update($request, $product)
  {
    $isValidPrice = $this->isValidPrice($request);
    if (!$isValidPrice)
      return false;

    try {
      $product->fill($request->input());
      $product->save();

      Session::flash('success', 'Updated successfully');
      return true;
    } catch (\Exception $err) {
      Session::flash('error', 'Updated failed');
      return false;
    }
  }

  public function delete($request)
  {
    $product = Product::where('id', $request->id)->first();
    if ($product) {
      $product->delete();
      return true;
    }

    return false;
  }
}
