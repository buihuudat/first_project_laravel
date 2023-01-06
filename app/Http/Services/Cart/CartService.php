<?php

namespace App\Http\Services\Cart;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDO;

class CartService
{
  public function create($request)
  {
    $qty = (int) $request->num_product;
    $product_id = (int) $request->product_id;

    if ($qty <= 0 || $product_id <= 0) {
      Session::flash('error', 'Count or product is wrong');
      return false;
    }

    $carts = Session::get('carts');
    if (is_null($carts)) {
      Session::put('carts', [
        $product_id => $qty
      ]);

      $exists = Arr::exists($carts, $product_id);

      if ($exists) {
        $carts[$product_id] = $carts[$product_id] + $qty;
        Session::put('carts', $carts);
        return true;
      }

      $carts[$product_id] = $qty;
      Session::put('carts', $carts);

      return true;
    }
  }

  public function getProducts()
  {
    $carts = Session::get('carts');
    if (is_null($carts))
      return [];

    $productId = array_keys($carts);
    return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
      ->where('active', 1)->whereIn('id', $productId)->get();
  }

  public function addCart($request)
  {
    try {
      DB::beforeExecuting();
      $carts = Session::get('carts');

      if (is_null($carts))
        return false;

      $customer = Customer::create($request->all());

      $this->infoProductCart($carts, $customer->id);

      DB::commit();
      Session::flash('success', 'Order successfully');

      SendMail::dispatch($request->input('email'))->delay(now()->addSecond(2));

      Session::forget('carts');
    } catch (\Exception $err) {
      DB::rollBack();
      Session::flash('error', 'Order error, try again');
      return false;
    }

    return true;
  }

  protected function infoProductCart($carts, $customer_id)
  {
    $productId = array_keys($carts);
    $products = Product::select('id', 'name', 'price', 'price_sale', 'thumb')
      ->where('active', 1)
      ->WhereIn('id', $productId)
      ->get();

    $data = [];
    foreach ($products as $product) {
      $data[] = [
        'customer_id' => $customer_id,
        'product_id' => $product->id,
        'pty' => $carts[$product->id],
        'price' => $product->price_sale != 0 ? $product->price_sale : $product->price
      ];
    }

    return Cart::insert($data);
  }
}
