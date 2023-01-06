<?php

namespace App\Http\Controllers;

use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
  protected $menuService, $productService;

  public function __construct(MenuService $menuService, ProductService $productService)
  {
    $this->menuService = $menuService;
    $this->productService = $productService;
  }
  public function index(Request $request, $id, $slug = '')
  {
    $menu = $this->menuService->getId($id);
    return view('menu', [
      'title' => $menu->name,
      'products' => $this->productService->getProducts($menu, $request),
      'menus' => [$menu]
    ]);
  }
}
