<?php

namespace App\Http\Controllers;

use App\Http\Services\Cart\CartService;
use App\Http\Services\Menu\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
  protected $cartService, $menuService;
  public function __construct(CartService $cartService, MenuService $menuService)
  {
    $this->cartService = $cartService;
    $this->menuService = $menuService;
  }
  public function index(Request $request)
  {
    $result = $this->cartService->create($request);
    if ($result) {
      return redirect()->back();
    }

    return redirect('/cart');
  }

  public function cart(Request $request)
  {
    return view('cart.list', [
      'title' => 'Carts',
      'products' => $this->cartService->getProducts($request),
      'carts' => Session::get('carts'),
      'menus' => $this->menuService->show()
    ]);
  }

  public function addCart(Request $request)
  {
    $this->cartService->addCart($request);
    return redirect()->back();
  }
}
